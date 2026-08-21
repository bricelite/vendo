<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    /**
     * Sert une photo de produit stockée dans storage/app/uploads.
     * basename() empêche toute tentative de sortie du dossier (ex. « ../ »).
     */
    public function montrer(string $fichier)
    {
        $nom = basename($fichier);

        $chemin = Storage::disk('local')->path('uploads/'.$nom);

        if (! is_file($chemin)) {
            abort(404);
        }

        return response()->file($chemin);
    }
}
