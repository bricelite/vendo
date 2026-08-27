<section>
    <header>
        <h2 class="text-lg font-medium text-texte">
            Mes informations
        </h2>

        <p class="mt-1 text-sm text-texte-secondaire">
            Mettez à jour votre nom et vos coordonnées.
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" value="Votre nom" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="telephone" value="Numéro de téléphone" />
            <x-text-input id="telephone" name="telephone" type="tel" class="mt-1 block w-full" :value="old('telephone', $user->telephone)" required autocomplete="tel" placeholder="Ex. : 01 97 12 34 56" />
            <x-input-error class="mt-2" :messages="$errors->get('telephone')" />
        </div>

        <div>
            <x-input-label for="email" value="Email (facultatif)" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button class="shrink-0">Enregistrer</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-succes"
                >Enregistré.</p>
            @endif
        </div>
    </form>
</section>
