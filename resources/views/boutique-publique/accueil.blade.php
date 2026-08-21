<x-boutique-layout :boutique="$boutique">
    <div class="py-4" x-data="{ recherche: '' }">

        {{-- Barre de recherche + bouton partager --}}
        @if ($produits->isNotEmpty())
            <div class="flex items-center gap-2 mb-4">
                <div class="relative flex-1">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-texte-secondaire" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                    <input type="text" x-model="recherche"
                           placeholder="Rechercher un produit…"
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-fond-alterne bg-fond text-texte text-sm focus:border-principale focus:ring-1 focus:ring-principale outline-none transition">
                </div>
                <a href="https://wa.me/?text={{ rawurlencode("Découvrez la boutique {$boutique->nom} !\n" . url(route('boutique-publique.accueil', $boutique))) }}"
                   target="_blank" rel="noopener"
                   class="flex items-center justify-center w-9 h-9 rounded-lg bg-[#25D366] text-white shrink-0">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                </a>
            </div>
        @else
            <div class="mb-4">
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-texte-secondaire" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                    <input type="text" x-model="recherche"
                           placeholder="Rechercher un produit…"
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-fond-alterne bg-fond text-texte text-sm focus:border-principale focus:ring-1 focus:ring-principale outline-none transition">
                </div>
            </div>
        @endif

        @if ($produits->isEmpty())
            <div class="bg-fond-alterne rounded-lg p-8 text-center">
                <p class="text-texte-secondaire">La boutique arrive bientôt.</p>
            </div>
        @else

            {{-- Section Soldes --}}
            @if ($produitsEnSolde->isNotEmpty())
                <section class="mb-6">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="px-2.5 py-1 bg-alerte text-white text-xs font-bold rounded-full uppercase">Soldes</span>
                        <h2 class="text-base font-bold text-texte">Nos offres spéciales</h2>
                    </div>
                    <div class="flex gap-3 overflow-x-auto pb-2 -mx-1 px-1">
                         @foreach ($produitsEnSolde as $produit)
                            <a href="{{ route('boutique-publique.fiche-produit', [$boutique, $produit]) }}"
                               class="shrink-0 w-40 bg-fond border border-fond-alterne rounded-xl overflow-hidden relative">
                                @if ($produit->promoActive())
                                    <div class="absolute top-2 left-2 z-10 px-2 py-0.5 bg-alerte text-white text-xs font-bold rounded-full">-{{ $produit->pourcentageReduction() }}%</div>
                                @endif
                                @if ($produit->image_url)
                                    <img src="{{ '/uploads/'.$produit->image_url }}" alt="{{ $produit->nom }}" loading="lazy" class="w-full h-28 object-cover">
                                @else
                                    <div class="w-full h-28 bg-fond-alterne flex items-center justify-center">
                                        <span class="text-3xl font-bold text-texte-secondaire">{{ mb_substr($produit->nom, 0, 1) }}</span>
                                    </div>
                                @endif
                                <div class="p-2.5">
                                    <p class="text-sm font-medium text-texte line-clamp-1">{{ $produit->nom }}</p>
                                    <div class="mt-1 flex items-baseline gap-1.5">
                                        <span class="text-sm font-bold text-alerte">{{ number_format($produit->prixActuel(), 0, ',', ' ') }} F</span>
                                        <span class="text-xs text-texte-secondaire line-through">{{ number_format($produit->prix, 0, ',', ' ') }} F</span>
                                    </div>
                                    @if ($produit->avis_count > 0)
                                        <div class="mt-1 flex items-center gap-1">
                                            <svg class="h-3 w-3 text-accent" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                            <span class="text-xs text-texte-secondaire">{{ number_format($produit->avis_avg_note, 1, ',', '.') }} ({{ $produit->avis_count }})</span>
                                        </div>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif

            {{-- Section Pour vous --}}
            @if ($suggestions->isNotEmpty())
                <section class="mb-6">
                    <h2 class="text-base font-bold text-texte mb-3 flex items-center gap-2">
                        <span class="w-1 h-5 bg-accent rounded-full"></span>
                        @if ($clientEstFidele)
                            Bon retour parmi nous !
                        @else
                            Pour vous
                        @endif
                    </h2>
                    <div class="flex gap-3 overflow-x-auto pb-2 -mx-1 px-1">
                        @foreach ($suggestions as $item)
                            @php $produit = $item['produit']; @endphp
                            <a href="{{ route('boutique-publique.fiche-produit', [$boutique, $produit]) }}"
                               class="shrink-0 w-40 bg-fond border border-fond-alterne rounded-xl overflow-hidden relative">
                                @if ($produit->promoActive())
                                    <div class="absolute top-2 left-2 z-10 px-2 py-0.5 bg-alerte text-white text-xs font-bold rounded-full">-{{ $produit->pourcentageReduction() }}%</div>
                                @endif
                                @if ($produit->image_url)
                                    <img src="{{ '/uploads/'.$produit->image_url }}" alt="{{ $produit->nom }}" loading="lazy" class="w-full h-28 object-cover">
                                @else
                                    <div class="w-full h-28 bg-fond-alterne flex items-center justify-center">
                                        <span class="text-3xl font-bold text-texte-secondaire">{{ mb_substr($produit->nom, 0, 1) }}</span>
                                    </div>
                                @endif
                                <div class="p-2.5">
                                    <p class="text-sm font-medium text-texte line-clamp-1">{{ $produit->nom }}</p>
                                    @if ($produit->categorie)
                                        <p class="text-xs text-texte-secondaire">{{ $produit->categorie->nom }}</p>
                                    @endif
                                    <div class="mt-1 flex items-baseline gap-1.5">
                                        @if ($produit->prix_promo)
                                            <span class="text-sm font-bold text-accent">{{ number_format($produit->prixActuel(), 0, ',', ' ') }} F</span>
                                            <span class="text-xs text-texte-secondaire line-through">{{ number_format($produit->prix, 0, ',', ' ') }} F</span>
                                        @else
                                            <span class="text-sm font-bold text-texte">{{ number_format($produit->prix, 0, ',', ' ') }} F</span>
                                        @endif
                                    </div>
                                    @if ($produit->avis_count > 0)
                                        <div class="mt-1 flex items-center gap-1">
                                            <svg class="h-3 w-3 text-accent" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                            <span class="text-xs text-texte-secondaire">{{ number_format($produit->avis_avg_note, 1, ',', '.') }} ({{ $produit->avis_count }})</span>
                                        </div>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif

            {{-- Filtres catégorie --}}
            @if ($categories->isNotEmpty())
                <div class="mb-4 flex gap-2 overflow-x-auto pb-1 -mx-1 px-1">
                    <a href="{{ route('boutique-publique.accueil', ['boutique' => $boutique->slug, 'tri' => $tri]) }}"
                       class="shrink-0 px-3 py-1.5 text-sm font-medium rounded-full transition
                              {{ empty($categorieSlug) ? 'bg-principale text-white' : 'bg-fond-alterne text-texte-secondaire hover:bg-fond-alterne/80' }}">
                        Tous
                    </a>
                    @foreach ($categories as $cat)
                        <a href="{{ route('boutique-publique.accueil', ['boutique' => $boutique->slug, 'categorie' => $cat->slug, 'tri' => $tri]) }}"
                           class="shrink-0 px-3 py-1.5 text-sm font-medium rounded-full transition
                                  {{ $categorieSlug === $cat->slug ? 'bg-principale text-white' : 'bg-fond-alterne text-texte-secondaire hover:bg-fond-alterne/80' }}">
                            {{ $cat->nom }} ({{ $cat->produits_count }})
                        </a>
                    @endforeach
                </div>
            @endif

            {{-- Tri --}}
            <div class="mb-4 flex items-center gap-2">
                <span class="text-xs text-texte-secondaire">Trier par :</span>
                <div class="flex gap-1">
                    <a href="{{ route('boutique-publique.accueil', ['boutique' => $boutique->slug, 'categorie' => $categorieSlug]) }}"
                       class="px-2.5 py-1 text-xs font-medium rounded-lg transition
                              {{ $tri === 'recent' ? 'bg-principale text-white' : 'bg-fond-alterne text-texte-secondaire hover:bg-fond-alterne/80' }}">
                        Récent
                    </a>
                    <a href="{{ route('boutique-publique.accueil', ['boutique' => $boutique->slug, 'categorie' => $categorieSlug, 'tri' => 'prix_croissant']) }}"
                       class="px-2.5 py-1 text-xs font-medium rounded-lg transition
                              {{ $tri === 'prix_croissant' ? 'bg-principale text-white' : 'bg-fond-alterne text-texte-secondaire hover:bg-fond-alterne/80' }}">
                        Prix ↑
                    </a>
                    <a href="{{ route('boutique-publique.accueil', ['boutique' => $boutique->slug, 'categorie' => $categorieSlug, 'tri' => 'prix_decroissant']) }}"
                       class="px-2.5 py-1 text-xs font-medium rounded-lg transition
                              {{ $tri === 'prix_decroissant' ? 'bg-principale text-white' : 'bg-fond-alterne text-texte-secondaire hover:bg-fond-alterne/80' }}">
                        Prix ↓
                    </a>
                </div>
            </div>

            {{-- Produits classés par catégorie --}}
            @foreach ($produitsParCategorie as $nomCategorie => $produitsCategorie)
                <section class="mb-6">
                    <h2 class="text-base font-bold text-texte mb-3 flex items-center gap-2">
                        <span class="w-1 h-5 bg-accent rounded-full"></span>
                        {{ $nomCategorie }}
                        <span class="text-xs font-normal text-texte-secondaire">({{ $produitsCategorie->count() }})</span>
                    </h2>

                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach ($produitsCategorie as $produit)
                            <a href="{{ route('boutique-publique.fiche-produit', [$boutique, $produit]) }}"
                               class="bg-fond border border-fond-alterne rounded-lg overflow-hidden transition relative"
                               x-show="recherche === '' || '{{ mb_strtolower($produit->nom) }}'.includes(recherche.trim().toLowerCase())"
                               x-transition.opacity.duration.150ms>
                                @if ($produit->promoActive())
                                    <div class="absolute top-2 left-2 z-10 px-2 py-0.5 bg-alerte text-white text-xs font-bold rounded-full">-{{ $produit->pourcentageReduction() }}%</div>
                                @endif
                                @if ($produit->image_url)
                                    <img src="{{ '/uploads/'.$produit->image_url }}" alt="{{ $produit->nom }}" loading="lazy" class="w-full h-40 sm:h-48 object-cover">
                                @else
                                    <div class="w-full h-40 sm:h-48 bg-fond-alterne flex items-center justify-center">
                                        <span class="text-4xl font-bold text-texte-secondaire">{{ mb_substr($produit->nom, 0, 1) }}</span>
                                    </div>
                                @endif

                                <div class="p-3">
                                    <p class="text-sm font-medium text-texte line-clamp-2">{{ $produit->nom }}</p>

                                    <div class="mt-1 flex items-baseline gap-2">
                                        @if ($produit->prix_promo)
                                            <span class="text-sm font-bold text-accent">{{ number_format($produit->prixActuel(), 0, ',', ' ') }} F</span>
                                            <span class="text-xs text-texte-secondaire line-through">{{ number_format($produit->prix, 0, ',', ' ') }} F</span>
                                        @else
                                            <span class="text-sm font-bold text-texte">{{ number_format($produit->prix, 0, ',', ' ') }} F</span>
                                        @endif
                                    </div>

                                    @if ($produit->avis_count > 0)
                                        <div class="mt-1 flex items-center gap-1">
                                            <svg class="h-3 w-3 text-accent" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                            <span class="text-xs text-texte-secondaire">{{ number_format($produit->avis_avg_note, 1, ',', '.') }} ({{ $produit->avis_count }})</span>
                                        </div>
                                    @endif

                                    @if ($produit->estEnRupture())
                                        <span class="inline-block mt-2 text-xs px-2 py-1 rounded-full bg-alerte text-white">Rupture de stock</span>
                                    @elseif ($produit->estEnStockFaible())
                                        <span class="inline-block mt-2 text-xs px-2 py-1 rounded-full bg-avertissement text-white">Il en reste {{ $produit->stock_quantite }}</span>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endforeach

            <p x-show="recherche.trim() !== '' && document.querySelectorAll('[x-show]:not([x-cloak])').length === 0"
               class="mt-6 text-center text-sm text-texte-secondaire"
               x-cloak>
                Aucun produit ne correspond à « <span x-text="recherche"></span> »
            </p>
        @endif
    </div>
</x-boutique-layout>
