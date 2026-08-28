<x-boutique-layout :boutique="$boutique">
    <div class="pt-4">
        <h1 class="text-xl font-bold text-[#1A1A1A] mb-4">Votre panier</h1>

        <div x-data="{ envoi: false, modePaiement: 'mobile_money' }">

            {{-- État vide --}}
            <div x-show="$store.panier.articles.length === 0" class="py-12 text-center">
                <svg class="mx-auto h-12 w-12 text-[#E4E3DC]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                </svg>
                <p class="mt-4 text-[#6B6B63]">Votre panier est vide.</p>
                <a href="{{ route('boutique-publique.accueil', $boutique) }}"
                   class="mt-3 inline-block px-5 min-h-11 leading-[2.4] font-semibold text-sm rounded-xl bg-[#F2801F] hover:bg-[#D97016] text-white transition">
                    Parcourir la boutique
                </a>
            </div>

            {{-- Contenu du panier + commande --}}
            <div x-show="$store.panier.articles.length > 0">

                {{-- Liste des articles --}}
                <div class="space-y-2.5">
                    <template x-for="article in $store.panier.articles" :key="article.produit_id">
                        <div class="flex gap-3 rounded-xl p-2.5 glass-carte">
                            <template x-if="article.image_url">
                                <img :src="'/uploads/' + article.image_url" :alt="article.nom"
                                     class="h-14 w-14 rounded-xl object-cover shrink-0 bg-[#EEE]">
                            </template>
                            <template x-if="!article.image_url">
                                <div class="h-14 w-14 rounded-xl flex items-center justify-center font-bold text-lg text-[#6B6B63] bg-[#EEE] shrink-0"
                                     x-text="article.nom.charAt(0)"></div>
                            </template>

                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-sm text-[#1A1A1A] truncate" x-text="article.nom"></p>
                                <p class="text-xs text-[#6B6B63]" x-text="Number(article.prix).toLocaleString('fr-FR') + ' FCFA'"></p>
                                <div class="mt-2 flex items-center justify-between gap-2">
                                    <div class="flex items-center gap-2">
                                        <button type="button"
                                                @click="$store.panier.changerQuantite(article.produit_id, article.quantite - 1, 99999)"
                                                class="h-8 w-8 flex items-center justify-center rounded-lg text-[#1A1A1A] border border-[#E4E3DC] text-base"
                                                aria-label="Diminuer la quantité">−</button>
                                        <span class="w-5 text-center text-sm font-medium text-[#1A1A1A]" x-text="article.quantite"></span>
                                        <button type="button"
                                                @click="$store.panier.changerQuantite(article.produit_id, article.quantite + 1, 99999)"
                                                class="h-8 w-8 flex items-center justify-center rounded-lg text-[#1A1A1A] border border-[#E4E3DC] text-base"
                                                aria-label="Augmenter la quantité">+</button>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-bold text-[#1B7A3D]"
                                              x-text="Number(article.prix * article.quantite).toLocaleString('fr-FR') + ' FCFA'"></span>
                                        <button type="button"
                                                @click="$store.panier.retirer(article.produit_id)"
                                                class="text-xs font-medium text-[#C0392B] min-h-8">Retirer</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Fidélité --}}
                @if ($boutique->seuil_fidele > 0 && $boutique->reduction_fidele > 0)
                    @if ($clientEstFidele)
                        <div class="mt-3 p-3 bg-[#1EA562]/10 rounded-xl text-sm text-[#1B7A3D] font-medium text-right">
                            Réduction fidèle activée : -{{ $boutique->reduction_fidele }}%
                        </div>
                    @else
                        <div class="mt-3 p-3 rounded-xl text-xs text-[#6B6B63] bg-[#F7F5EF] text-right">
                            Encore {{ max(0, $boutique->seuil_fidele - $nbCommandesClient) }} commande(s) pour bénéficier de {{ $boutique->reduction_fidele }}% de réduction
                        </div>
                    @endif
                @endif

                {{-- Livraison : retrait boutique ou livraison avec adresse libre --}}
                <div class="mt-5">
                    <p class="font-semibold text-sm text-[#1A1A1A] mb-2">Livraison</p>
                    <div class="grid grid-cols-2 gap-2">
                        <button type="button"
                                @click="$store.panier.setModeLivraison('livraison')"
                                class="flex flex-col items-center gap-1 min-h-11 py-3 rounded-xl text-sm font-medium transition-all"
                                :style="$store.panier.modeLivraison === 'livraison'
                                    ? 'background:#FFFFFF; color:#1A1A1A; border:2px solid #1EA562'
                                    : 'background:#FFFFFF; color:#6B6B63; border:1px solid #E4E3DC'">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0H21.375a1.125 1.125 0 001.125-1.125V14.25m0 0H5.625m15.75 0V6.375c0-.621-.504-1.125-1.125-1.125H16.5m-2.25 0h-2.25m0 0V4.125c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125v1.125m0 0h-2.25m2.25 0V6.375" />
                            </svg>
                            Livraison
                        </button>
                        <button type="button"
                                @click="$store.panier.setModeLivraison('retrait')"
                                class="flex flex-col items-center gap-1 min-h-11 py-3 rounded-xl text-sm font-medium transition-all"
                                :style="$store.panier.modeLivraison === 'retrait'
                                    ? 'background:#FFFFFF; color:#1A1A1A; border:2px solid #1EA562'
                                    : 'background:#FFFFFF; color:#6B6B63; border:1px solid #E4E3DC'">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016A3.001 3.001 0 0021 9.349m-18 0V6.375a3 3 0 013-3h12a3 3 0 013 3v.75" />
                            </svg>
                            Retrait boutique
                        </button>
                    </div>

                    <p class="text-xs text-[#6B6B63] mt-2">
                        <span x-show="$store.panier.modeLivraison === 'livraison'">Indiquez votre quartier ou adresse ci-dessous.</span>
                        <span x-show="$store.panier.modeLivraison === 'retrait'">Vous viendrez récupérer votre commande en boutique. Un code de retrait vous sera communiqué.</span>
                    </p>
                </div>

                {{-- Mode de paiement --}}
                <div class="mt-5">
                    <p class="font-semibold text-sm text-[#1A1A1A] mb-2">Mode de paiement</p>
                    <div class="space-y-2">
                        {{-- Mobile Money : mis en avant --}}
                        <button type="button"
                                @click="modePaiement = 'mobile_money'"
                                class="w-full flex items-center gap-3 min-h-11 p-3 rounded-xl text-left transition-all"
                                :class="modePaiement === 'mobile_money'
                                    ? 'bg-[#FFFFFF] text-[#1A1A1A] border-2 border-[#F2801F]'
                                    : 'bg-[#FFFFFF] text-[#6B6B63] border border-[#E4E3DC]'">
                            <span class="flex items-center justify-center h-9 w-9 rounded-full bg-[#1EA562]/10 text-[#1B7A3D] shrink-0">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 11.858 12 10.5 12c-1.357 0-3.036-.219-4.621-1.659-.878-.879-.878-2.303 0-3.182 1.172-.879 3.07-.879 4.242 0l.879.659m-4.5 4.5l1.5 1.5" />
                                </svg>
                            </span>
                            <span class="flex-1">
                                <span class="block text-sm font-semibold">Mobile Money</span>
                                <span class="block text-xs" style="color: #6B6B63">Payer maintenant par Mobile Money</span>
                            </span>
                            <svg x-show="modePaiement === 'mobile_money'" class="h-5 w-5 text-[#1EA562]" fill="none" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </button>

                        {{-- Paiement à la livraison --}}
                        <button type="button"
                                @click="modePaiement = 'livraison'"
                                class="w-full flex items-center gap-3 min-h-11 p-3 rounded-xl text-left transition-all"
                                :class="modePaiement === 'livraison'
                                    ? 'bg-[#FFFFFF] text-[#1A1A1A] border-2 border-[#F2801F]'
                                    : 'bg-[#FFFFFF] text-[#6B6B63] border border-[#E4E3DC]'">
                            <span class="flex items-center justify-center h-9 w-9 rounded-full bg-[#F2801F]/10 text-[#F2801F] shrink-0">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l1.5-4.5 1.5 1.5 3-3 1.5 1.5 3-3 1.5 1.5 1.5-4.5" />
                                </svg>
                            </span>
                            <span class="flex-1">
                                <span class="block text-sm font-semibold">À la livraison</span>
                                <span class="block text-xs" style="color: #6B6B63">Paiement à la réception de la commande</span>
                            </span>
                            <svg x-show="modePaiement === 'livraison'" class="h-5 w-5 text-[#1EA562]" fill="none" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Récap total --}}
                <div class="mt-5 p-4 rounded-xl glass-carte">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-[#6B6B63]">Total</span>
                        <span class="text-lg font-bold text-[#1B7A3D]"
                              x-text="Number($store.panier.montantTotal()).toLocaleString('fr-FR') + ' FCFA'"></span>
                    </div>
                </div>

                {{-- Formulaire de commande --}}
                <div class="mt-4 space-y-4">
                    @if ($errors->has('articles'))
                        <div class="bg-[#C0392B]/10 text-[#C0392B] text-sm rounded-xl px-4 py-3">
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
                        {{-- Mode de paiement --}}
                        <input type="hidden" name="mode_paiement" :value="modePaiement">

                        <div>
                            <x-input-label for="client_nom" value="Votre nom" />
                            <x-text-input id="client_nom" class="block mt-1 w-full" type="text" name="client_nom"
                                          :value="old('client_nom')" required maxlength="100"
                                          autocomplete="name" placeholder="Ex. : Aïcha Dossou" />
                            <x-input-error :messages="$errors->get('client_nom')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="client_telephone" value="Votre numéro de téléphone" />
                            <x-text-input id="client_telephone" class="block mt-1 w-full" type="tel" name="client_telephone"
                                          :value="old('client_telephone')" required maxlength="30"
                                          autocomplete="tel" placeholder="Ex. : 01 97 12 34 56" />
                            <x-input-error :messages="$errors->get('client_telephone')" class="mt-2" />
                        </div>

                        {{-- Champ adresse/quartier : visible uniquement en mode livraison --}}
                        <div class="mt-4" x-show="$store.panier.modeLivraison === 'livraison'" x-transition>
                            <x-input-label for="client_localite" value="Quartier ou adresse" />
                            <x-text-input id="client_localite" class="block mt-1 w-full" type="text" name="client_localite"
                                          :value="old('client_localite')" maxlength="100"
                                          placeholder="Ex. : Calavi, Akpakpa" />
                            <x-input-error :messages="$errors->get('client_localite')" class="mt-2" />
                        </div>

                        <div class="mt-6">
                            <button type="submit" :disabled="envoi"
                                    class="w-full min-h-12 py-3 inline-flex items-center justify-center gap-2 font-semibold text-sm rounded-xl bg-[#F2801F] hover:bg-[#D97016] text-white transition disabled:opacity-50">
                                <template x-if="!envoi">
                                    <span class="inline-flex items-center gap-2">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Confirmer la commande
                                    </span>
                                </template>
                                <template x-if="envoi">
                                    <span x-cloak>Envoi en cours…</span>
                                </template>
                            </button>
                        </div>
                    </form>

                    <a href="{{ route('boutique-publique.accueil', $boutique) }}"
                       class="block w-full min-h-12 py-3 font-semibold text-sm rounded-xl text-center border border-[#E4E3DC] bg-[#FFFFFF] text-[#6B6B63]">
                        Continuer mes achats
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-boutique-layout>
