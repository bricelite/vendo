<?php

namespace App\Http\Controllers;

use App\Models\Boutique;
use App\Models\Commande;
use App\Rules\TelephoneBenin;
use App\Services\CommandeService;
use Illuminate\Http\Request;

class CommandeController extends Controller
{
    public function __construct(
        protected CommandeService $commandeService,
    ) {}

    /**
     * Enregistre la commande du client sur la boutique.
     */
    public function creer(Request $request, Boutique $boutique)
    {
        // Le panier arrive en JSON depuis le navigateur du client
        if (is_string($request->input('articles'))) {
            $request->merge(['articles' => json_decode($request->input('articles'), true)]);
        }

        $donnees = $request->validate([
            'client_nom' => ['required', 'string', 'max:100'],
            'client_telephone' => ['required', 'string', 'max:30', new TelephoneBenin()],
            'client_localite' => ['nullable', 'string', 'max:100'],
            'mode_retrait' => ['required', 'string', 'in:livraison,retrait'],
            'mode_paiement' => ['nullable', 'string', 'in:mobile_money,livraison'],
            'articles' => ['required', 'array', 'min:1'],
            'articles.*.produit_id' => ['required', 'integer'],
            'articles.*.quantite' => ['required', 'integer', 'min:1'],
        ]);

        try {
            $commande = $this->commandeService->creerCommande(
                $boutique,
                $donnees['articles'],
                [
                    'user_id' => $request->input('user_id'),
                    'client_nom' => $donnees['client_nom'],
                    'client_telephone' => $donnees['client_telephone'],
                    'client_localite' => $donnees['client_localite'] ?? null,
                    'mode_retrait' => $donnees['mode_retrait'],
                    'mode_paiement' => $donnees['mode_paiement'] ?? null,
                ],
            );
        } catch (\RuntimeException $e) {
            return back()->withErrors(['articles' => $e->getMessage()])->withInput();
        }

        return redirect()->route('commande.confirmation', $commande);
    }

    /**
     * Page de confirmation envoyée au client après sa commande.
     */
    public function confirmation(Commande $commande)
    {
        $commande->load('lignes.produit.avis');

        $produitsAvis = $commande->lignes->map(fn ($ligne) => [
            'produit' => $ligne->produit,
            'dejaNote' => $ligne->produit->avis->contains('commande_id', $commande->id),
        ]);

        return view('commande.confirmation', compact('commande', 'produitsAvis'));
    }

    /**
     * Liste des commandes de la boutique du vendeur.
     */
    public function index(Request $request)
    {
        $boutique = $request->user()->boutique;

        if (! $boutique) {
            return redirect()->route('dashboard');
        }

        $statut = $request->get('statut');

        $commandes = $boutique->commandes()
            ->when($statut, fn ($requete) => $requete->where('statut', $statut))
            ->latest()
            ->get();

        return view('commandes.index', compact('boutique', 'commandes', 'statut'));
    }

    /**
     * Détail d'une commande : client, articles, total et actions possibles.
     */
    public function montrer(Request $request, Commande $commande)
    {
        $this->autoriserVendeur($request->user(), $commande);

        $commande->load('lignes');

        return view('commandes.montrer', compact('commande'));
    }

    /**
     * Change le statut d'une commande (confirmer, livrer, annuler).
     */
    public function changerStatut(Request $request, Commande $commande)
    {
        $this->autoriserVendeur($request->user(), $commande);

        $donnees = $request->validate([
            'statut' => ['required', 'in:confirmee,livree,annulee,retiree'],
        ]);

        try {
            $this->commandeService->changerStatut($commande, $donnees['statut']);
        } catch (\RuntimeException $e) {
            if ($request->wantsJson()) {
                return response()->json(['message' => $e->getMessage()], 422);
            }

            return back()->with('erreur', $e->getMessage());
        }

        $message = match ($donnees['statut']) {
            'confirmee' => 'Commande confirmée.',
            'livree' => 'Commande marquée comme livrée.',
            'annulee' => 'Commande annulée. Le stock a été remis en place.',
        };

        if ($request->wantsJson()) {
            return response()->json([
                'message' => $message,
                'statut' => $commande->fresh()->statut,
            ]);
        }

        return redirect()->back()->with('succes', $message);
    }

    /**
     * Valide le retrait d'une commande en boutique avec le code à 6 chiffres.
     */
    public function validerRetrait(Request $request, Commande $commande)
    {
        $this->autoriserVendeur($request->user(), $commande);

        $donnees = $request->validate([
            'code_retrait' => ['required', 'string', 'size:6'],
        ]);

        if ($commande->code_retrait !== $donnees['code_retrait']) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Code incorrect.'], 422);
            }

            return back()->with('erreur', 'Code incorrect.');
        }

        $commande->update([
            'statut_retrait' => 'retire',
            'statut' => 'retiree',
        ]);

        $message = 'Retrait validé. La commande est terminée.';

        if ($request->wantsJson()) {
            return response()->json([
                'message' => $message,
                'statut' => $commande->fresh()->statut,
                'statut_retrait' => $commande->fresh()->statut_retrait,
            ]);
        }

        return redirect()->back()->with('succes', $message);
    }

    private function autoriserVendeur($utilisateur, Commande $commande): void
    {
        abort_unless($utilisateur->id === $commande->boutique->user_id, 403);
    }
}
