<article class="glass-solid p-4" id="produit-{{ $produit->id }}" data-produit-carte="{{ $produit->id }}">
    <div class="flex gap-3">
        @if ($produit->image_url)
            <img src="{{ '/uploads/'.$produit->image_url }}" alt=""
                 class="h-20 w-20 shrink-0 rounded-xl object-cover border border-fond-alterne">
        @else
            <div class="h-20 w-20 shrink-0 rounded-xl bg-fond-alterne flex items-center justify-center">
                <svg class="h-8 w-8 text-texte-secondaire" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5z" />
                </svg>
            </div>
        @endif

        <div class="min-w-0 flex-1">
            <p class="font-semibold text-texte truncate">{{ $produit->nom }}</p>

            <div class="mt-1 flex flex-wrap items-center gap-1.5">
                @if (! $produit->est_disponible)
                    <span data-dispo-badge class="inline-block text-[11px] font-medium px-2 py-0.5 rounded-full bg-fond-alterne text-texte-secondaire">En pause</span>
                @elseif ($produit->estEnRupture())
                    <span class="inline-block text-[11px] font-medium px-2 py-0.5 rounded-full bg-alerte text-white">Rupture de stock</span>
                @elseif ($produit->estEnStockFaible())
                    <span class="inline-block text-[11px] font-medium px-2 py-0.5 rounded-full bg-avertissement text-white">Il en reste {{ $produit->stock_quantite }}</span>
                @elseif ($produit->prix_promo)
                    <span class="inline-block text-[11px] font-medium px-2 py-0.5 rounded-full bg-accent text-white">Promo</span>
                @endif
            </div>

            <p class="mt-1.5 text-sm font-semibold text-texte">
                @if ($produit->prix_promo)
                    <span class="text-succes">{{ number_format($produit->prix_promo, 0, ',', ' ') }} FCFA</span>
                    <span class="text-texte-secondaire line-through">{{ number_format($produit->prix, 0, ',', ' ') }} FCFA</span>
                @else
                    {{ number_format($produit->prix, 0, ',', ' ') }} FCFA
                @endif
            </p>
        </div>
    </div>

    <div class="mt-3 flex items-center gap-2 border-t border-fond-alterne pt-3">
        <a href="{{ route('produits.modifier', $produit) }}"
           class="inline-flex flex-1 items-center justify-center gap-1 px-3 py-2 text-sm font-medium text-principale rounded-xl hover:bg-fond-alterne">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
            </svg>
            Modifier
        </a>

        <form method="POST" action="{{ route('produits.disponibilite', $produit) }}"
              data-ajax data-action="disponibilite" class="flex-1">
            @csrf
            @method('PATCH')
            <button type="submit"
                    class="inline-flex w-full items-center justify-center gap-1 px-3 py-2 text-sm font-medium text-texte-secondaire rounded-xl hover:bg-fond-alterne">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                </svg>
                <span data-dispo-label>{{ $produit->est_disponible ? 'Masquer' : 'Remettre en vente' }}</span>
            </button>
        </form>

        <form method="POST" action="{{ route('produits.destroy', $produit) }}"
              data-ajax data-action="supprimer" data-remove-target="#produit-{{ $produit->id }}"
              onsubmit="return confirm('Supprimer « {{ $produit->nom }} » ? Cette action est irréversible.')">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="inline-flex items-center justify-center h-9 w-9 text-texte-secondaire rounded-xl hover:text-alerte hover:bg-fond-alterne"
                    title="Supprimer">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                </svg>
            </button>
        </form>
    </div>
</article>
