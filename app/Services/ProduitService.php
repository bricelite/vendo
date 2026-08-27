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
    public function creer(Boutique $boutique, array $donnees, ?UploadedFile $image, ?array $imagesSupplementaires = null): Produit
    {
        if ($image) {
            $donnees['image_url'] = $this->enregistrerImage($image);
        }

        if ($imagesSupplementaires) {
            $donnees['images_supplementaires'] = $this->enregistrerImages($imagesSupplementaires);
        }

        return $boutique->produits()->create($donnees);
    }

    /**
     * Modifie un produit. Une nouvelle photo remplace l'ancienne.
     */
    public function modifier(Produit $produit, array $donnees, ?UploadedFile $image, ?array $imagesSupplementaires = null): Produit
    {
        if ($image) {
            $this->supprimerImage($produit->image_url);
            $donnees['image_url'] = $this->enregistrerImage($image);
        }

        if ($imagesSupplementaires !== null) {
            // Supprimer les anciennes images supplémentaires
            if ($produit->images_supplementaires) {
                foreach ($produit->images_supplementaires as $ancienne) {
                    $this->supprimerImage($ancienne);
                }
            }
            $donnees['images_supplementaires'] = $this->enregistrerImages($imagesSupplementaires);
        }

        $produit->update($donnees);

        return $produit;
    }

    /**
     * Duplique un produit avec le suffixe " (copie)", stock à 0, non disponible.
     */
    public function dupliquer(Produit $source): Produit
    {
        $donnees = [
            'nom' => $source->nom.' (copie)',
            'description' => $source->description,
            'prix' => $source->prix,
            'prix_promo' => null,
            'stock_quantite' => 0,
            'categorie_id' => $source->categorie_id,
            'est_disponible' => false,
            'est_en_solde' => false,
            'alerte_stock_bas' => false,
        ];

        return $this->creer($source->boutique, $donnees, null);
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
     * Sauvegarde plusieurs photos et renvoie un tableau de noms de fichiers.
     */
    private function enregistrerImages(array $images): array
    {
        $noms = [];

        foreach ($images as $image) {
            if ($image instanceof UploadedFile) {
                $noms[] = $this->enregistrerImage($image);
            }
        }

        return $noms;
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

    /**
     * Conserve uniquement les images listées, supprime les autres du disque et met à jour le modèle.
     */
    public function conserverImages(Produit $produit, array $nomsGardes): void
    {
        if (! $produit->images_supplementaires) {
            return;
        }

        $aSupprimer = array_diff($produit->images_supplementaires, $nomsGardes);

        foreach ($aSupprimer as $nom) {
            $this->supprimerImage($nom);
        }

        $produit->update(['images_supplementaires' => array_values($nomsGardes)]);
    }
}
