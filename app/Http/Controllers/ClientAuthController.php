<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Rules\TelephoneBenin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class ClientAuthController extends Controller
{
    /**
     * Affiche le formulaire d'inscription client.
     */
    public function showRegister()
    {
        return view('client.register');
    }

    /**
     * Enregistre un nouveau compte client.
     */
    public function register(Request $request)
    {
        $donnees = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'telephone' => ['required', 'string', 'max:30', new TelephoneBenin(), 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $utilisateur = User::create([
            'name' => $donnees['name'],
            'telephone' => $donnees['telephone'],
            'password' => $donnees['password'],
            'role' => 'client',
        ]);

        Auth::login($utilisateur);

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Affiche le formulaire de connexion client.
     */
    public function showLogin()
    {
        return view('client.login');
    }

    /**
     * Connecte un client existant.
     */
    public function login(Request $request)
    {
        $donnees = $request->validate([
            'telephone' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $utilisateur = User::where('telephone', $donnees['telephone'])->where('role', 'client')->first();

        if (! $utilisateur || ! Hash::check($donnees['password'], $utilisateur->password)) {
            return back()->withErrors([
                'telephone' => 'Numéro ou mot de passe incorrect.',
            ])->onlyInput('telephone');
        }

        Auth::login($utilisateur);

        return redirect()->intended(route('client.historique'));
    }

    /**
     * Déconnecte le client.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
