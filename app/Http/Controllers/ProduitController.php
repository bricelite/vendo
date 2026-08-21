<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Services\ProduitService;
use Illuminate\Http\Request;

class ProduitController extends Controller
{
    /**
     * Liste les produits de la boutique du vendeur.
     */
    public function index(Request $request)
    {
        $boutique = $request->user()->boutique;

        if (! $boutique) {
            return redirect()->route('dashboard');
        }

        $produits = $boutique->produits()->latest()->get();

        return view('produits.index', compact('boutique', 'produits'));
    }

    /**
     * Formulaire d'ajout d'un produit.
     */
    public function creer(Request $request)
    {
        $boutique = $request->user()->boutique;

        if (! $boutique) {
            return redirect()->route('dashboard');
        }

        $boutique->load('categories');

        return view('produits.creer', compact('boutique'));
    }

    /**
     * Formulaire de modification d'un produit.
     */
    public function modifier(Request $request, Produit $produit)
    {
        $this->autoriserVendeur($request->user(), $produit);

        $boutique = $produit->boutique;
        $boutique->load('categories');

        return view('produits.modifier', compact('produit', 'boutique'));
    }

    /**
     * Ajoute un produit à la boutique du vendeur connecté.
     */
    public function store(Request $request, ProduitService $produitService)
    {
        $boutique = $request->user()->boutique;

        if (! $boutique) {
            return redirect()->route('dashboard');
        }

        $donnees = $request->validate($this->reglesValidation());

        // Cases à cocher : absentes de la requête quand décochées
        $donnees['est_disponible'] = $request->boolean('est_disponible');
        $donnees['est_en_solde'] = $request->boolean('est_en_solde');

        $produit = $produitService->creer($boutique, $donnees, $request->file('image'));

        return redirect()->route('produits.index')
            ->with('succes', 'Produit « '.$produit->nom.' » ajouté à votre boutique.');
    }

    /**
     * Enregistre les modifications d'un produit.
     */
    public function update(Request $request, Produit $produit, ProduitService $produitService)
    {
        $this->autoriserVendeur($request->user(), $produit);

        $donnees = $request->validate($this->reglesValidation());

        // Cases à cocher : absentes de la requête quand décochées
        $donnees['est_disponible'] = $request->boolean('est_disponible');
        $donnees['est_en_solde'] = $request->boolean('est_en_solde');

        $produitService->modifier($produit, $donnees, $request->file('image'));

        return redirect()->route('produits.index')
            ->with('succes', 'Produit « '.$produit->nom.' » modifié.');
    }

    /**
     * Supprime définitivement un produit (avec confirmation côté interface).
     */
    public function destroy(Request $request, Produit $produit, ProduitService $produitService)
    {
        $this->autoriserVendeur($request->user(), $produit);

        $produitService->supprimerImage($produit->image_url);
        $produit->delete();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Produit supprimé.']);
        }

        return redirect()->route('produits.index')->with('succes', 'Produit supprimé.');
    }

    /**
     * Met un produit en pause ou de nouveau en vente.
     */
    public function basculerDisponibilite(Request $request, Produit $produit)
    {
        $this->autoriserVendeur($request->user(), $produit);

        $produit->update(['est_disponible' => ! $produit->est_disponible]);

        $message = $produit->est_disponible
            ? 'Le produit est de nouveau en vente.'
            : 'Le produit est masqué de votre boutique.';

        if ($request->wantsJson()) {
            return response()->json([
                'message' => $message,
                'disponible' => $produit->est_disponible,
            ]);
        }

        return redirect()->back()->with('succes', $message);
    }

    private function reglesValidation(): array
    {
        return [
            'nom' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:2000'],
            'categorie_id' => ['nullable', 'integer', 'exists:categories,id'],
            'prix' => ['required', 'integer', 'min:1'],
            'prix_promo' => ['nullable', 'integer', 'min:1', 'lt:prix'],
            'stock_quantite' => ['required', 'integer', 'min:0'],
            'est_disponible' => ['nullable', 'boolean'],
            'est_en_solde' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:10240'],
        ];
    }

    private function autoriserVendeur($utilisateur, Produit $produit): void
    {
        abort_unless($utilisateur->id === $produit->boutique->user_id, 403);
    }
}
