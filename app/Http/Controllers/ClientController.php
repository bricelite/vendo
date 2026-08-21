<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Historique des commandes du client connecté.
     */
    public function historique(Request $request)
    {
        $utilisateur = $request->user();

        if (! $utilisateur || $utilisateur->role !== 'client') {
            return redirect()->route('login');
        }

        $commandes = $utilisateur->commandes()
            ->with('boutique', 'lignes')
            ->latest()
            ->get();

        return view('client.historique', compact('commandes'));
    }
}
