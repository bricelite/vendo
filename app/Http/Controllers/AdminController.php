<?php

namespace App\Http\Controllers;

use App\Models\Boutique;
use App\Models\Commande;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Vue d'ensemble pour l'administrateur Vendo.
     */
    public function index(Request $request)
    {
        $this->autoriserAdmin($request->user());

        $boutiques = Boutique::with('vendeur')->withCount('produits', 'commandes')->get();
        $commandesEnAttente = Commande::where('statut', 'en_attente')->count();

        return view('admin.dashboard', compact('boutiques', 'commandesEnAttente'));
    }

    private function autoriserAdmin($utilisateur): void
    {
        abort_unless($utilisateur->estAdmin(), 403);
    }
}
