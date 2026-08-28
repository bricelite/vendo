<x-boutique-layout :boutique="$boutique">
    <div class="py-4" x-data="{ quantite: 1 }">
        <a href="{{ route('boutique-publique.accueil', $boutique) }}"
           class="text-sm text-[#6B6B63] hover:text-[#1EA562]">← Tous les produits</a>

        @if ($produit->image_url)
            <img src="{{ '/uploads/'.$produit->image_url }}" alt="{{ $produit->nom }}" class="mt-4 w-full h-64 sm:h-80 object-cover rounded-2xl">
        @else
            <div class="mt-4 w-full h-64 sm:h-80 bg-[#EEE] flex items-center justify-center rounded-2xl">
                <span class="text-6xl font-bold text-[#6B6B63]">{{ mb_substr($produit->nom, 0, 1) }}</span>
            </div>
        @endif

        <div class="mt-4 flex items-start justify-between gap-3">
            <h1 class="text-2xl font-bold text-[#1A1A1A]">{{ $produit->nom }}</h1>
            <button type="button"
                    x-data="{ actif: $store.favoris.estFavori({{ $produit->id }}) }"
                    data-produit='@json($produit->favorisSnapshot())'
                    @click.stop.prevent="$store.favoris.basculer(JSON.parse($el.dataset.produit)); actif = $store.favoris.estFavori({{ $produit->id }})"
                    class="flex items-center justify-center h-11 w-11 rounded-full bg-[#FFFFFF] border border-[#E4E3DC] shrink-0"
                    :class="actif ? 'text-[#1EA562]' : 'text-[#6B6B63]'"
                    aria-label="Mettre en favori">
                <svg class="h-5 w-5" viewBox="0 0 24 24" :fill="actif ? 'currentColor' : 'none'" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                </svg>
            </button>
        </div>

        @if ($nombreAvis > 0)
            <div class="mt-2 flex items-center gap-2">
                <div class="flex gap-0.5">
                    @for ($i = 1; $i <= 5; $i++)
                        <svg class="h-4 w-4 {{ $i <= round($noteMoyenne) ? 'text-[#E8930F]' : 'text-[#E4E3DC]' }}"
                             viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z" clip-rule="evenodd" />
                        </svg>
                    @endfor
                </div>
                <span class="text-sm text-[#6B6B63]">{{ number_format($noteMoyenne, 1) }} · {{ $nombreAvis }} avis</span>
            </div>
        @endif

        <div class="mt-2 flex items-baseline gap-2">
            @if ($produit->prix_promo)
                <span class="text-lg font-bold text-[#1B7A3D]">{{ number_format($produit->prixActuel(), 0, ',', ' ') }} FCFA</span>
                <span class="text-sm text-[#6B6B63] line-through">{{ number_format($produit->prix, 0, ',', ' ') }} FCFA</span>
            @else
                <span class="text-lg font-bold text-[#1B7A3D]">{{ number_format($produit->prix, 0, ',', ' ') }} FCFA</span>
            @endif
        </div>

        @if ($produit->estEnRupture())
            <span class="inline-block mt-2 text-xs px-2 py-1 rounded-full bg-[#C0392B] text-white">Rupture de stock</span>
        @elseif ($produit->estEnStockFaible())
            <span class="inline-block mt-2 text-xs px-2 py-1 rounded-full bg-[#C08A2E] text-white">Il en reste {{ $produit->stock_quantite }}</span>
        @endif

        @if ($produit->description)
            <p class="mt-4 text-base text-[#6B6B63]">{{ $produit->description }}</p>
        @endif

        @if ($nombreAvis > 0)
            <div class="mt-6">
                <h3 class="font-semibold text-[#1A1A1A] text-sm">Avis des clients</h3>
                <div class="mt-2 space-y-2">
                    @foreach ($produit->avis->take(5) as $avisItem)
                        <div class="rounded-xl glass-carte p-3">
                            <div class="flex items-center justify-between gap-2">
                                <p class="text-sm font-medium text-[#1A1A1A]">{{ $avisItem->client_nom }}</p>
                                <div class="flex gap-0.5 shrink-0">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <svg class="h-3 w-3 {{ $i <= $avisItem->note ? 'text-[#E8930F]' : 'text-[#E4E3DC]' }}"
                                             viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z" clip-rule="evenodd" />
                                        </svg>
                                    @endfor
                                </div>
                            </div>
                            @if ($avisItem->commentaire)
                                <p class="mt-1 text-sm text-[#1A1A1A]">{{ $avisItem->commentaire }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if ($produit->estEnRupture())
            <div class="mt-6 px-4 py-3 bg-[#C0392B]/10 text-[#C0392B] text-sm rounded-xl">
                Ce produit est momentanément en rupture de stock.
            </div>
        @endif
        {{-- CTA toujours visible sans scroll : fixé en bas sur mobile --}}
        @if (! $produit->estEnRupture())
            <div class="fixed bottom-16 inset-x-0 z-20 bg-[#FFFFFF] border-t border-[#E4E3DC] px-4 py-3 sm:static sm:border-0 sm:px-0 sm:py-0 sm:mt-6">
                <div class="max-w-3xl mx-auto flex items-center gap-3">
                    <div class="flex items-center border border-[#E4E3DC] rounded-xl">
                        <button type="button" @click="quantite = Math.max(1, quantite - 1)"
                                class="px-3 py-2 text-[#1A1A1A] text-lg">−</button>
                        <span class="px-2 w-8 text-center font-semibold text-[#1A1A1A]" x-text="quantite"></span>
                        <button type="button" @click="quantite = Math.min({{ $produit->stock_quantite }}, quantite + 1)"
                                class="px-3 py-2 text-[#1A1A1A] text-lg">+</button>
                    </div>

                    <button type="button"
                            @click="$store.panier.ajouter({{ $produit->id }}, '{{ addslashes($produit->nom) }}', {{ $produit->prixActuel() }}, quantite, {{ $produit->stock_quantite }}, '{{ addslashes($produit->image_url ?? '') }}', '{{ $produit->id }}'); window.location.href = '{{ route('panier', $boutique) }}'"
                            class="flex-1 min-h-12 py-3 bg-[#F2801F] hover:bg-[#D97016] text-white font-semibold text-sm rounded-xl text-center transition">
                        Commander
                    </button>
                </div>
            </div>
        @endif
    </div>
</x-boutique-layout>
