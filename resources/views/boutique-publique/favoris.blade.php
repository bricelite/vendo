<x-boutique-layout :boutique="$boutique">
    <div class="pt-4">
        <h1 class="text-xl font-bold text-[#1A1A1A] mb-4">Mes favoris</h1>

        {{-- État vide --}}
        <div x-show="$store.favoris.liste.length === 0" class="py-12 text-center">
            <svg class="mx-auto h-12 w-12 text-[#E4E3DC]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
            </svg>
            <p class="mt-4 text-[#6B6B63]">Vous n'avez pas encore de produits favoris.</p>
            <a href="{{ route('boutique-publique.accueil', $boutique) }}"
               class="mt-3 inline-block px-5 min-h-11 leading-[2.4] font-semibold text-sm rounded-xl bg-[#F2801F] hover:bg-[#D97016] text-white transition">
                Découvrir les produits
            </a>
        </div>

        {{-- Liste des favoris --}}
        <div x-show="$store.favoris.liste.length > 0" class="space-y-2.5">
            <template x-for="favori in $store.favoris.liste" :key="favori.produit_id">
                <div class="flex gap-3 rounded-xl p-2.5 glass-carte">
                    <template x-if="favori.image_url">
                        <img :src="'/uploads/' + favori.image_url" :alt="favori.nom"
                             class="h-14 w-14 rounded-xl object-cover shrink-0 bg-[#EEE]">
                    </template>
                    <template x-if="!favori.image_url">
                        <div class="h-14 w-14 rounded-xl flex items-center justify-center font-bold text-lg text-[#6B6B63] bg-[#EEE] shrink-0"
                             x-text="favori.nom.charAt(0)"></div>
                    </template>

                    <div class="flex-1 min-w-0 flex flex-col justify-center">
                        <p class="font-medium text-sm text-[#1A1A1A] line-clamp-1" x-text="favori.nom"></p>
                        <div class="mt-0.5 flex items-baseline gap-1.5">
                            <span class="text-sm font-bold text-[#1B7A3D]" x-text="Number(favori.prix_actuel).toLocaleString('fr-FR') + ' FCFA'"></span>
                            <span x-show="favori.prix_original > favori.prix_actuel"
                                  class="text-xs text-[#6B6B63] line-through" x-text="Number(favori.prix_original).toLocaleString('fr-FR') + ' FCFA'"></span>
                        </div>
                    </div>

                    <div class="shrink-0 flex flex-col justify-center gap-1">
                        <a :href="'{{ url('/boutique/'.$boutique->slug) }}/' + favori.produit_id"
                           class="inline-flex items-center justify-center min-h-11 px-4 rounded-xl bg-[#F2801F] hover:bg-[#D97016] text-white text-sm font-semibold transition">
                            Voir
                        </a>
                        <button type="button"
                                @click="$store.favoris.retirer(favori.produit_id)"
                                class="inline-flex items-center justify-center gap-1 text-xs font-medium text-[#6B6B63] min-h-8">
                            Retirer
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>
</x-boutique-layout>
