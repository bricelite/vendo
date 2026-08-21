<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DescriptionIaService
{
    private string $cléApi;
    private string $url;
    private string $modèle;

    public function __construct()
    {
        $this->cléApi = config('services.groq.clé_api', '');
        $this->url = config('services.groq.url', 'https://api.groq.com/openai/v1/chat/completions');
        $this->modèle = config('services.groq.modèle', 'llama-3.3-70b-versatile');
    }

    public function estConfiguré(): bool
    {
        return ! empty($this->cléApi);
    }

    public function genererDescription(string $nomProduit, string $prix, string $modeLangage): string
    {
        if (! $this->estConfiguré()) {
            throw new \RuntimeException('La clé API Groq n\'est pas configurée. Ajoutez GROQ_API_KEY dans votre fichier .env');
        }

        $prompt = $this->construirePrompt($nomProduit, $prix, $modeLangage);

        try {
            $réponse = Http::withHeaders([
                'Authorization' => 'Bearer '.$this->cléApi,
                'Content-Type' => 'application/json',
            ])->timeout(15)->post($this->url, [
                'model' => $this->modèle,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Tu es un rédacteur de contenu spécialisé dans les descriptions de produits pour les vendeurs sur les réseaux sociaux au Bénin. Tu écris de manière courte, percutante, avec des emojis et des hashtags pertinents.',
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt,
                    ],
                ],
                'temperature' => 0.8,
                'max_tokens' => 300,
            ]);

            if ($réponse->failed()) {
                Log::error('Erreur API Groq', [
                    'statut' => $réponse->status(),
                    'corps' => $réponse->body(),
                ]);
                throw new \RuntimeException('Erreur lors de la génération de la description. Réessayez.');
            }

            $données = $réponse->json();
            $texte = $données['choices'][0]['message']['content'] ?? '';

            if (empty($texte)) {
                throw new \RuntimeException('Réponse vide de l\'IA. Réessayez.');
            }

            return trim($texte);
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Timeout connexion Groq', ['erreur' => $e->getMessage()]);
            throw new \RuntimeException('Connexion lente. Vérifiez votre connexion internet et réessayez.');
        } catch (\RuntimeException $e) {
            throw $e;
        } catch (\Throwable $e) {
            Log::error('Erreur inattendue Groq', ['erreur' => $e->getMessage()]);
            throw new \RuntimeException('Une erreur inattendue est survenue. Réessayez.');
        }
    }

    public function suggérerCatégorie(string $nomProduit, string $description, array $catégories): ?int
    {
        if (! $this->estConfiguré()) {
            return null;
        }

        $liste = collect($catégographies)->map(fn ($c) => "ID {$c['id']}: {$c['nom']}")->implode("\n");

        $prompt = "Voici la liste des catégories disponibles d'une boutique :\n";
        $prompt .= $liste."\n\n";
        $prompt .= "Produit à classifier :\n";
        $prompt .= "Nom : {$nomProduit}\n";
        if (! empty($description)) {
            $prompt .= "Description : ".mb_substr($description, 0, 300)."\n";
        }
        $prompt .= "\nRéponds UNIQUEMENT avec le numéro (ID) de la catégorie la plus appropriée. ";
        $prompt .= "Si aucune catégorie ne correspond, réponds « 0 ».";

        try {
            $réponse = Http::withHeaders([
                'Authorization' => 'Bearer '.$this->cléApi,
                'Content-Type' => 'application/json',
            ])->timeout(10)->post($this->url, [
                'model' => $this->modèle,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Tu es un classificateur de produits pour des boutiques en ligne au Bénin. Tu réponds toujours avec un seul numéro entier, rien d\'autre.',
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt,
                    ],
                ],
                'temperature' => 0.1,
                'max_tokens' => 5,
            ]);

            if ($réponse->failed()) {
                Log::error('Erreur API Groq (catégorie)', ['statut' => $réponse->status()]);
                return null;
            }

            $texte = trim($réponse->json('choices.0.message.content', ''));
            $id = (int) $texte;

            $idsValides = collect($catégories)->pluck('id')->toArray();

            return in_array($id, $idsValides) ? $id : null;
        } catch (\Throwable $e) {
            Log::error('Erreur suggestion catégorie', ['erreur' => $e->getMessage()]);
            return null;
        }
    }

    private function construirePrompt(string $nomProduit, string $prix, string $modeLangage): string
    {
        $base = "Écris une description de produit pour une boutique en ligne au Bénin.\n\n";
        $base .= "Produit : {$nomProduit}\n";
        $base .= "Prix : {$prix} FCFA\n\n";
        $base .= "Règles obligatoires :\n";
        $base .= "- Maximum 3 lignes\n";
        $base .= "- Termine par 3-5 hashtags pertinents\n";
        $base .= "- Utilise des emojis pour rendre accrocheur\n";
        $base .= "- Ne répète pas le prix dans le texte (il est déjà affiché à côté)\n";

        return match ($modeLangage) {
            'standard' => $base.
                "\nMode : Français standard, professionnel mais chaleureux.\n".
                "Exemple de ton : description claire, qui met en avant la qualité du produit.",
            'decontracte' => $base.
                "\nMode : Français décontracté, comme un ami qui te parle.\n".
                "Exemple de ton : 'Ce truc est trop bien', 'Foncez les gars', 'Le meilleur prix que tu vas trouver'.",
            'beninois' => $base.
                "\nMode : Français béninois avec des expressions locales.\n".
                "Utilise des mots comme : wègè, na wam, c'est bien o, gbègbe, yé, kpassa, àvè, dji.\n".
                "Exemple de ton : 'Na wam, ce produit c'est wègè !', 'Pour ce prix, c'est kpassa !'.",
            default => $base,
        };
    }
}
