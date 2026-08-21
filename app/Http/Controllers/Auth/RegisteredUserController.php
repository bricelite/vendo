<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Rules\TelephoneBenin;
use App\Services\BoutiqueService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view (inscription vendeur en 3 étapes).
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * Les trois étapes (compte, boutique, premier produit) sont enregistrées
     * dans une seule transaction via BoutiqueService.
     */
    public function store(Request $request, BoutiqueService $boutiqueService): RedirectResponse
    {
        $donnees = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'telephone' => ['required', 'string', 'max:30', new TelephoneBenin(), 'unique:'.User::class],
            'email' => ['nullable', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'boutique_nom' => ['required', 'string', 'max:150'],
            'boutique_localisation' => ['nullable', 'string', 'max:200'],
            'google_maps_url' => ['nullable', 'url', 'max:500'],
            'produit_nom' => ['nullable', 'string', 'max:150'],
            'produit_prix' => ['nullable', 'integer', 'min:1'],
            'produit_stock' => ['nullable', 'integer', 'min:0'],
        ]);

        $utilisateur = $boutiqueService->creerCompteVendeur([
            'name' => $donnees['name'],
            'telephone' => $donnees['telephone'],
            'email' => $donnees['email'] ?? null,
            'password' => $donnees['password'],
            'boutique_nom' => $donnees['boutique_nom'],
            'boutique_localisation' => $donnees['boutique_localisation'] ?? null,
            'google_maps_url' => $donnees['google_maps_url'] ?? null,
            'produit_nom' => $donnees['produit_nom'] ?? null,
            'produit_prix' => $donnees['produit_prix'] ?? null,
            'produit_stock' => $donnees['produit_stock'] ?? null,
        ]);

        event(new Registered($utilisateur));

        Auth::login($utilisateur);

        return redirect(route('dashboard', absolute: false));
    }
}
