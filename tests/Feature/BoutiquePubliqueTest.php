<?php

namespace Tests\Feature;

use App\Models\Boutique;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BoutiquePubliqueTest extends TestCase
{
    use RefreshDatabase;

    private function creerBoutiqueAvecProduit(): array
    {
        $vendeur = User::factory()->create();
        $boutique = $vendeur->boutique()->create([
            'nom' => 'Boutique Test',
            'slug' => 'boutique-test',
        ]);
        $produit = $boutique->produits()->create([
            'nom' => 'Robe en pagne',
            'prix' => 15000,
            'prix_promo' => 12000,
            'stock_quantite' => 5,
        ]);

        return [$boutique, $produit];
    }

    public function test_la_vitrine_publique_se_affiche(): void
    {
        [$boutique] = $this->creerBoutiqueAvecProduit();

        $response = $this->get('/boutique/'.$boutique->slug);

        $response->assertOk()->assertSee('Robe en pagne');
    }

    public function test_la_fiche_produit_se_affiche_avec_le_prix_promo(): void
    {
        [$boutique, $produit] = $this->creerBoutiqueAvecProduit();

        $response = $this->get('/boutique/'.$boutique->slug.'/'.$produit->id);

        $response->assertOk()->assertSee('Commander');
    }

    public function test_le_panier_se_affiche(): void
    {
        [$boutique] = $this->creerBoutiqueAvecProduit();

        $this->get('/boutique/'.$boutique->slug.'/panier')
            ->assertOk()
            ->assertSee('Votre panier');
    }

    public function test_un_produit_dune_autre_boutique_nest_pas_accessible(): void
    {
        [, $produit] = $this->creerBoutiqueAvecProduit();
        $autreBoutique = Boutique::factory()->create();

        $this->get('/boutique/'.$autreBoutique->slug.'/'.$produit->id)
            ->assertNotFound();
    }

    public function test_un_client_peut_passer_une_commande(): void
    {
        [$boutique, $produit] = $this->creerBoutiqueAvecProduit();

        $response = $this->post('/boutique/'.$boutique->slug.'/commande', [
            'client_nom' => 'Kévin H.',
            'client_telephone' => '0196000000',
            'client_localite' => 'Fidjrossè',
            'articles' => [
                ['produit_id' => $produit->id, 'quantite' => 2],
            ],
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('commandes', [
            'boutique_id' => $boutique->id,
            'statut' => 'en_attente',
            'montant_produit' => 24000,
        ]);

        $this->assertDatabaseHas('produits', [
            'id' => $produit->id,
            'stock_quantite' => 3,
        ]);
    }

    public function test_une_commande_avec_stock_insuffisant_est_refusee(): void
    {
        [$boutique, $produit] = $this->creerBoutiqueAvecProduit();

        $this->post('/boutique/'.$boutique->slug.'/commande', [
            'client_nom' => 'Kévin H.',
            'client_telephone' => '0196000000',
            'articles' => [
                ['produit_id' => $produit->id, 'quantite' => 50],
            ],
        ])->assertSessionHasErrors();

        $this->assertDatabaseCount('commandes', 0);
        $this->assertDatabaseHas('produits', [
            'id' => $produit->id,
            'stock_quantite' => 5,
        ]);
    }
}
