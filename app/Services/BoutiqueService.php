<?php

namespace App\Services;

use App\Models\Boutique;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class BoutiqueService
{
    /**
     * Crée le compte vendeur complet : utilisateur + boutique + premier produit.
     * Les trois sont créés dans la même transaction : si une étape échoue, rien n'est créé.
     *
     * @param  array{name: string, telephone: string, email: string|null, password: string, boutique_nom: string, boutique_localisation: string|null, produit_nom: string, produit_prix: int, produit_stock: int}  $donnees
     */
    public function creerCompteVendeur(array $donnees): User
    {
        return DB::transaction(function () use ($donnees) {
            $utilisateur = User::create([
                'name' => $donnees['name'],
                'telephone' => $donnees['telephone'],
                'email' => $donnees['email'],
                'password' => $donnees['password'],
                'role' => 'vendeur',
            ]);

            $boutique = $this->creerBoutique(
                $utilisateur,
                $donnees['boutique_nom'],
                $donnees['boutique_localisation'] ?? null,
                $donnees['google_maps_url'] ?? null,
            );

            if (! empty($donnees['produit_nom'])) {
                $boutique->produits()->create([
                    'nom' => $donnees['produit_nom'],
                    'prix' => $donnees['produit_prix'] ?? 0,
                    'stock_quantite' => $donnees['produit_stock'] ?? 0,
                ]);
            }

            return $utilisateur;
        });
    }

    /**
     * Crée la boutique du vendeur avec un slug unique et lisible.
     */
    public function creerBoutique(User $vendeur, string $nom, ?string $localisation = null, ?string $googleMapsUrl = null): Boutique
    {
        $baseSlug = Str::slug($nom);
        $slug = $baseSlug;
        $compteur = 1;

        while (Boutique::where('slug', $slug)->exists()) {
            $slug = $baseSlug.'-'.$compteur;
            $compteur++;
        }

        try {
            return $vendeur->boutique()->create([
                'nom' => $nom,
                'slug' => $slug,
                'localisation' => $localisation,
                'google_maps_url' => $googleMapsUrl,
            ]);
        } catch (\Throwable $e) {
            Log::error("Échec de la création de la boutique pour le vendeur {$vendeur->id}", [
                'erreur' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
