<?php

namespace App\Http\Controllers;

use App\Services\ProduitService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class BoutiqueController extends Controller
{
    /**
     * Modifie les informations de la boutique du vendeur connecté.
     * Le lien public (slug) ne change pas pour ne pas casser les partages.
     */
    public function update(Request $request, ProduitService $produitService)
    {
        $boutique = $request->user()->boutique;

        if (! $boutique) {
            return redirect()->route('dashboard');
        }

        $donnees = $request->validate([
            'nom' => ['required', 'string', 'max:100'],
            'localisation' => ['nullable', 'string', 'max:200'],
            'google_maps_url' => ['nullable', 'url', 'max:500'],
            'description' => ['nullable', 'string', 'max:500'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:2048'],
            'couverture' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:4096'],
            'seuil_fidele' => ['nullable', 'integer', 'min:0', 'max:100'],
            'reduction_fidele' => ['nullable', 'integer', 'min:0', 'max:100'],
            'numero_mobile_money' => ['nullable', 'string', 'max:30'],
            'operateur_mobile_money' => ['nullable', 'in:mtn,moov'],
            'duree_reservation_defaut_minutes' => ['nullable', 'integer', 'in:60,120,360,720,1440'],
        ]);

        if ($request->hasFile('logo')) {
            $this->supprimerAncienneImage($boutique->logo_url, $boutique->id, 'logo');
            $donnees['logo_url'] = $this->enregistrerImage($request->file('logo'));
        }

        if ($request->hasFile('couverture')) {
            $this->supprimerAncienneImage($boutique->couverture_url, $boutique->id, 'couverture');
            $donnees['couverture_url'] = $this->enregistrerImage($request->file('couverture'));
        }

        $boutique->update($donnees);

        return redirect()->route('profile.edit')
            ->with('succes', 'Votre boutique a été mise à jour.');
    }

    private function enregistrerImage($fichier): string
    {
        $nom = Str::random(20).'.'.$fichier->getClientOriginalExtension();
        $fichier->move(public_path('uploads'), $nom);

        return $nom;
    }

    private function supprimerAncienneImage(?string $nom, int $boutiqueId, string $type): void
    {
        if (! $nom) {
            return;
        }

        try {
            $chemin = public_path('uploads/'.$nom);
            if (is_file($chemin)) {
                unlink($chemin);
            }
        } catch (\Throwable $e) {
            Log::warning("Impossible de supprimer l'ancienne {$type} de la boutique {$boutiqueId}", [
                'erreur' => $e->getMessage(),
            ]);
        }
    }
}
