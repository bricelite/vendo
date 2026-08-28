<x-boutique-layout :boutique="$boutique">
    <div class="pt-4" x-data="{ recherche: '' }">

        {{-- Salutation + barre de recherche --}}
        <div class="mb-4">
            <p class="text-sm text-[#6B6B63]">Bonjour 👋</p>
            <h1 class="text-xl font-bold text-[#1A1A1A]">Que cherchez-vous aujourd'hui ?</h1>
            <div class="relative mt-3">
                <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 h-5 w-5 text-[#6B6B63]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
                <input type="text" x-model="recherche"
                       placeholder="Rechercher un produit…"
                       class="w-full h-11 pl-11 pr-4 rounded-xl border border-[#E4E3DC] bg-[#FFFFFF] text-[#1A1A1A] text-sm outline-none focus:border-[#1EA562] focus:ring-1 focus:ring-[#1EA562] placeholder:text-[#6B6B63] transition">
            </div>
        </div>

        {{-- Bandeau promo --}}
        @if ($produitsEnSolde->isNotEmpty())
            <a href="{{ route('boutique-publique.accueil', $boutique) }}#produits"
               class="block mb-5 rounded-2xl overflow-hidden">
                <div class="bg-gradient-to-r from-[#1EA562] to-[#1B7A3D] px-5 py-4 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[11px] font-medium uppercase text-white/80 tracking-wide">Offres spéciales</p>
                            <p class="text-lg font-bold">Jusqu'à -{{ $produitsEnSolde->max(fn ($p) => $p->pourcentageReduction()) }}%</p>
                        </div>
                        <span class="shrink-0 inline-flex items-center justify-center rounded-full bg-white/20 px-3 py-1.5 text-sm font-semibold">Voir les promos →</span>
                    </div>
                </div>
            </a>
        @endif

        {{-- Filtres catégorie : pills horizontales scrollables --}}
        @if ($categories->isNotEmpty())
            <div class="mb-5 flex gap-2 overflow-x-auto pb-1 -mx-1 px-1">
                <a href="{{ route('boutique-publique.accueil', ['boutique' => $boutique->slug, 'tri' => $tri]) }}"
                   class="shrink-0 px-4 py-2 text-sm font-medium rounded-full transition
                          {{ empty($categorieSlug) ? 'bg-[#1EA562] text-white' : 'bg-[#FFFFFF] border border-[#E4E3DC] text-[#6B6B63] hover:border-[#1EA562] hover:text-[#1EA562]' }}">
                    Tous
                </a>
                @foreach ($categories as $cat)
                    <a href="{{ route('boutique-publique.accueil', ['boutique' => $boutique->slug, 'categorie' => $cat->slug, 'tri' => $tri]) }}"
                       class="shrink-0 px-4 py-2 text-sm font-medium rounded-full transition
                              {{ $categorieSlug === $cat->slug ? 'bg-[#1EA562] text-white' : 'bg-[#FFFFFF] border border-[#E4E3DC] text-[#6B6B63] hover:border-[#1EA562] hover:text-[#1EA562]' }}">
                        {{ $cat->nom }}
                    </a>
                @endforeach
            </div>
        @endif

        {{-- Grille produits --}}
        <div id="produits"
             x-effect="const terme = recherche.trim().toLowerCase(); document.querySelectorAll('#produits-matrice .produit-carte').forEach(c => { c.hidden = terme !== '' && !(c.dataset.nom || '').includes(terme); })">
            @if ($produits->isEmpty())
                <div class="rounded-2xl glass-carte p-10 text-center">
                    <p class="text-[#6B6B63]">La boutique arrive bientôt.</p>
                </div>
            @else
                <div class="mb-3 flex items-center justify-between">
                    <h2 class="text-base font-bold text-[#1A1A1A]">Produits populaires</h2>
                    <div class="flex items-center gap-1 text-xs text-[#6B6B63]">
                        <span>Trier :</span>
                        <a href="{{ route('boutique-publique.accueil', ['boutique' => $boutique->slug, 'categorie' => $categorieSlug]) }}"
                           class="px-2 py-1 rounded-full {{ $tri === 'recent' ? 'bg-[#1EA562] text-white' : 'text-[#1EA562]' }}">
                            Récent
                        </a>
                        <a href="{{ route('boutique-publique.accueil', ['boutique' => $boutique->slug, 'categorie' => $categorieSlug, 'tri' => 'prix_croissant']) }}"
                           class="px-2 py-1 rounded-full {{ $tri === 'prix_croissant' ? 'bg-[#1EA562] text-white' : 'text-[#1EA562]' }}">
                            Prix ↑
                        </a>
                        <a href="{{ route('boutique-publique.accueil', ['boutique' => $boutique->slug, 'categorie' => $categorieSlug, 'tri' => 'prix_decroissant']) }}"
                           class="px-2 py-1 rounded-full {{ $tri === 'prix_decroissant' ? 'bg-[#1EA562] text-white' : 'text-[#1EA562]' }}">
                            Prix ↓
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3" id="produits-matrice">
                    @foreach ($produits as $produit)
                        <a href="{{ route('boutique-publique.fiche-produit', [$boutique, $produit]) }}"
                           class="glass-carte rounded-2xl overflow-hidden transition relative produit-carte block"
                           data-nom="{{ mb_strtolower($produit->nom) }}">
                            @if ($produit->promoActive())
                                <div class="absolute top-2 left-2 z-10 px-2 py-0.5 bg-[#F2801F] text-white text-xs font-bold rounded-full">-{{ $produit->pourcentageReduction() }}%</div>
                            @endif

                            <button type="button"
                                    x-data="{ actif: $store.favoris.estFavori({{ $produit->id }}) }"
                                    data-produit='@json($produit->favorisSnapshot())'
                                    @click.stop.prevent="$store.favoris.basculer(JSON.parse($el.dataset.produit)); actif = $store.favoris.estFavori({{ $produit->id }})"
                                    class="absolute top-2 right-2 z-10 flex items-center justify-center h-9 w-9 rounded-full bg-white/90 shadow-sm"
                                    :class="actif ? 'text-[#1EA562]' : 'text-[#6B6B63]'"
                                    aria-label="Mettre en favori">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" :fill="actif ? 'currentColor' : 'none'" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                </svg>
                            </button>

                            <div class="aspect-square w-full overflow-hidden">
                                @if ($produit->image_url)
                                    <img src="{{ '/uploads/'.$produit->image_url }}" alt="{{ $produit->nom }}" loading="lazy" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-[#EEE] flex items-center justify-center">
                                        <span class="text-3xl font-bold text-[#6B6B63]">{{ mb_substr($produit->nom, 0, 1) }}</span>
                                    </div>
                                @endif
                            </div>

                            <div class="p-3">
                                <p class="text-sm font-medium text-[#1A1A1A] line-clamp-2 min-h-10">{{ $produit->nom }}</p>

                                @if ($produit->avis_count > 0)
                                    <div class="mt-1 flex items-center gap-1">
                                        <svg class="h-3.5 w-3.5 text-[#E8930F]" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                        <span class="text-xs text-[#6B6B63]">{{ number_format($produit->avis_avg_note, 1, ',', '.') }} ({{ $produit->avis_count }})</span>
                                    </div>
                                @else
                                    <div class="mt-1 flex items-center gap-1">
                                        <svg class="h-3.5 w-3.5 text-[#E4E3DC]" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                    </div>
                                @endif

                                <div class="mt-1 flex items-baseline gap-1.5">
                                    @if ($produit->prix_promo)
                                        <span class="text-base font-bold text-[#1B7A3D]">{{ number_format($produit->prixActuel(), 0, ',', ' ') }} F</span>
                                        <span class="text-xs text-[#6B6B63] line-through">{{ number_format($produit->prix, 0, ',', ' ') }} F</span>
                                    @else
                                        <span class="text-base font-bold text-[#1B7A3D]">{{ number_format($produit->prix, 0, ',', ' ') }} F</span>
                                    @endif
                                </div>

                                @if ($produit->estEnRupture())
                                    <span class="inline-block mt-2 text-[11px] px-2 py-0.5 rounded-full bg-[#C0392B] text-white">Rupture de stock</span>
                                @elseif ($produit->estEnStockFaible() && $produit->stock_quantite <= 2)
                                    <span class="inline-block mt-2 text-[11px] px-2 py-0.5 rounded-full bg-[#C08A2E] text-white">Il en reste {{ $produit->stock_quantite }}</span>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>

                <p x-show="recherche.trim() !== '' && document.querySelectorAll('#produits-matrice .produit-carte:not([hidden])').length === 0"
                   class="mt-6 text-center text-sm text-[#6B6B63]" x-cloak>
                    Aucun produit ne correspond à « <span x-text="recherche"></span> »
                </p>
            @endif
        </div>
    </div>
</x-boutique-layout>
