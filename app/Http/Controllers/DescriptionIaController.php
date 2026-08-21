<?php

namespace App\Http\Controllers;

use App\Services\DescriptionIaService;
use Illuminate\Http\Request;

class DescriptionIaController extends Controller
{
    public function generer(Request $request, DescriptionIaService $service)
    {
        if (! $service->estConfiguré()) {
            return response()->json([
                'erreur' => 'Le service IA n\'est pas configuré. Demandez à l\'administrateur d\'ajouter la clé API.',
            ], 503);
        }

        $données = $request->validate([
            'nom_produit' => ['required', 'string', 'max:150'],
            'prix' => ['required', 'string', 'max:20'],
            'mode_langage' => ['required', 'string', 'in:standard,decontracte,beninois'],
        ]);

        try {
            $description = $service->genererDescription(
                $données['nom_produit'],
                $données['prix'],
                $données['mode_langage']
            );

            return response()->json(['description' => $description]);
        } catch (\RuntimeException $e) {
            return response()->json(['erreur' => $e->getMessage()], 422);
        }
    }

    public function suggérerCatégorie(Request $request, DescriptionIaService $service)
    {
        if (! $service->estConfiguré()) {
            return response()->json([
                'erreur' => 'Le service IA n\'est pas configuré.',
            ], 503);
        }

        $données = $request->validate([
            'nom_produit' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:500'],
            'categories' => ['required', 'array', 'min:1'],
            'categories.*.id' => ['required', 'integer'],
            'categories.*.nom' => ['required', 'string', 'max:100'],
        ]);

        $catégories = array_map(fn ($c) => ['id' => $c['id'], 'nom' => $c['nom']], $données['categories']);
        $id = $service->suggérerCatégorie($données['nom_produit'], $données['description'] ?? '', $catégories);

        return response()->json([
            'categorie_id' => $id,
        ]);
    }
}
