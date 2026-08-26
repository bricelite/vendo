<x-boutique-layout :boutique="$boutique">
    <div class="py-4">
        <h1 class="text-lg font-semibold text-texte mb-4">Votre panier</h1>

        <div x-data="{ envoi: false }">
            {{-- État vide --}}
            <div x-show="$store.panier.articles.length === 0" class="py-12 text-center">
                <svg class="mx-auto h-12 w-12 text-fond-alterne" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                </svg>
                <p class="mt-4 text-texte secondaire">Votre panier est vide.</p>
                <a href="{{ route('boutique-publique.accueil', $boutique) }}"
                   class="mt-3 inline-block px-5 py-2.5 bg-principale text-white font-semibold text-sm rounded-xl">
                    Parcourir la boutique
                </a>
            </div>

            {{-- Articles du panier --}}
            <div x-show="$store.panier.articles.length > 0">
                <div class="space-y-3">
                    <template x-for="article in $store.panier.articles" :key="article.produit_id">
                        <div class="flex gap-3 border border-fond-alterne rounded-xl p-3">
                            {{-- Photo miniature --}}
                            <template x-if="article.image_url">
                                <img :src="'/uploads/' + article.image_url" :alt="article.nom"
                                     class="h-14 w-14 rounded-xl object-cover bg-fond-alterne shrink-0">
                            </template>
                            <template x-if="!article.image_url">
                                <div class="h-14 w-14 rounded-xl bg-fond-alterne flex items-center justify-center text-principale font-bold text-lg shrink-0"
                                     x-text="article.nom.charAt(0)"></div>
                            </template>

                            {{-- Détails --}}
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-texte text-sm truncate" x-text="article.nom"></p>
                                <p class="text-sm text-texte-secondaire" x-text="Number(article.prix).toLocaleString('fr-FR') + ' FCFA'"></p>
                                <div class="mt-1.5 flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <button type="button"
                                                @click="$store.panier.changerQuantite(article.produit_id, article.quantite - 1, 99999)"
                                                class="w-7 h-7 flex items-center justify-center border border-fond-alterne rounded text-texte text-sm">−</button>
                                        <span class="w-5 text-center text-sm font-medium text-texte" x-text="article.quantite"></span>
                                        <button type="button"
                                                @click="$store.panier.changerQuantite(article.produit_id, article.quantite + 1, 99999)"
                                                class="w-7 h-7 flex items-center justify-center border border-fond-alterne rounded text-texte text-sm">+</button>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-semibold text-texte"
                                              x-text="Number(article.prix * article.quantite).toLocaleString('fr-FR') + ' FCFA'"></span>
                                        <button type="button"
                                                @click="$store.panier.retirer(article.produit_id)"
                                                class="text-alerte text-xs font-medium">Retirer</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Fidélité --}}
                @if ($boutique->seuil_fidele > 0 && $boutique->reduction_fidele > 0)
                    @if ($clientEstFidele)
                        <div class="mt-3 p-3 bg-succes/10 rounded-xl text-sm text-succes font-medium text-right">
                            Réduction fidèle activée : -{{ $boutique->reduction_fidele }}%
                        </div>
                    @else
                        <div class="mt-3 p-3 glass-subtle rounded-xl text-xs text-texte-secondaire text-right">
                            Encore {{ max(0, $boutique->seuil_fidele - $nbCommandesClient) }} commande(s) pour bénéficier de {{ $boutique->reduction_fidele }}% de réduction
                        </div>
                    @endif
                @endif

                {{-- Total --}}
                <p class="mt-3 text-right text-lg font-bold text-texte">
                    Total : <span x-text="Number($store.panier.montantTotal()).toLocaleString('fr-FR') + ' FCFA'"></span>
                </p>

                <p class="text-xs text-texte-secondaire text-right mb-4">
                    Le vendeur vous contactera pour la livraison et le paiement.
                </p>
            </div>

            {{-- Commande en ligne : enregistrée pour le vendeur, le stock est mis à jour --}}
            <div x-show="$store.panier.articles.length > 0" class="space-y-4">
                @if ($errors->has('articles'))
                    <div class="bg-alerte/10 text-alerte text-sm rounded-xl px-4 py-3">
                        {{ $errors->first('articles') }}
                    </div>
                @endif

                @if (! Auth::check() || Auth::user()->role !== 'client')
                    <div class="glass-subtle rounded-xl p-3 text-sm text-texte-secondaire">
                        <a href="{{ route('client.login') }}" class="text-principale font-medium hover:underline">Connectez-vous</a>
                        pour suivre vos commandes et laisser des avis.
                    </div>
                @endif

                <form method="POST" action="{{ route('commande.creer', $boutique) }}" @submit="envoi = true">
                    @csrf
                    @if (Auth::check() && Auth::user()->role === 'client')
                        <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                    @endif
                    {{-- Les articles du panier partent avec le formulaire --}}
                    <input type="hidden" name="articles"
                           :value="JSON.stringify($store.panier.articles.map(a => ({ produit_id: a.produit_id, quantite: a.quantite })))">

                    <div>
                        <x-input-label for="client_nom" value="Votre nom" />
                        <x-text-input id="client_nom" class="block mt-1 w-full" type="text" name="client_nom"
                                      :value="old('client_nom', auth()->user()?->name)" required maxlength="100"
                                      autocomplete="name" placeholder="Ex. : Aïcha Dossou" />
                        <x-input-error :messages="$errors->get('client_nom')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="client_telephone" value="Votre numéro de téléphone" />
                        <x-text-input id="client_telephone" class="block mt-1 w-full" type="tel" name="client_telephone"
                                      :value="old('client_telephone', auth()->user()?->telephone)" required maxlength="30"
                                      autocomplete="tel" placeholder="Ex. : 01 97 12 34 56" />
                        <x-input-error :messages="$errors->get('client_telephone')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="client_localite" value="Quartier ou localité (facultatif)" />
                        <x-text-input id="client_localite" class="block mt-1 w-full" type="text" name="client_localite"
                                      :value="old('client_localite')" maxlength="100"
                                      placeholder="Ex. : Calavi, Akpakpa" />
                        <x-input-error :messages="$errors->get('client_localite')" class="mt-2" />
                    </div>

                    <p class="mt-4 text-xs text-texte-secondaire">
                        Le vendeur vous contactera au numéro indiqué pour la livraison et le paiement.
                    </p>

                    <div class="mt-6 space-y-3">
                        <button type="submit" :disabled="envoi"
                                class="w-full py-3 bg-principale text-white font-semibold text-sm rounded-xl text-center disabled:opacity-50">
                            <template x-if="!envoi">
                                <span>Commander</span>
                            </template>
                            <template x-if="envoi">
                                <span x-cloak>Envoi en cours…</span>
                            </template>
                        </button>
                        <a href="{{ route('boutique-publique.accueil', $boutique) }}"
                           class="block w-full py-3 border border-fond-alterne text-texte-secondaire font-semibold text-sm rounded-xl text-center">
                            Continuer mes achats
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-boutique-layout>
