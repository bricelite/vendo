<?php

namespace App\Http\Controllers;

use App\Models\Boutique;
use App\Models\Commande;
use App\Models\Produit;
use App\Services\CommandeService;
use App\Services\SuggestionService;
use Illuminate\Http\Request;

class BoutiquePubliqueController extends Controller
{
    public function __construct(
        protected SuggestionService $suggestionService,
        protected CommandeService $commandeService,
    ) {}

    /**
     * Vitrine publique de la boutique, avec filtres catégorie, tri,
     * produits en solde, suggestions et info fidélité.
     */
    public function show(Boutique $boutique)
    {
        $query = $boutique->produits()->where('est_disponible', true)->with('categorie');

        $categorieSlug = request('categorie');
        $tri = request('tri', 'recent');

        if ($categorieSlug) {
            $query->whereHas('categorie', fn ($q) => $q->where('slug', $categorieSlug));
        }

        $query = match ($tri) {
            'prix_croissant' => $query->orderBy('prix'),
            'prix_decroissant' => $query->orderByDesc('prix'),
            default => $query->latest(),
        };

        $produits = $query->withCount('avis')->withAvg('avis', 'note')->get();

        $categories = $boutique->categories()
            ->withCount(['produits' => fn ($q) => $q->where('est_disponible', true)])
            ->get()
            ->filter(fn ($cat) => $cat->produits_count > 0);

        $produitsParCategorie = $produits->groupBy(fn ($p) => $p->categorie ? $p->categorie->nom : 'Autres produits');

        $produitsEnSolde = $boutique->produits()
            ->where('est_disponible', true)
            ->where('est_en_solde', true)
            ->withCount('avis')->withAvg('avis', 'note')
            ->with('categorie')
            ->get();

        $clientTelephone = request('client_telephone');
        $suggestions = $this->suggestionService->suggerer($boutique, $clientTelephone, 6);

        if ($suggestions->isEmpty()) {
            $suggestions = $this->suggestionService->populaires($boutique, 6);
        }

        $nbCommandesClient = $this->commandeService->nombreCommandesClient($boutique, $clientTelephone);
        $clientEstFidele = $this->commandeService->clientEstFidele($boutique, $clientTelephone);

        $noteMoyenneBoutique = $boutique->produits()->where('est_disponible', true)
            ->join('avis', 'produits.id', '=', 'avis.produit_id')
            ->avg('avis.note');
        $nombreAvisBoutique = $boutique->produits()->where('est_disponible', true)
            ->join('avis', 'produits.id', '=', 'avis.produit_id')
            ->count('avis.id');

        return view('boutique-publique.accueil', compact(
            'boutique', 'produits', 'categories', 'categorieSlug', 'tri',
            'produitsParCategorie', 'produitsEnSolde', 'suggestions',
            'nbCommandesClient', 'clientEstFidele',
            'noteMoyenneBoutique', 'nombreAvisBoutique',
        ));
    }

    /**
     * Fiche produit publique, avec le bouton « Commander » toujours visible.
     */
    public function ficheProduit(Boutique $boutique, Produit $produit)
    {
        if ($produit->boutique_id !== $boutique->id) {
            abort(404);
        }

        $produit->load('avis');
        $noteMoyenne = $produit->avis->avg('note');
        $nombreAvis = $produit->avis->count();

        $produitsSimilaires = $boutique->produits()
            ->where('est_disponible', true)
            ->where('id', '!=', $produit->id)
            ->when($produit->categorie_id, fn ($q) => $q->where('categorie_id', $produit->categorie_id))
            ->with('categorie')
            ->limit(4)
            ->get();

        return view('boutique-publique.fiche-produit', compact(
            'boutique', 'produit', 'noteMoyenne', 'nombreAvis', 'produitsSimilaires',
        ));
    }

    /**
     * Panier du client pour cette boutique.
     */
    public function panier(Boutique $boutique)
    {
        $clientTelephone = request('client_telephone');
        $nbCommandesClient = $this->commandeService->nombreCommandesClient($boutique, $clientTelephone);
        $clientEstFidele = $this->commandeService->clientEstFidele($boutique, $clientTelephone);

        return view('boutique-publique.panier', compact('boutique', 'nbCommandesClient', 'clientEstFidele'));
    }
}
