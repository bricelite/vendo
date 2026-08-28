<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AvisController;
use App\Http\Controllers\BoutiqueController;
use App\Http\Controllers\BoutiquePubliqueController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DescriptionIaController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClientAuthController;
use App\Http\Controllers\ClientController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Boutique publique (côté client, sans authentification)
Route::get('/boutique/{boutique:slug}', [BoutiquePubliqueController::class, 'show'])->name('boutique-publique.accueil');
Route::get('/boutique/{boutique:slug}/panier', [BoutiquePubliqueController::class, 'panier'])->name('panier');
Route::get('/boutique/{boutique:slug}/favoris', [BoutiquePubliqueController::class, 'favoris'])->name('boutique-publique.favoris');
Route::get('/boutique/{boutique:slug}/{produit}', [BoutiquePubliqueController::class, 'ficheProduit'])->name('boutique-publique.fiche-produit');

// Commande côté client
Route::post('/boutique/{boutique:slug}/commande', [CommandeController::class, 'creer'])->name('commande.creer');
Route::get('/commande/{commande}', [CommandeController::class, 'confirmation'])->name('commande.confirmation');

// Avis client (public, après commande)
Route::post('/avis', [AvisController::class, 'store'])->name('avis.store');

// Compte client
Route::middleware('guest')->group(function () {
    Route::get('/client/connexion', [ClientAuthController::class, 'showLogin'])->name('client.login');
    Route::post('/client/connexion', [ClientAuthController::class, 'login'])->name('client.login.post');
    Route::get('/client/inscription', [ClientAuthController::class, 'showRegister'])->name('client.register');
    Route::post('/client/inscription', [ClientAuthController::class, 'register'])->name('client.register.post');
});

Route::middleware('auth')->group(function () {
    Route::post('/client/deconnexion', [ClientAuthController::class, 'logout'])->name('client.logout');
    Route::get('/client/historique', [ClientController::class, 'historique'])->name('client.historique');
});

// Espace vendeur connecté
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Produits
    Route::get('/produits', [ProduitController::class, 'index'])->name('produits.index');
    Route::get('/produits/creer', [ProduitController::class, 'creer'])->name('produits.creer');
    Route::get('/produits/{produit}/modifier', [ProduitController::class, 'modifier'])->name('produits.modifier');
    Route::post('/produits', [ProduitController::class, 'store'])->name('produits.store');
    Route::patch('/produits/{produit}', [ProduitController::class, 'update'])->name('produits.update');
    Route::patch('/produits/{produit}/disponibilite', [ProduitController::class, 'basculerDisponibilite'])->name('produits.disponibilite');
    Route::post('/produits/{produit}/dupliquer', [ProduitController::class, 'dupliquer'])->name('produits.dupliquer');
    Route::delete('/produits/{produit}', [ProduitController::class, 'destroy'])->name('produits.destroy');

    // Catégories
    Route::get('/categories', [\App\Http\Controllers\CategorieController::class, 'index'])->name('categories.index');
    Route::post('/categories', [\App\Http\Controllers\CategorieController::class, 'store'])->name('categories.store');
    Route::patch('/categories/{categorie}', [\App\Http\Controllers\CategorieController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{categorie}', [\App\Http\Controllers\CategorieController::class, 'destroy'])->name('categories.destroy');

    // Commandes
    Route::get('/commandes', [CommandeController::class, 'index'])->name('commandes.index');
    Route::get('/commandes/{commande}', [CommandeController::class, 'montrer'])->name('commandes.montrer');
    Route::patch('/commandes/{commande}/statut', [CommandeController::class, 'changerStatut'])->name('commandes.statut');
    Route::post('/commandes/{commande}/retrait', [CommandeController::class, 'validerRetrait'])->name('commandes.retrait');

    // Avis
    Route::get('/avis', [AvisController::class, 'index'])->name('avis.index');

    // Ma boutique
    Route::patch('/ma-boutique', [BoutiqueController::class, 'update'])->name('boutique.update');

    // IA descriptions
    Route::post('/description-ia', [DescriptionIaController::class, 'generer'])->name('description-ia.generer');
    Route::post('/description-ia/categorie', [DescriptionIaController::class, 'suggérerCatégorie'])->name('description-ia.categorie');

    // Administration
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile/boutique', [ProfileController::class, 'boutique'])->name('profile.boutique');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
