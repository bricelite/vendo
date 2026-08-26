<x-app-layout>
    <x-slot name="header">Mes produits</x-slot>

    @if ($produits->isEmpty())
        <div class="glass-solid p-10 text-center">
            <div class="mx-auto h-16 w-16 rounded-full bg-fond-alterne flex items-center justify-center">
                <svg class="h-8 w-8 text-texte-secondaire" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z" />
                </svg>
            </div>
            <p class="mt-4 font-semibold text-texte">Aucun produit pour le moment</p>
            <p class="mt-1 text-sm text-texte-secondaire">Ajoutez votre premier produit pour commencer à vendre.</p>
            <a href="{{ route('produits.creer') }}"
               class="mt-6 inline-flex items-center justify-center gap-2 px-6 py-3 bg-accent text-white font-semibold text-sm rounded-xl shadow-sm">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Ajouter un produit
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach ($produits as $produit)
                @include('produits._carte', ['produit' => $produit])
            @endforeach
        </div>

        {{-- Bouton + avec animation lift + glow --}}
        <a href="{{ route('produits.creer') }}"
           class="fixed bottom-24 right-4 md:bottom-8 z-20 h-14 w-14 rounded-full bg-accent text-white shadow-lg flex items-center justify-center fab-lift-cycle"
           title="Ajouter un produit">
            <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
        </a>
    @endif
</x-app-layout>
