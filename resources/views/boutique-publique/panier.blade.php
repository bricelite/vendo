<x-boutique-layout :boutique="$boutique">
    <div class="py-4">
        <h1 class="font-fraunces font-semibold mb-4" style="font-size: 15px; color: #1F3A5F">Votre panier</h1>

        <div x-data="{ envoi: false }">
            {{-- État vide --}}
            <div x-show="$store.panier.articles.length === 0" class="py-12 text-center">
                <svg class="mx-auto h-12 w-12" style="color: #E7DFCB" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                </svg>
                <p class="mt-4" style="color: #8A7550">Votre panier est vide.</p>
                <a href="{{ route('boutique-publique.accueil', $boutique) }}"
                   class="mt-3 inline-block px-5 py-2.5 font-semibold text-sm rounded-xl"
                   style="background: #C08A2E; color: #1F3A5F">
                    Parcourir la boutique
                </a>
            </div>

            {{-- Articles du panier --}}
            <div x-show="$store.panier.articles.length > 0">
                <div class="space-y-2.5">
                    <template x-for="article in $store.panier.articles" :key="article.produit_id">
                        <div class="flex gap-3 rounded-xl p-2.5" style="background: #FFFFFF; border: 0.5px solid #E7DFCB; border-radius: 12px">
                            {{-- Photo miniature --}}
                            <template x-if="article.image_url">
                                <img :src="'/uploads/' + article.image_url" :alt="article.nom"
                                     class="h-11 w-11 rounded-lg object-cover shrink-0" style="background: #EADFC5">
                            </template>
                            <template x-if="!article.image_url">
                                <div class="h-11 w-11 rounded-lg flex items-center justify-center font-bold text-sm shrink-0"
                                     style="background: #EADFC5; color: #8A7550"
                                     x-text="article.nom.charAt(0)"></div>
                            </template>

                            {{-- Détails --}}
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-sm truncate" style="color: #1F3A5F" x-text="article.nom"></p>
                                <p style="font-size: 12px; color: #8A7550" x-text="Number(article.prix).toLocaleString('fr-FR') + ' FCFA'"></p>
                                <div class="mt-1.5 flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <button type="button"
                                                @click="$store.panier.changerQuantite(article.produit_id, article.quantite - 1, 99999)"
                                                class="w-7 h-7 flex items-center justify-center rounded text-sm"
                                                style="border: 0.5px solid #E7DFCB; color: #1F3A5F">−</button>
                                        <span class="w-5 text-center text-sm font-medium" style="color: #1F3A5F" x-text="article.quantite"></span>
                                        <button type="button"
                                                @click="$store.panier.changerQuantite(article.produit_id, article.quantite + 1, 99999)"
                                                class="w-7 h-7 flex items-center justify-center rounded text-sm"
                                                style="border: 0.5px solid #E7DFCB; color: #1F3A5F">+</button>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-semibold"
                                              style="color: #1F3A5F"
                                              x-text="Number(article.prix * article.quantite).toLocaleString('fr-FR') + ' FCFA'"></span>
                                        <button type="button"
                                                @click="$store.panier.retirer(article.produit_id)"
                                                class="text-xs font-medium" style="color: #B94A3C">Retirer</button>
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
                        <div class="mt-3 p-3 rounded-xl text-xs text-right" style="background: #E7DFCB40; color: #8A7550">
                            Encore {{ max(0, $boutique->seuil_fidele - $nbCommandesClient) }} commande(s) pour bénéficier de {{ $boutique->reduction_fidele }}% de réduction
                        </div>
                    @endif
                @endif

                {{-- Choix livraison / retrait --}}
                <div class="mt-5">
                    <p class="font-medium mb-2" style="font-size: 12px; color: #1F3A5F">Comment récupérer votre commande ?</p>
                    <div class="grid grid-cols-2 gap-2">
                        <button type="button"
                                @click="$store.panier.setModeLivraison('livraison')"
                                class="flex flex-col items-center gap-1 py-3 rounded-xl text-sm font-medium transition-all"
                                :style="$store.panier.modeLivraison === 'livraison'
                                    ? 'background: #C08A2E; color: #1F3A5F; border: 1.5px solid #C08A2E'
                                    : 'background: #FFFFFF; color: #1F3A5F; border: 0.5px solid #E7DFCB'">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0H21.375a1.125 1.125 0 001.125-1.125V14.25m0 0H5.625m15.75 0V6.375c0-.621-.504-1.125-1.125-1.125H16.5m-2.25 0h-2.25m0 0V4.125c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125v1.125m0 0h-2.25m2.25 0V6.375" />
                            </svg>
                            Livraison
                        </button>
                        <button type="button"
                                @click="$store.panier.setModeLivraison('retrait')"
                                class="flex flex-col items-center gap-1 py-3 rounded-xl text-sm font-medium transition-all"
                                :style="$store.panier.modeLivraison === 'retrait'
                                    ? 'background: #C08A2E; color: #1F3A5F; border: 1.5px solid #C08A2E'
                                    : 'background: #FFFFFF; color: #1F3A5F; border: 0.5px solid #E7DFCB'">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016A3.001 3.001 0 0021 9.349m-18 0V6.375a3 3 0 013-3h12a3 3 0 013 3v.75" />
                            </svg>
                            Retrait boutique
                        </button>
                    </div>
                </div>

                {{-- Total --}}
                <div class="mt-4 flex items-center justify-between">
                    <span style="font-size: 13px; color: #1F3A5F">Total</span>
                    <span class="font-fraunces font-semibold" style="font-size: 18px; color: #1F3A5F"
                          x-text="Number($store.panier.montantTotal()).toLocaleString('fr-FR') + ' FCFA'"></span>
                </div>

                {{-- Texte d'aide selon le mode --}}
                <p class="text-xs text-center mt-1" style="color: #8A7550">
                    <span x-show="$store.panier.modeLivraison === 'livraison'">Le vendeur vous contactera pour la livraison et le paiement.</span>
                    <span x-show="$store.panier.modeLivraison === 'retrait'">Un code de retrait vous sera envoyé pour récupérer votre article en boutique.</span>
                </p>
            </div>

            {{-- Commande en ligne : enregistrée pour le vendeur, le stock est mis à jour --}}
            <div x-show="$store.panier.articles.length > 0" class="space-y-4 mt-4">
                @if ($errors->has('articles'))
                    <div class="bg-alerte/10 text-alerte text-sm rounded-xl px-4 py-3">
                        {{ $errors->first('articles') }}
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
                    {{-- Mode de livraison --}}
                    <input type="hidden" name="mode_retrait" :value="$store.panier.modeLivraison">

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

                    {{-- Champ quartier : visible uniquement en mode livraison --}}
                    <div class="mt-4" x-show="$store.panier.modeLivraison === 'livraison'" x-transition>
                        <x-input-label for="client_localite" value="Quartier ou localité" />
                        <x-text-input id="client_localite" class="block mt-1 w-full" type="text" name="client_localite"
                                      :value="old('client_localite')" maxlength="100"
                                      placeholder="Ex. : Calavi, Akpakpa" />
                        <x-input-error :messages="$errors->get('client_localite')" class="mt-2" />
                    </div>

                    <div class="mt-6 space-y-3">
                        <button type="submit" :disabled="envoi"
                                class="w-full py-3 font-medium text-sm rounded-xl text-center disabled:opacity-50"
                                style="background: #C08A2E; color: #1F3A5F; border-radius: 10px; padding-top: 11px; padding-bottom: 11px; font-size: 14px">
                            <template x-if="!envoi">
                                <span>Commander</span>
                            </template>
                            <template x-if="envoi">
                                <span x-cloak>Envoi en cours…</span>
                            </template>
                        </button>
                    </div>
                </form>

                {{-- Lien discret sous le bouton --}}
                @if (! Auth::check() || Auth::user()->role !== 'client')
                    <p class="text-center" style="font-size: 11px; color: #A99B7C">
                        <a href="{{ route('client.login') }}" class="hover:underline">Se connecter pour suivre vos commandes</a>
                    </p>
                @endif

                <a href="{{ route('boutique-publique.accueil', $boutique) }}"
                   class="block w-full py-3 font-semibold text-sm rounded-xl text-center"
                   style="border: 0.5px solid #E7DFCB; color: #8A7550; background: #FFFFFF">
                    Continuer mes achats
                </a>
            </div>
        </div>
    </div>
</x-boutique-layout>
