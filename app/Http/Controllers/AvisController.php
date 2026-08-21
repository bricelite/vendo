<?php

namespace App\Http\Controllers;

use App\Models\Avis;
use App\Models\Commande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AvisController extends Controller
{
    /**
     * Affiche les avis laissés par les clients sur les produits de la boutique.
     */
    public function index(Request $request)
    {
        $boutique = $request->user()->boutique;

        if (! $boutique) {
            return redirect()->route('dashboard');
        }

        $avis = Avis::whereHas('produit', fn ($requete) => $requete->where('boutique_id', $boutique->id))
            ->with('produit')
            ->latest()
            ->get();

        $noteMoyenne = $avis->isEmpty() ? null : $avis->avg('note');

        return view('avis.index', compact('boutique', 'avis', 'noteMoyenne'));
    }

    /**
     * Enregistre un avis laissé par un client après sa commande.
     * Un client ne peut laisser qu'un seul avis par produit par commande.
     */
    public function store(Request $request)
    {
        $donnees = $request->validate([
            'commande_id' => ['required', 'integer', 'exists:commandes,id'],
            'produit_id' => ['required', 'integer', 'exists:produits,id'],
            'client_nom' => ['required', 'string', 'max:100'],
            'note' => ['required', 'integer', 'min:1', 'max:5'],
            'commentaire' => ['nullable', 'string', 'max:500'],
        ]);

        $commande = Commande::findOrFail($donnees['commande_id']);

        $dejaNote = Avis::where('commande_id', $commande->id)
            ->where('produit_id', $donnees['produit_id'])
            ->exists();

        if ($dejaNote) {
            return back()->with('erreur', 'Vous avez déjà laissé un avis pour ce produit.');
        }

        try {
            Avis::create([
                'commande_id' => $commande->id,
                'produit_id' => $donnees['produit_id'],
                'client_nom' => $donnees['client_nom'],
                'note' => $donnees['note'],
                'commentaire' => $donnees['commentaire'] ?? null,
            ]);
        } catch (\Throwable $e) {
            Log::error("Échec de la création d'un avis pour la commande {$commande->reference_courte}", [
                'erreur' => $e->getMessage(),
            ]);
            throw $e;
        }

        return redirect()->route('commande.confirmation', $commande)
            ->with('succes_avis', 'Merci pour votre avis !');
    }
}
