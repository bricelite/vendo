<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" x-data="{ envoiEnCours: false }" @submit="envoiEnCours = true">
        @csrf

        <!-- Téléphone -->
        <div>
            <x-input-label for="telephone" value="Numéro de téléphone" />
            <x-text-input id="telephone" class="block mt-1 w-full" type="tel" name="telephone" :value="old('telephone')" required autofocus autocomplete="tel" placeholder="Ex. : 01 97 12 34 56" />
            <x-input-error :messages="$errors->get('telephone')" class="mt-2" />
        </div>

        <!-- Mot de passe -->
        <div class="mt-4">
            <x-input-label for="password" value="Mot de passe" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Se souvenir de moi -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-principale text-principale shadow-sm focus:ring-principale" name="remember">
                <span class="ms-2 text-sm text-texte-secondaire">Se souvenir de moi</span>
            </label>
        </div>

        <div class="mt-6">
            <button type="submit" :disabled="envoiEnCours"
                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-principale border border-transparent rounded-xl font-semibold text-sm text-white tracking-wide transition ease-in-out duration-150 hover:opacity-90 disabled:opacity-50">
                <template x-if="!envoiEnCours">
                    <span>Se connecter</span>
                </template>
                <template x-if="envoiEnCours">
                    <span class="inline-flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                        Connexion…
                    </span>
                </template>
            </button>
        </div>
    </form>

    <div class="mt-6 text-center">
        <a class="text-sm text-texte-secondaire hover:text-principale" href="{{ route('register') }}">
            Vous n'avez pas encore de boutique ? Créez-la gratuitement
        </a>
    </div>
</x-guest-layout>
