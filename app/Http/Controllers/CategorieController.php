<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategorieController extends Controller
{
    /**
     * Liste les catégories de la boutique du vendeur.
     */
    public function index(Request $request)
    {
        $boutique = $request->user()->boutique;

        if (! $boutique) {
            return redirect()->route('dashboard');
        }

        $categories = $boutique->categories()->withCount('produits')->get();

        return view('categories.index', compact('boutique', 'categories'));
    }

    /**
     * Ajoute une catégorie à la boutique.
     */
    public function store(Request $request)
    {
        $boutique = $request->user()->boutique;

        if (! $boutique) {
            return redirect()->route('dashboard');
        }

        $donnees = $request->validate([
            'nom' => ['required', 'string', 'max:100'],
        ]);

        $slug = Str::slug($donnees['nom']);
        $compteur = 1;
        $slugFinal = $slug;

        while (Categorie::where('boutique_id', $boutique->id)->where('slug', $slugFinal)->exists()) {
            $slugFinal = $slug.'-'.$compteur;
            $compteur++;
        }

        $dernierOrdre = $boutique->categories()->max('ordre') ?? 0;

        Categorie::create([
            'boutique_id' => $boutique->id,
            'nom' => $donnees['nom'],
            'slug' => $slugFinal,
            'ordre' => $dernierOrdre + 1,
        ]);

        return redirect()->route('categories.index')
            ->with('succes', 'Catégorie « '.$donnees['nom'].' » ajoutée.');
    }

    /**
     * Renomme une catégorie.
     */
    public function update(Request $request, Categorie $categorie)
    {
        abort_unless($categorie->boutique_id === $request->user()->boutique->id, 403);

        $donnees = $request->validate([
            'nom' => ['required', 'string', 'max:100'],
        ]);

        $categorie->update(['nom' => $donnees['nom']]);

        return redirect()->route('categories.index')
            ->with('succes', 'Catégorie mise à jour.');
    }

    /**
     * Supprime une catégorie (les produits ne sont pas supprimés, juste dissociés).
     */
    public function destroy(Request $request, Categorie $categorie)
    {
        abort_unless($categorie->boutique_id === $request->user()->boutique->id, 403);

        $categorie->produits()->update(['categorie_id' => null]);
        $categorie->delete();

        return redirect()->route('categories.index')
            ->with('succes', 'Catégorie supprimée.');
    }
}
