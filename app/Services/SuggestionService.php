<?php

namespace App\Services;

use App\Models\Boutique;
use App\Models\Commande;
use App\Models\Produit;
use Illuminate\Support\Collection;

class SuggestionService
{
    /**
     * Suggère des produits à un client en fonction de son historique d'achat
     * dans cette boutique, de la popularité des produits, des notes et du prix.
     *
     * @return Collection<int, array{produit: Produit, score: float}>
     */
    public function suggerer(Boutique $boutique, ?string $clientTelephone, int $limite = 6): Collection
    {
        $produitsDisponibles = $boutique->produits()
            ->where('est_disponible', true)
            ->with('categorie', 'avis')
            ->withCount('avis')
            ->withAvg('avis', 'note')
            ->get();

        if ($produitsDisponibles->isEmpty()) {
            return collect();
        }

        $profilClient = $this->construireProfilClient($boutique, $clientTelephone);

        $produitsScores = $produitsDisponibles->map(function ($produit) use ($profilClient) {
            $score = $this->calculerScore($produit, $profilClient);

            return [
                'produit' => $produit,
                'score' => $score,
            ];
        });

        return $produitsScores
            ->sortByDesc('score')
            ->take($limite)
            ->values();
    }

    /**
     * Produits les plus populaires (pas d'historique client).
     */
    public function populaires(Boutique $boutique, int $limite = 6): Collection
    {
        $produitsDisponibles = $boutique->produits()
            ->where('est_disponible', true)
            ->with('categorie', 'avis')
            ->withCount('avis')
            ->withAvg('avis', 'note')
            ->get();

        if ($produitsDisponibles->isEmpty()) {
            return collect();
        }

        $popularite = $this->calculerPopularite($boutique);

        $produitsScores = $produitsDisponibles->map(function ($produit) use ($popularite) {
            $nbVentes = $popularite[$produit->id] ?? 0;
            $noteMoyenne = $produit->avis->avg('note') ?? 0;
            $score = ($nbVentes * 3) + ($noteMoyenne * 5);

            return [
                'produit' => $produit,
                'score' => $score,
            ];
        });

        return $produitsScores
            ->sortByDesc('score')
            ->take($limite)
            ->values();
    }

    /**
     * Construit le profil d'achat d'un client pour cette boutique.
     */
    private function construireProfilClient(Boutique $boutique, ?string $clientTelephone): array
    {
        if (! $clientTelephone) {
            return [
                'categoriesAchetees' => collect(),
                'panierMoyen' => 0,
                'nbAchats' => 0,
            ];
        }

        $commandes = Commande::where('boutique_id', $boutique->id)
            ->where('client_telephone', $clientTelephone)
            ->whereIn('statut', ['confirmee', 'livree'])
            ->with('lignes.produit')
            ->get();

        if ($commandes->isEmpty()) {
            return [
                'categoriesAchetees' => collect(),
                'panierMoyen' => 0,
                'nbAchats' => 0,
            ];
        }

        $produitsAchetes = $commandes->flatMap->lignes->pluck('produit');

        $categoriesAchetees = $produitsAchetes
            ->filter(fn ($p) => $p->categorie_id)
            ->groupBy('categorie_id')
            ->map(fn ($groupe) => $groupe->sum(fn ($p) => $p->pivot->quantite ?? 1));

        $panierMoyen = $commandes->avg('montant_produit');

        return [
            'categoriesAchetees' => $categoriesAchetees,
            'panierMoyen' => (int) $panierMoyen,
            'nbAchats' => $commandes->count(),
        ];
    }

    /**
     * Score un produit par rapport au profil client (0 à 100).
     */
    private function calculerScore(Produit $produit, array $profilClient): float
    {
        $score = 0.0;

        // 1. Affinité catégorie (40 points max)
        if ($produit->categorie_id && $profilClient['categoriesAchetees']->has($produit->categorie_id)) {
            $totalAchetes = $profilClient['categoriesAchetees']->sum();
            $dansCategorie = $profilClient['categoriesAchetees']->get($produit->categorie_id);
            $score += ($dansCategorie / max($totalAchetes, 1)) * 40;
        }

        // 2. Popularité (25 points max)
        $nbVentesRecentes = Commande::where('boutique_id', $produit->boutique_id)
            ->whereIn('statut', ['confirmee', 'livree'])
            ->where('created_at', '>=', now()->subDays(30))
            ->whereHas('lignes', fn ($q) => $q->where('produit_id', $produit->id))
            ->count();
        $score += min($nbVentesRecentes * 5, 25);

        // 3. Note moyenne (20 points max)
        $noteMoyenne = $produit->avis->avg('note') ?? 0;
        $score += ($noteMoyenne / 5) * 20;

        // 4. Proximité de prix (15 points max)
        if ($profilClient['panierMoyen'] > 0 && $produit->prixActuel() > 0) {
            $ecart = abs($produit->prixActuel() - $profilClient['panierMoyen']) / max($produit->prixActuel(), $profilClient['panierMoyen']);
            $score += max(0, 15 * (1 - $ecart));
        }

        return round($score, 1);
    }

    /**
     * Nombre de ventes récentes par produit (30 derniers jours).
     * @return array<int, int>  produit_id => nombre de ventes
     */
    private function calculerPopularite(Boutique $boutique): array
    {
        return Commande::where('boutique_id', $boutique->id)
            ->whereIn('statut', ['confirmee', 'livree'])
            ->where('created_at', '>=', now()->subDays(30))
            ->with('lignes')
            ->get()
            ->flatMap->lignes
            ->groupBy('produit_id')
            ->map(fn ($groupe) => $groupe->sum('quantite'))
            ->toArray();
    }
}
