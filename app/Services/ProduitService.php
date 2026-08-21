<?php

namespace App\Services;

use App\Models\Boutique;
use App\Models\Produit;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProduitService
{
    private const DOSSIER_IMAGES = 'uploads';

    /**
     * Crée un produit dans la boutique, avec sa photo éventuelle.
     * La photo est déjà compressée côté client (voir AGENTS §2.6) avant l'envoi.
     */
    public function creer(Boutique $boutique, array $donnees, ?UploadedFile $image): Produit
    {
        if ($image) {
            $donnees['image_url'] = $this->enregistrerImage($image);
        }

        return $boutique->produits()->create($donnees);
    }

    /**
     * Modifie un produit. Une nouvelle photo remplace l'ancienne.
     */
    public function modifier(Produit $produit, array $donnees, ?UploadedFile $image): Produit
    {
        if ($image) {
            $this->supprimerImage($produit->image_url);
            $donnees['image_url'] = $this->enregistrerImage($image);
        }

        $produit->update($donnees);

        return $produit;
    }

    /**
     * Sauvegarde la photo dans public/uploads/ et renvoie son nom de fichier.
     */
    private function enregistrerImage(UploadedFile $image): string
    {
        try {
            $nom = Str::random(20).'.'.$image->getClientOriginalExtension();
            $image->move(public_path(self::DOSSIER_IMAGES), $nom);

            return $nom;
        } catch (\Throwable $e) {
            Log::error("Échec de la sauvegarde d'une photo produit", [
                'erreur' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Supprime l'image d'un produit devenue inutile (remplacement ou suppression du produit).
     * Une suppression qui échoue est journalisée mais ne fait pas échouer l'action principale.
     */
    public function supprimerImage(?string $nom): void
    {
        if (! $nom) {
            return;
        }

        try {
            $chemin = public_path(self::DOSSIER_IMAGES.'/'.$nom);
            if (is_file($chemin)) {
                unlink($chemin);
            }
        } catch (\Throwable $e) {
            Log::warning("Impossible de supprimer l'image d'un produit", [
                'image' => $nom,
                'erreur' => $e->getMessage(),
            ]);
        }
    }
}
