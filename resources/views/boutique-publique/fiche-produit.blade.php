<x-boutique-layout :boutique="$boutique">
    <div class="py-4" x-data="{ quantite: 1 }">
        <a href="{{ route('boutique-publique.accueil', $boutique) }}"
           class="text-sm text-texte-secondaire hover:text-principale">← Tous les produits</a>

        @if ($produit->image_url)
            <img src="{{ '/uploads/'.$produit->image_url }}" alt="{{ $produit->nom }}" class="mt-4 w-full h-64 sm:h-80 object-cover rounded-lg">
        @else
            <div class="mt-4 w-full h-64 sm:h-80 bg-fond-alterne flex items-center justify-center rounded-lg">
                <span class="text-6xl font-bold text-texte-secondaire">{{ mb_substr($produit->nom, 0, 1) }}</span>
            </div>
        @endif

        <h1 class="mt-4 text-2xl font-bold text-texte">{{ $produit->nom }}</h1>

        @if ($nombreAvis > 0)
            <div class="mt-2 flex items-center gap-2">
                <div class="flex gap-0.5">
                    @for ($i = 1; $i <= 5; $i++)
                        <svg class="h-4 w-4 {{ $i <= round($noteMoyenne) ? 'text-accent' : 'text-fond-alterne' }}"
                             viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z" clip-rule="evenodd" />
                        </svg>
                    @endfor
                </div>
                <span class="text-sm text-texte-secondaire">{{ number_format($noteMoyenne, 1) }} · {{ $nombreAvis }} avis</span>
            </div>
        @endif

        <div class="mt-2 flex items-baseline gap-2">
            @if ($produit->prix_promo)
                <span class="text-lg font-bold text-texte">{{ number_format($produit->prixActuel(), 0, ',', ' ') }} FCFA</span>
                <span class="text-sm text-texte-secondaire line-through">{{ number_format($produit->prix, 0, ',', ' ') }} FCFA</span>
            @else
                <span class="text-lg font-bold text-texte">{{ number_format($produit->prix, 0, ',', ' ') }} FCFA</span>
            @endif
        </div>

        @if ($produit->estEnRupture())
            <span class="inline-block mt-2 text-xs px-2 py-1 rounded-full bg-alerte text-white">Rupture de stock</span>
        @elseif ($produit->estEnStockFaible())
            <span class="inline-block mt-2 text-xs px-2 py-1 rounded-full bg-avertissement text-white">Il en reste {{ $produit->stock_quantite }}</span>
        @endif

        @if ($produit->description)
            <p class="mt-4 text-base text-texte-secondaire">{{ $produit->description }}</p>
        @endif

        @if ($nombreAvis > 0)
            <div class="mt-6">
                <h3 class="font-semibold text-texte text-sm">Avis des clients</h3>
                <div class="mt-2 space-y-2">
                    @foreach ($produit->avis->take(5) as $avisItem)
                        <div class="bg-fond-alterne rounded-lg p-3">
                            <div class="flex items-center justify-between gap-2">
                                <p class="text-sm font-medium text-texte">{{ $avisItem->client_nom }}</p>
                                <div class="flex gap-0.5 shrink-0">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <svg class="h-3 w-3 {{ $i <= $avisItem->note ? 'text-accent' : 'text-fond-alterne' }}
                                             viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z" clip-rule="evenodd" />
                                        </svg>
                                    @endfor
                                </div>
                            </div>
                            @if ($avisItem->commentaire)
                                <p class="mt-1 text-sm text-texte">{{ $avisItem->commentaire }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if ($produit->estEnRupture())
            <div class="mt-6 px-4 py-3 bg-alerte/10 text-alerte text-sm rounded-md">
                Ce produit est momentanément en rupture de stock.
            </div>
        @endif
        {{-- CTA toujours visible sans scroll : fixé en bas sur mobile --}}
        @if (! $produit->estEnRupture())
            <div class="fixed bottom-0 inset-x-0 z-10 bg-fond border-t border-fond-alterne px-4 py-3 sm:static sm:border-0 sm:px-0 sm:py-0 sm:mt-6">
                <div class="max-w-3xl mx-auto flex items-center gap-3">
                    <div class="flex items-center border border-fond-alterne rounded-md">
                        <button type="button" @click="quantite = Math.max(1, quantite - 1)"
                                class="px-3 py-2 text-texte hover:text-principale">−</button>
                        <span class="px-2 w-8 text-center font-semibold text-texte" x-text="quantite"></span>
                        <button type="button" @click="quantite = Math.min({{ $produit->stock_quantite }}, quantite + 1)"
                                class="px-3 py-2 text-texte hover:text-principale">+</button>
                    </div>

                    <button type="button"
                            @click="$store.panier.ajouter({{ $produit->id }}, '{{ addslashes($produit->nom) }}', {{ $produit->prixActuel() }}, quantite, {{ $produit->stock_quantite }}, '{{ addslashes($produit->image_url ?? '') }}', '{{ $produit->id }}'); window.location.href = '{{ route('panier', $boutique) }}'"
                            class="flex-1 py-3 bg-principale text-white font-semibold text-sm rounded-md text-center">
                        Commander
                    </button>
                </div>
            </div>
        @endif
    </div>
</x-boutique-layout>
