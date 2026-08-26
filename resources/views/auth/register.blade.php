<x-guest-layout>
    <div class="mb-6">
        <p class="text-lg font-semibold text-texte">Créez votre boutique en 3 étapes</p>
        <p class="text-sm text-texte-secondaire">Ça prend moins de 5 minutes.</p>
    </div>

    @php
        // En cas d'erreur de validation, réafficher en priorité l'étape fautive :
        // sinon l'utilisateur atterrit sur une autre étape et ne voit jamais son erreur.
        $etapeInitiale = 1;
        if ($errors->hasAny(['name', 'telephone', 'email', 'password', 'password_confirmation'])) {
            $etapeInitiale = 1;
        } elseif ($errors->hasAny(['boutique_nom', 'boutique_localisation', 'google_maps_url'])) {
            $etapeInitiale = 2;
        } elseif ($errors->hasAny(['produit_nom', 'produit_prix', 'produit_stock'])) {
            $etapeInitiale = 3;
        } elseif (old('produit_nom')) {
            $etapeInitiale = 3;
        } elseif (old('boutique_nom')) {
            $etapeInitiale = 2;
        }
    @endphp

    <div class="mb-4" x-data="{ etape: {{ $etapeInitiale }} }">
        <p class="text-sm text-texte-secondaire mb-2" x-text="'Étape ' + etape + ' sur 3'"></p>
        <div class="h-1 w-full bg-fond-alterne rounded-full overflow-hidden">
            <div class="h-full bg-principale transition-all duration-300" :style="'width: ' + (etape / 3) * 100 + '%'"></div>
        </div>
    </div>

    <noscript>
        <div class="mb-4 p-3 rounded-xl bg-avertissement/10 text-avertissement text-sm">
            L'inscription nécessite JavaScript. Veuillez l'activer dans votre navigateur.
        </div>
    </noscript>

    <form method="POST" action="{{ route('register') }}" novalidate
          x-data="{
            envoiEnCours: false,
            etape: {{ $etapeInitiale }},
            champInvalide: '',
            messageChamp: '',
            signalerErreur(nomChamp, message) {
                this.champInvalide = nomChamp;
                this.messageChamp = message;
                var champ = this.$el.querySelector('[name=' + nomChamp + ']');
                if (champ) champ.focus();
            },
            continuer() {
                var section = this.$refs['etape-' + this.etape];
                var champs = section.querySelectorAll('[required]');
                var premierVide = null;

                champs.forEach(function (champ) {
                    if (!champ.value.trim() && !premierVide) {
                        premierVide = champ;
                    }
                });

                if (premierVide) {
                    this.signalerErreur(premierVide.name, 'Ce champ est obligatoire');
                    return;
                }

                if (this.etape === 1) {
                    var tel = section.querySelector('[name=telephone]');
                    // Même règle que le serveur : format béninois 01 XX XX XX XX (ou ancien 8 chiffres)
                    if (tel && !/^(01\d{8}|[6-9]\d{7})$/.test(tel.value.replace(/[\s.\-]/g, ''))) {
                        this.signalerErreur('telephone', 'Numéro invalide. Exemple : 01 97 12 34 56');
                        return;
                    }

                    var mdp = section.querySelector('[name=password]');
                    var mdpConfirm = section.querySelector('[name=password_confirmation]');
                    if (mdp && mdp.value.length < 8) {
                        this.signalerErreur('password', 'Le mot de passe doit contenir au moins 8 caractères');
                        return;
                    }
                    if (mdp && mdpConfirm && mdp.value !== mdpConfirm.value) {
                        this.signalerErreur('password_confirmation', 'Les mots de passe ne correspondent pas');
                        return;
                    }
                }

                this.champInvalide = '';
                if (this.etape < 3) {
                    this.etape++;
                } else {
                    this.envoiEnCours = true;
                    this.$el.submit();
                }
            },
            retour() {
                if (this.etape > 1) this.etape--;
            },
            soumettre() {
                this.envoiEnCours = true;
                this.$el.submit();
            },
          }">
        @csrf

        <!-- Étape 1 : le compte -->
        <div x-ref="etape-1" x-show="etape === 1" x-transition.opacity.duration.200ms>
            <div>
                <x-input-label for="name" value="Votre nom" />
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')"
                              required autofocus autocomplete="name" placeholder="Ex. : Aïcha Dossou"
                              @input="champInvalide = ''" x-bind:class="champInvalide === 'name' ? 'border-alerte' : ''" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                <p class="mt-2 text-sm text-alerte" x-show="champInvalide === 'name'" x-cloak x-text="messageChamp"></p>
            </div>

            <div class="mt-4">
                <x-input-label for="telephone" value="Votre numéro de téléphone" />
                <x-text-input id="telephone" class="block mt-1 w-full" type="tel" name="telephone" :value="old('telephone')"
                              required autocomplete="tel" placeholder="Ex. : 01 97 12 34 56"
                              @input="champInvalide = ''" x-bind:class="champInvalide === 'telephone' ? 'border-alerte' : ''" />
                <x-input-error :messages="$errors->get('telephone')" class="mt-2" />
                <p class="mt-2 text-sm text-alerte" x-show="champInvalide === 'telephone'" x-cloak x-text="messageChamp"></p>
            </div>

            <div class="mt-4">
                <x-input-label for="password" value="Mot de passe" />
                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password"
                              required minlength="8" autocomplete="new-password" placeholder="Au moins 8 caractères"
                              @input="champInvalide = ''" x-bind:class="champInvalide === 'password' ? 'border-alerte' : ''" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                <p class="mt-2 text-sm text-alerte" x-show="champInvalide === 'password'" x-cloak x-text="messageChamp"></p>
            </div>

            <div class="mt-4">
                <x-input-label for="password_confirmation" value="Confirmez le mot de passe" />
                <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                              name="password_confirmation" required minlength="8" autocomplete="new-password"
                              @input="champInvalide = ''" x-bind:class="champInvalide === 'password_confirmation' ? 'border-alerte' : ''" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                <p class="mt-2 text-sm text-alerte" x-show="champInvalide === 'password_confirmation'" x-cloak x-text="messageChamp"></p>
            </div>

            <button type="button" @click="continuer()" :disabled="envoiEnCours"
                    class="mt-6 w-full inline-flex items-center justify-center px-4 py-3 bg-principale border border-transparent rounded-xl font-semibold text-sm text-white tracking-wide hover:opacity-90 transition ease-in-out duration-150 disabled:opacity-50">
                Continuer
            </button>
        </div>

        <!-- Étape 2 : la boutique -->
        <div x-ref="etape-2" x-show="etape === 2" x-transition.opacity.duration.200ms>
            <div>
                <x-input-label for="boutique_nom" value="Le nom de votre boutique" />
                <x-text-input id="boutique_nom" class="block mt-1 w-full" type="text" name="boutique_nom" :value="old('boutique_nom')"
                              required placeholder="Ex. : Boutique Aïcha"
                              @input="champInvalide = ''" x-bind:class="champInvalide === 'boutique_nom' ? 'border-alerte' : ''" />
                <x-input-error :messages="$errors->get('boutique_nom')" class="mt-2" />
                <p class="mt-2 text-sm text-alerte" x-show="champInvalide === 'boutique_nom'" x-cloak x-text="messageChamp"></p>
            </div>

            <div class="mt-4">
                <x-input-label for="boutique_localisation" value="Localisation (facultatif)" />
                <x-text-input id="boutique_localisation" class="block mt-1 w-full" type="text" name="boutique_localisation" :value="old('boutique_localisation')"
                              placeholder="Ex. : Ganhi, Cotonou" />
                <x-input-error :messages="$errors->get('boutique_localisation')" class="mt-2" />
            </div>

            <div class="mt-3">
                <div class="flex items-center gap-3 mb-3">
                    <div class="h-px flex-1 bg-fond-alterne"></div>
                    <span class="text-xs text-texte-secondaire font-medium">ou</span>
                    <div class="h-px flex-1 bg-fond-alterne"></div>
                </div>

                <a href="https://www.google.com/maps" target="_blank" rel="noopener"
                   class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 border border-fond-alterne rounded-xl text-sm font-medium text-texte hover:bg-fond-alterne transition">
                    <svg class="h-5 w-5 text-succes" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                    </svg>
                    Trouver ma localisation sur Google Maps
                </a>

                <div class="mt-3">
                    <x-input-label for="google_maps_url" value="Lien Google Maps (facultatif)" />
                    <x-text-input id="google_maps_url" class="block mt-1 w-full" type="url" name="google_maps_url" :value="old('google_maps_url')"
                                  placeholder="https://maps.app.goo.gl/..." maxlength="500" />
                    <p class="mt-1 text-xs text-texte-secondaire">Collez le lien de votre localisation depuis Google Maps.</p>
                    <x-input-error :messages="$errors->get('google_maps_url')" class="mt-2" />
                </div>
            </div>

            <p class="mt-4 text-sm text-texte-secondaire">
                C'est ce nom que vos clients verront sur votre boutique en ligne.
            </p>

            <div class="mt-6 space-y-3">
                <button type="button" @click="continuer()" :disabled="envoiEnCours"
                        class="w-full inline-flex items-center justify-center px-4 py-3 bg-principale border border-transparent rounded-xl font-semibold text-sm text-white tracking-wide hover:opacity-90 transition ease-in-out duration-150 disabled:opacity-50">
                    Continuer
                </button>
                <button type="button" @click="retour()"
                        class="w-full inline-flex items-center justify-center px-4 py-2 border border-principale rounded-xl font-semibold text-sm text-principale bg-transparent hover:bg-principale/5 transition ease-in-out duration-150">
                    Retour
                </button>
            </div>
        </div>

        <!-- Étape 3 : le premier produit (facultatif) -->
        <div x-ref="etape-3" x-show="etape === 3" x-transition.opacity.duration.200ms>
            <div>
                <x-input-label for="produit_nom" value="Votre premier produit" />
                <x-text-input id="produit_nom" class="block mt-1 w-full" type="text" name="produit_nom" :value="old('produit_nom')"
                              placeholder="Ex. : Robe en pagne" />
                <x-input-error :messages="$errors->get('produit_nom')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="produit_prix" value="Prix en FCFA" />
                <x-text-input id="produit_prix" class="block mt-1 w-full" type="number" min="1" name="produit_prix" :value="old('produit_prix', 1000)"
                              placeholder="Ex. : 5000" />
                <x-input-error :messages="$errors->get('produit_prix')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="produit_stock" value="Combien en avez-vous en stock ?" />
                <x-text-input id="produit_stock" class="block mt-1 w-full" type="number" min="0" name="produit_stock" :value="old('produit_stock', 1)"
                              placeholder="Ex. : 10" />
                <x-input-error :messages="$errors->get('produit_stock')" class="mt-2" />
            </div>

            <p class="mt-4 text-sm text-texte-secondaire">
                Vous pourrez ajouter d'autres produits et vos photos plus tard.
            </p>

            <div class="mt-6 space-y-3">
                <button type="button" @click="continuer()" :disabled="envoiEnCours"
                        class="w-full inline-flex items-center justify-center px-4 py-3 bg-principale border border-transparent rounded-xl font-semibold text-sm text-white tracking-wide transition ease-in-out duration-150 disabled:opacity-50">
                    <template x-if="!envoiEnCours">
                        <span>Créer ma boutique</span>
                    </template>
                    <template x-if="envoiEnCours">
                        <span>Création en cours…</span>
                    </template>
                </button>
                <button type="button" @click="retour()"
                        class="w-full inline-flex items-center justify-center px-4 py-2 border border-principale rounded-xl font-semibold text-sm text-principale bg-transparent hover:bg-principale/5 transition ease-in-out duration-150">
                    Retour
                </button>
                <button type="button" @click="soumettre()"
                        :disabled="envoiEnCours"
                        class="w-full text-center text-sm text-texte-secondaire hover:text-texte py-2 transition disabled:opacity-50">
                    Ignorer
                </button>
            </div>
        </div>
    </form>

    <div class="mt-6 text-center">
        <a class="text-sm text-texte-secondaire hover:text-principale" href="{{ route('login') }}">
            Vous avez déjà un compte ? Connectez-vous
        </a>
    </div>
</x-guest-layout>
