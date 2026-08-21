<?php

namespace Database\Seeders;

use App\Models\Avis;
use App\Models\Boutique;
use App\Models\Commande;
use App\Models\Produit;
use App\Models\User;
use App\Services\BoutiqueService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->creerAdmin();
        $this->creerBoutiqueDemo();
    }

    private function creerAdmin(): void
    {
        User::create([
            'name' => 'Admin Vendo',
            'telephone' => '0190000001',
            'email' => 'admin@vendo.bj',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);
    }

    /**
     * La boutique d'Aïcha, le persona de référence du projet.
     */
    private function creerBoutiqueDemo(): void
    {
        $aicha = User::create([
            'name' => 'Aïcha Dossou',
            'telephone' => '0197000000',
            'email' => 'aicha@vendo.bj',
            'password' => Hash::make('password'),
            'role' => 'vendeur',
        ]);

        $boutiqueService = app(BoutiqueService::class);
        $boutique = $boutiqueService->creerBoutique($aicha, 'Boutique Aïcha');
        $boutique->update([
            'description' => 'Vêtements et accessoires en pagne, faits avec amour à Cotonou.',
        ]);

        $robe = $this->creerProduit($boutique, 'Robe en pagne', 15000, 8, 12000);
        $boubou = $this->creerProduit($boutique, 'Boubou brodé', 25000, 3);
        $sac = $this->creerProduit($boutique, 'Sac à main en tissu', 8000, 0);
        $turban = $this->creerProduit($boutique, 'Turban assorti', 5000, 4);

        $commande = Commande::create([
            'boutique_id' => $boutique->id,
            'reference_courte' => 'VE-7A1Q',
            'client_nom' => 'Kévin H.',
            'client_telephone' => '0196000000',
            'client_localite' => 'Fidjrossè, Cotonou',
            'statut' => 'confirmee',
            'montant_produit' => 15000,
        ]);

        $commande->lignes()->create([
            'produit_id' => $robe->id,
            'nom_produit' => $robe->nom,
            'prix_unitaire' => $robe->prixActuel(),
            'quantite' => 1,
        ]);

        $commandeEnAttente = Commande::create([
            'boutique_id' => $boutique->id,
            'reference_courte' => 'VE-2M5T',
            'client_nom' => 'Marie C.',
            'client_telephone' => '0195000000',
            'client_localite' => 'Cadjèhoun, Cotonou',
            'statut' => 'en_attente',
            'montant_produit' => 25000,
        ]);

        $commandeEnAttente->lignes()->create([
            'produit_id' => $boubou->id,
            'nom_produit' => $boubou->nom,
            'prix_unitaire' => $boubou->prixActuel(),
            'quantite' => 1,
        ]);

        Avis::create([
            'produit_id' => $robe->id,
            'client_nom' => 'Kévin H.',
            'note' => 5,
            'commentaire' => 'Très belle qualité, livraison rapide.',
        ]);

        Avis::create([
            'produit_id' => $turban->id,
            'client_nom' => 'Marie C.',
            'note' => 4,
            'commentaire' => 'Jolie couleur, taille parfaite.',
        ]);
    }

    private function creerProduit(Boutique $boutique, string $nom, int $prix, int $stock, ?int $prixPromo = null): Produit
    {
        return $boutique->produits()->create([
            'nom' => $nom,
            'description' => "Description courte du produit {$nom}.",
            'prix' => $prix,
            'prix_promo' => $prixPromo,
            'stock_quantite' => $stock,
        ]);
    }
}
