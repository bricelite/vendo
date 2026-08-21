<?php

namespace App\Services;

use App\Models\Boutique;
use App\Models\Commande;
use App\Models\Produit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CommandeService
{
    /**
     * Crée une commande pour la boutique, avec ses lignes, et décrémente le stock.
     * Applique la réduction fidèle si le client est éligible.
     *
     * @param  array<int, array{produit_id: int, quantite: int}>  $articlesPanier
     * @param  array{client_nom: string, client_telephone: string, client_localite: string|null, user_id: int|null}  $infosClient
     *
     * @throws \RuntimeException si le stock est insuffisant pour un article
     */
    public function creerCommande(Boutique $boutique, array $articlesPanier, array $infosClient): Commande
    {
        return DB::transaction(function () use ($boutique, $articlesPanier, $infosClient) {
            $montantTotal = 0;
            $lignesPretees = [];

            foreach ($articlesPanier as $article) {
                $produit = Produit::where('boutique_id', $boutique->id)
                    ->findOrFail($article['produit_id']);

                if ($produit->stock_quantite < $article['quantite']) {
                    Log::warning("Stock insuffisant pour le produit {$produit->id}", [
                        'demande' => $article['quantite'],
                        'stock' => $produit->stock_quantite,
                    ]);
                    throw new \RuntimeException("Stock insuffisant pour « {$produit->nom} ».");
                }

                $lignesPretees[] = [
                    'produit' => $produit,
                    'quantite' => $article['quantite'],
                ];

                $montantTotal += $produit->prixActuel() * $article['quantite'];
            }

            $montantAvantReduction = $montantTotal;

            if ($this->clientEstFidele($boutique, $infosClient['client_telephone'] ?? null)) {
                $reduction = $boutique->reduction_fidele;
                $montantTotal = (int) round($montantTotal * (1 - $reduction / 100));
            }

            $commande = Commande::create([
                'boutique_id' => $boutique->id,
                'user_id' => $infosClient['user_id'] ?? null,
                'reference_courte' => $this->genererReferenceCourte(),
                'client_nom' => $infosClient['client_nom'],
                'client_telephone' => $infosClient['client_telephone'],
                'client_localite' => $infosClient['client_localite'],
                'statut' => 'en_attente',
                'montant_produit' => $montantTotal,
            ]);

            foreach ($lignesPretees as $ligne) {
                $produit = $ligne['produit'];

                $commande->lignes()->create([
                    'produit_id' => $produit->id,
                    'nom_produit' => $produit->nom,
                    'prix_unitaire' => $produit->prixActuel(),
                    'quantite' => $ligne['quantite'],
                ]);

                $produit->decrement('stock_quantite', $ligne['quantite']);
            }

            return $commande;
        });
    }

    /**
     * Vérifie si un client est éligible à la réduction fidèle.
     * Compte les commandes payées (confirmées ou livrées) de ce téléphone dans cette boutique.
     */
    public function clientEstFidele(Boutique $boutique, ?string $clientTelephone): bool
    {
        if (! $clientTelephone || $boutique->seuil_fidele <= 0 || $boutique->reduction_fidele <= 0) {
            return false;
        }

        $nbCommandes = Commande::where('boutique_id', $boutique->id)
            ->where('client_telephone', $clientTelephone)
            ->whereIn('statut', ['confirmee', 'livree'])
            ->count();

        return $nbCommandes >= $boutique->seuil_fidele;
    }

    /**
     * Calcule la réduction fidèle applicable pour un client donné.
     * Retourne le montant de la réduction en FCFA, ou 0 si non éligible.
     */
    public function calculerReductionFidele(Boutique $boutique, ?string $clientTelephone, int $montantTotal): int
    {
        if (! $this->clientEstFidele($boutique, $clientTelephone)) {
            return 0;
        }

        return (int) round($montantTotal * $boutique->reduction_fidele / 100);
    }

    /**
     * Nombre de commandes payées d'un client dans une boutique.
     */
    public function nombreCommandesClient(Boutique $boutique, ?string $clientTelephone): int
    {
        if (! $clientTelephone) {
            return 0;
        }

        return Commande::where('boutique_id', $boutique->id)
            ->where('client_telephone', $clientTelephone)
            ->whereIn('statut', ['confirmee', 'livree'])
            ->count();
    }

    /**
     * Référence courte unique, facile à lire au téléphone : « VE-3K7P ».
     */
    public function genererReferenceCourte(): string
    {
        do {
            $reference = 'VE-'.strtoupper(Str::random(4));
        } while (Commande::where('reference_courte', $reference)->exists());

        return $reference;
    }

    /**
     * Transitions autorisées entre les statuts d'une commande.
     */
    private const TRANSITIONS = [
        'en_attente' => ['confirmee', 'annulee'],
        'confirmee' => ['livree', 'annulee', 'retiree'],
        'livree' => [],
        'annulee' => [],
        'retiree' => [],
    ];

    /**
     * Fait passer une commande à un nouveau statut, si le passage est autorisé.
     */
    public function changerStatut(Commande $commande, string $nouveauStatut): void
    {
        $transitionsAutorisees = self::TRANSITIONS[$commande->statut] ?? [];

        if (! in_array($nouveauStatut, $transitionsAutorisees, true)) {
            Log::warning('Changement de statut interdit pour la commande '.$commande->reference_courte, [
                'statut_actuel' => $commande->statut,
                'statut_demande' => $nouveauStatut,
            ]);
            throw new \RuntimeException('Cette action n\'est pas possible pour le statut actuel de la commande.');
        }

        try {
            if ($nouveauStatut === 'annulee') {
                $this->remettreStock($commande);
            }

            $commande->update(['statut' => $nouveauStatut]);
        } catch (\Throwable $e) {
            Log::error("Échec du changement de statut de la commande {$commande->reference_courte}", [
                'erreur' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    private function remettreStock(Commande $commande): void
    {
        foreach ($commande->lignes as $ligne) {
            Produit::where('id', $ligne->produit_id)
                ->increment('stock_quantite', $ligne->quantite);
        }
    }
}
