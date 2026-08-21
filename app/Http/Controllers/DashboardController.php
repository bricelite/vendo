<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Tableau de bord du vendeur : ses ventes, ses commandes et ses produits.
     */
    public function index(Request $request)
    {
        $boutique = $request->user()->boutique;

        if (! $boutique) {
            return view('dashboard', ['boutique' => null]);
        }

        $produits = $boutique->produits()->latest()->get();
        $commandes = $boutique->commandes()->with('lignes')->latest()->limit(10)->get();

        $commandesPayees = $boutique->commandes()
            ->whereIn('statut', ['confirmee', 'livree']);

        $gainsTotaux = (clone $commandesPayees)->sum('montant_produit');
        $nbCommandesPayees = (clone $commandesPayees)->count();
        $meilleureCommande = (clone $commandesPayees)->max('montant_produit');

        return view('dashboard', compact(
            'boutique',
            'produits',
            'commandes',
            'gainsTotaux',
            'nbCommandesPayees',
            'meilleureCommande',
        ));
    }
}
