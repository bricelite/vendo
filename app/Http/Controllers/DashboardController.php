<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
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

        // Tendance 7 jours : CA par jour pour les 7 derniers jours
        $tendance7Jours = (clone $commandesPayees)
            ->where('created_at', '>=', Carbon::now()->subDays(6)->startOfDay())
            ->selectRaw('DATE(created_at) as jour, SUM(montant_produit) as total')
            ->groupBy('jour')
            ->pluck('total', 'jour')
            ->toArray();

        $jours7 = [];
        for ($i = 6; $i >= 0; $i--) {
            $cle = Carbon::now()->subDays($i)->format('Y-m-d');
            $jours7[$cle] = $tendance7Jours[$cle] ?? 0;
        }

        return view('dashboard', compact(
            'boutique',
            'produits',
            'commandes',
            'gainsTotaux',
            'nbCommandesPayees',
            'meilleureCommande',
            'jours7',
        ));
    }
}
