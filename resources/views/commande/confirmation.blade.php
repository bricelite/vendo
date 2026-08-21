<x-boutique-layout :boutique="$commande->boutique">
    {{-- La commande est enregistrée : le panier de cette boutique est vidé --}}
    <div class="py-8 text-center" x-data x-init="$store.panier.vider()">
        <div class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-succes text-white text-3xl font-bold mb-4">✓</div>

        <h1 class="text-2xl font-bold text-texte">Commande confirmée !</h1>

        <p class="mt-2 text-texte-secondaire">
            Votre référence : <span class="font-semibold text-principale">{{ $commande->reference_courte }}</span>
        </p>

        {{-- Récap des articles --}}
        <div class="mt-5 bg-fond rounded-2xl p-4 shadow-sm text-left max-w-sm mx-auto">
            <div class="divide-y divide-fond-alterne">
                @foreach ($commande->lignes as $ligne)
                    <div class="py-2.5 flex items-center justify-between gap-3 first:pt-0 last:pb-0">
                        <div class="flex items-center gap-3 min-w-0">
                            @if ($ligne->produit && $ligne->produit->image_url)
                                <img src="{{ '/uploads/'.$ligne->produit->image_url }}" alt="{{ $ligne->nom_produit }}"
                                     class="h-10 w-10 rounded-lg object-cover bg-fond-alterne shrink-0">
                            @else
                                <div class="h-10 w-10 rounded-lg bg-fond-alterne flex items-center justify-center text-principale font-bold text-sm shrink-0">
                                    {{ mb_substr($ligne->nom_produit, 0, 1) }}
                                </div>
                            @endif
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-texte truncate">{{ $ligne->nom_produit }}</p>
                                <p class="text-xs text-texte-secondaire">{{ $ligne->quantite }} × {{ number_format($ligne->prix_unitaire, 0, ',', ' ') }} FCFA</p>
                            </div>
                        </div>
                        <p class="text-sm font-semibold text-texte shrink-0">{{ number_format($ligne->prix_unitaire * $ligne->quantite, 0, ',', ' ') }} FCFA</p>
                    </div>
                @endforeach
            </div>
            <div class="mt-2 pt-2 border-t border-fond-alterne flex items-center justify-between">
                <p class="font-semibold text-texte">Total</p>
                <p class="font-bold text-texte">{{ number_format($commande->montant_produit, 0, ',', ' ') }} FCFA</p>
            </div>
        </div>

        <div class="mt-6 bg-fond-alterne rounded-lg p-4 text-left text-sm text-texte-secondaire">
            <p class="mb-1">
                Le vendeur <span class="font-semibold text-texte">{{ $commande->boutique->nom }}</span> vous contactera
                au <span class="font-semibold text-texte">{{ $commande->client_telephone }}</span> pour la livraison et le paiement.
            </p>
            <p>Gardez votre numéro de référence pour le suivi.</p>
        </div>

        @php
            // Format international requis par WhatsApp (indicatif du Bénin)
            $telephoneWhatsApp = preg_replace('/[^0-9]/', '', $commande->boutique->vendeur->telephone);
            if (! str_starts_with($telephoneWhatsApp, '229')) {
                $telephoneWhatsApp = '229'.$telephoneWhatsApp;
            }
            $messageWhatsApp = "Bonjour, je viens de passer la commande {$commande->reference_courte} sur votre boutique.";
        @endphp

        <a href="https://wa.me/{{ $telephoneWhatsApp }}?text={{ rawurlencode($messageWhatsApp) }}"
           target="_blank" rel="noopener"
           class="mt-4 inline-flex items-center justify-center gap-2 w-full max-w-sm py-3 border border-succes text-succes font-semibold text-sm rounded-md">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 002.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
            </svg>
            Contacter le vendeur sur WhatsApp
        </a>

        @if (session('succes_avis'))
            <div class="mt-4 bg-succes/10 text-succes text-sm rounded-lg px-4 py-3">
                {{ session('succes_avis') }}
            </div>
        @endif

        @if (session('erreur'))
            <div class="mt-4 bg-alerte/10 text-alerte text-sm rounded-lg px-4 py-3">
                {{ session('erreur') }}
            </div>
        @endif
    </div>

    {{-- Formulaire d'avis --}}
    <div class="mt-2" x-data="{ ouvert: false }">
        <button type="button" @click="ouvert = !ouvert"
                class="w-full flex items-center justify-between bg-fond rounded-2xl p-4 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-full bg-accent/10 flex items-center justify-center">
                    <svg class="h-5 w-5 text-accent" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="text-left">
                    <p class="font-semibold text-texte text-sm">Donner votre avis</p>
                    <p class="text-xs text-texte-secondaire">Aidez les autres acheteurs</p>
                </div>
            </div>
            <svg class="h-5 w-5 text-texte-secondaire transition-transform" :class="{ 'rotate-180': ouvert }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
            </svg>
        </button>

        <div x-show="ouvert" x-transition.duration.200ms class="mt-3 space-y-4">
            @forelse ($produitsAvis as $item)
                @php $produit = $item['produit']; @endphp
                <div class="bg-fond rounded-2xl p-4 shadow-sm">
                    <p class="font-medium text-texte text-sm">{{ $produit->nom }}</p>

                    @if ($item['dejaNote'])
                        <p class="mt-2 text-xs text-succes">✓ Avis déjà envoyé</p>
                    @else
                        <form method="POST" action="{{ route('avis.store') }}" x-data="{ note: 0, envoi: false }" @submit="envoi = true">
                            @csrf
                            <input type="hidden" name="commande_id" value="{{ $commande->id }}">
                            <input type="hidden" name="produit_id" value="{{ $produit->id }}">
                            <input type="hidden" name="client_nom" value="{{ $commande->client_nom }}">
                            <input type="hidden" name="note" :value="note">

                            <div class="mt-2 flex gap-1">
                                @for ($i = 1; $i <= 5; $i++)
                                    <button type="button" @click="note = {{ $i }}"
                                            class="h-8 w-8 transition-colors"
                                            :class="note >= {{ $i }} ? 'text-accent' : 'text-fond-alterne'">
                                        <svg viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                @endfor
                            </div>

                            <textarea name="commentaire" rows="2" maxlength="500"
                                      class="mt-2 block w-full rounded-lg border-fond-alterne text-sm text-texte placeholder:text-texte-secondaire/60 focus:border-principale focus:ring-principale/30"
                                      placeholder="Votre commentaire (facultatif)"></textarea>

                            <button type="submit"
                                    :disabled="note === 0 || envoi"
                                    class="mt-2 inline-flex items-center gap-2 px-4 py-2 bg-principale text-white font-semibold text-sm rounded-lg disabled:opacity-50 disabled:cursor-not-allowed">
                                <svg x-show="envoi" class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                </svg>
                                <span x-text="envoi ? 'Envoi...' : 'Envoyer'"></span>
                            </button>
                        </form>
                    @endif
                </div>
            @empty
                <p class="text-sm text-texte-secondaire text-center">Aucun produit dans cette commande.</p>
            @endforelse
        </div>
    </div>

    <div class="mt-6 text-center">
        <a href="{{ route('boutique-publique.accueil', $commande->boutique) }}"
           class="inline-block px-6 py-3 bg-principale text-white font-semibold text-sm rounded-md">
            Continuer mes achats
        </a>
    </div>
</x-boutique-layout>
