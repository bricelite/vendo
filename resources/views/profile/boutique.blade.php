<x-app-layout>
    <x-slot name="header">Ma boutique</x-slot>

    @if (! $boutique)
        <div class="bg-fond rounded-2xl shadow-sm p-10 text-center">
            <p class="text-texte">Vous n'avez pas encore de boutique.</p>
        </div>
    @else
        {{-- Onglets boutique / compte --}}
        <div class="flex gap-2 mb-4">
            <a href="{{ route('profile.boutique') }}"
               class="px-4 py-2 rounded-full text-sm font-semibold bg-principale text-white">Ma boutique</a>
            <a href="{{ route('profile.edit') }}"
               class="px-4 py-2 rounded-full text-sm font-medium bg-fond text-texte-secondaire border border-fond-alterne">Mon compte</a>
        </div>

        {{-- Infos de base --}}
        <div class="bg-fond rounded-2xl p-5 shadow-sm">
            <h3 class="font-semibold text-texte">Informations</h3>

            <form method="POST" action="{{ route('boutique.update') }}" enctype="multipart/form-data" class="mt-4 space-y-4">
                @csrf
                @method('PATCH')

                {{-- Logo --}}
                <div x-data="{ preview: null }">
                    <x-input-label value="Logo de la boutique" />
                    <div class="mt-2 flex items-center gap-4">
                        @if ($boutique->logo_url)
                            <img src="{{ '/uploads/'.$boutique->logo_url }}" alt="Logo"
                                 class="h-16 w-16 rounded-xl object-cover bg-fond-alterne"
                                 x-show="!preview">
                        @else
                            <div class="h-16 w-16 rounded-xl bg-fond-alterne flex items-center justify-center text-principale font-bold text-xl"
                                 x-show="!preview">
                                {{ mb_substr($boutique->nom, 0, 1) }}
                            </div>
                        @endif
                        <img x-show="preview" :src="preview" class="h-16 w-16 rounded-xl object-cover">
                        <div class="flex-1">
                            <input type="file" name="logo" accept="image/jpeg,image/png,image/webp"
                                   class="block w-full text-sm text-texte file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-principale/10 file:text-principale hover:file:bg-principale/20"
                                   x-ref="logoInput"
                                   @change="if($refs.logoInput.files[0]){const reader=new FileReader();reader.onload=e=>preview=e.target.result;reader.readAsDataURL($refs.logoInput.files[0]);}">
                            <p class="mt-1 text-xs text-texte-secondaire">JPEG, PNG ou WebP. Max 2 Mo.</p>
                        </div>
                    </div>
                </div>

                {{-- Photo de couverture --}}
                <div x-data="{ previewCouverture: null }">
                    <x-input-label value="Photo de couverture" />
                    <p class="text-xs text-texte-secondaire mb-2">Photo affichée en haut de votre boutique publique.</p>
                    <div class="mt-2">
                        @if ($boutique->couverture_url)
                            <img src="{{ '/uploads/'.$boutique->couverture_url }}" alt="Couverture"
                                 class="w-full h-24 sm:h-32 object-cover rounded-xl bg-fond-alterne"
                                 x-show="!previewCouverture">
                        @else
                            <div class="w-full h-24 sm:h-32 rounded-xl bg-gradient-to-r from-principale to-accent flex items-center justify-center text-white/60 text-sm"
                                 x-show="!previewCouverture">
                                Pas encore de couverture
                            </div>
                        @endif
                        <img x-show="previewCouverture" :src="previewCouverture" class="w-full h-24 sm:h-32 object-cover rounded-xl">
                        <input type="file" name="couverture" accept="image/jpeg,image/png,image/webp"
                               class="mt-2 block w-full text-sm text-texte file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-principale/10 file:text-principale hover:file:bg-principale/20"
                               x-ref="couvertureInput"
                               @change="if($refs.couvertureInput.files[0]){const reader=new FileReader();reader.onload=e=>previewCouverture=e.target.result;reader.readAsDataURL($refs.couvertureInput.files[0]);}">
                    </div>
                </div>

                <div>
                    <x-input-label for="boutique_nom" value="Nom de la boutique" />
                    <x-text-input id="boutique_nom" name="nom" type="text" class="mt-1 block w-full"
                                  :value="old('nom', $boutique->nom)" maxlength="80" required />
                    <x-input-error class="mt-2" :messages="$errors->get('nom')" />
                </div>

                <div>
                    <x-input-label for="boutique_description" value="Description" />
                    <textarea id="boutique_description" name="description" rows="2" maxlength="500"
                              class="mt-1 block w-full rounded-lg border-fond-alterne text-texte text-sm focus:border-principale focus:ring-principale/30"
                              placeholder="Décrivez votre boutique en quelques mots">{{ old('description', $boutique->description) }}</textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('description')" />
                </div>

                <div>
                    <x-input-label for="boutique_localisation" value="Adresse / Quartier" />
                    <x-text-input id="boutique_localisation" name="localisation" type="text" class="mt-1 block w-full"
                                  :value="old('localisation', $boutique->localisation)" maxlength="200"
                                  placeholder="Ex. : Ganhi, Cotonou" />
                    <x-input-error class="mt-2" :messages="$errors->get('localisation')" />
                </div>

                <div>
                    <x-input-label for="boutique_google_maps_url" value="Lien Google Maps (optionnel)" />
                    <x-text-input id="boutique_google_maps_url" name="google_maps_url" type="url" class="mt-1 block w-full"
                                  :value="old('google_maps_url', $boutique->google_maps_url)" maxlength="500"
                                  placeholder="https://maps.app.goo.gl/..." />
                    <p class="mt-1 text-xs text-texte-secondaire">Collez le lien de partage Google Maps pour que vos clients vous trouvent facilement.</p>
                    <x-input-error class="mt-2" :messages="$errors->get('google_maps_url')" />
                </div>

                <x-primary-button>Enregistrer</x-primary-button>
            </form>

            {{-- Lien de la boutique --}}
            <div class="mt-5 pt-4 border-t border-fond-alterne" x-data="partageLien()">
                <input type="hidden" value="{{ $lienBoutique }}">
                <p class="text-sm font-medium text-texte">Lien de ma boutique</p>
                <p class="mt-1 text-xs text-texte-secondaire">Envoyez ce lien à vos clients sur WhatsApp, Facebook ou Instagram.</p>
                <div class="mt-3 flex items-center gap-2">
                    <p class="flex-1 min-w-0 text-sm text-principale truncate bg-fond-alterne rounded-lg px-3 py-2">{{ $lienBoutique }}</p>
                    <button type="button" @click="copier()"
                            class="shrink-0 inline-flex items-center gap-1.5 px-4 py-2.5 bg-principale text-white font-semibold text-sm rounded-lg">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186l9.566-5.314m-9.566 7.5l9.566 5.314m0 0a2.25 2.25 0 103.935 2.186 2.25 2.25 0 00-3.935-2.186zm0-12.814a2.25 2.25 0 103.933-2.185 2.25 2.25 0 00-3.933 2.185z" />
                        </svg>
                        Copier
                    </button>
                </div>
            </div>
        </div>

        {{-- Paiements Mobile Money --}}
        <div class="mt-4 bg-fond rounded-2xl p-5 shadow-sm">
            <h3 class="font-semibold text-texte">Recevoir mes paiements</h3>
            <p class="text-xs text-texte-secondaire mt-1">Les clients paieront directement sur ce numéro.</p>

            <form method="POST" action="{{ route('boutique.update') }}" class="mt-4 space-y-4" x-data="{ operateur: '{{ old('operateur_mobile_money', $boutique->operateur_mobile_money ?? '') }}' }">
                @csrf
                @method('PATCH')

                <div>
                    <x-input-label for="numero_mobile_money" value="Numéro Mobile Money" />
                    <x-text-input id="numero_mobile_money" name="numero_mobile_money" type="tel" class="mt-1 block w-full"
                                  :value="old('numero_mobile_money', $boutique->numero_mobile_money)" maxlength="30"
                                  placeholder="Ex. : 01 96 00 00 00" />
                    <x-input-error class="mt-2" :messages="$errors->get('numero_mobile_money')" />
                </div>

                <div>
                    <x-input-label value="Opérateur" />
                    <div class="mt-2 flex gap-3">
                        <button type="button" @click="operateur = 'mtn'"
                                :class="operateur === 'mtn' ? 'border-principale bg-principale/10 text-principale' : 'border-fond-alterne text-texte-secondaire'"
                                class="flex-1 py-3 rounded-xl border-2 text-sm font-semibold text-center transition">
                            MTN
                        </button>
                        <button type="button" @click="operateur = 'moov'"
                                :class="operateur === 'moov' ? 'border-principale bg-principale/10 text-principale' : 'border-fond-alterne text-texte-secondaire'"
                                class="flex-1 py-3 rounded-xl border-2 text-sm font-semibold text-center transition">
                            Moov
                        </button>
                    </div>
                    <input type="hidden" name="operateur_mobile_money" :value="operateur">
                </div>

                <x-primary-button>Enregistrer</x-primary-button>
            </form>
        </div>

        {{-- Durée de réservation --}}
        <div class="mt-4 bg-fond rounded-2xl p-5 shadow-sm">
            <h3 class="font-semibold text-texte">Durée de réservation par défaut</h3>
            <p class="text-xs text-texte-secondaire mt-1">Le temps laissé à un client pour payer un article réservé avant qu'il soit remis en vente.</p>

            <form method="POST" action="{{ route('boutique.update') }}" class="mt-4 space-y-4">
                @csrf
                @method('PATCH')

                <div>
                    <x-input-label for="duree_reservation" value="Durée" />
                    <select id="duree_reservation" name="duree_reservation_defaut_minutes"
                            class="mt-1 block w-full rounded-lg border-fond-alterne text-texte text-sm focus:border-principale focus:ring-principale/30">
                        @php $dureeActuelle = old('duree_reservation_defaut_minutes', $boutique->duree_reservation_defaut_minutes ?? 360); @endphp
                        <option value="60" {{ $dureeActuelle == 60 ? 'selected' : '' }}>1 heure</option>
                        <option value="120" {{ $dureeActuelle == 120 ? 'selected' : '' }}>2 heures</option>
                        <option value="360" {{ $dureeActuelle == 360 ? 'selected' : '' }}>6 heures</option>
                        <option value="720" {{ $dureeActuelle == 720 ? 'selected' : '' }}>12 heures</option>
                        <option value="1440" {{ $dureeActuelle == 1440 ? 'selected' : '' }}>24 heures</option>
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('duree_reservation_defaut_minutes')" />
                </div>

                <x-primary-button>Enregistrer</x-primary-button>
            </form>
        </div>

        {{-- Programme fidélité --}}
        <div class="mt-4 bg-fond rounded-2xl p-5 shadow-sm">
            <h3 class="font-semibold text-texte">Clients fidèles</h3>
            <p class="text-xs text-texte-secondaire mt-1">Récompensez vos clients réguliers avec une réduction automatique.</p>

            <form method="POST" action="{{ route('boutique.update') }}" class="mt-4 space-y-4">
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <x-input-label for="seuil_fidele" value="Après combien de commandes ?" />
                        <x-text-input id="seuil_fidele" name="seuil_fidele" type="number" min="0" max="100" class="mt-1 block w-full"
                                      :value="old('seuil_fidele', $boutique->seuil_fidele)" placeholder="Ex. : 3" />
                    </div>
                    <div>
                        <x-input-label for="reduction_fidele" value="Réduction (%)" />
                        <x-text-input id="reduction_fidele" name="reduction_fidele" type="number" min="0" max="100" class="mt-1 block w-full"
                                      :value="old('reduction_fidele', $boutique->reduction_fidele)" placeholder="Ex. : 10" />
                    </div>
                </div>

                @if ($boutique->seuil_fidele > 0 && $boutique->reduction_fidele > 0)
                    <p class="text-xs text-succes bg-succes/10 rounded-lg px-3 py-2">
                        Vos clients reçoivent {{ $boutique->reduction_fidele }}% de réduction après {{ $boutique->seuil_fidele }} commande(s) payée(s).
                    </p>
                @endif

                <x-primary-button>Enregistrer</x-primary-button>
            </form>
        </div>
    @endif
</x-app-layout>
