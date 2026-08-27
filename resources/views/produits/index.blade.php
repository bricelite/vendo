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
        <div x-data="{
            recherche: '',
            filtre: 'tous',
            get filtres() {
                return this.$root.querySelectorAll('[data-produit-carte]');
            }
        }" x-init="$watch('recherche', () => {
            $nextTick(() => {
                this.filtres.forEach(carte => {
                    const nom = carte.querySelector('[data-produit-nom]');
                    const texte = nom ? nom.textContent.toLowerCase() : '';
                    carte.style.display = texte.includes(this.recherche.toLowerCase()) ? '' : 'none';
                });
            });
        }); $watch('filtre', () => {
            $nextTick(() => {
                this.filtres.forEach(carte => {
                    const statut = carte.getAttribute('data-produit-statut');
                    const visible = this.filtre === 'tous' || statut === this.filtre;
                    carte.style.display = visible ? '' : 'none';
                });
            });
        })">

            {{-- Barre de recherche --}}
            <div class="relative mb-3">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-texte-secondaire" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </div>
                <input type="text" x-model="recherche" placeholder="Rechercher un produit..."
                       class="block w-full pl-10 pr-4 py-2.5 rounded-xl border-fond-alterne text-texte text-sm placeholder:text-texte-secondaire/60 focus:border-principale focus:ring-principale/30">
            </div>

            {{-- Chips filtre --}}
            <div class="flex gap-2 mb-3">
                <button type="button" @click="filtre = 'tous'"
                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-full border transition"
                        :class="filtre === 'tous' ? 'bg-accent text-white border-accent' : 'bg-white text-texte-secondaire border-fond-alterne'">
                    Tous
                </button>
                <button type="button" @click="filtre = 'en_stock'"
                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-full border transition"
                        :class="filtre === 'en_stock' ? 'bg-accent text-white border-accent' : 'bg-white text-texte-secondaire border-fond-alterne'">
                    En stock
                </button>
                <button type="button" @click="filtre = 'rupture'"
                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-full border transition"
                        :class="filtre === 'rupture' ? 'bg-accent text-white border-accent' : 'bg-white text-texte-secondaire border-fond-alterne'">
                    Rupture
                </button>
                <button type="button" @click="filtre = 'promo'"
                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-full border transition"
                        :class="filtre === 'promo' ? 'bg-accent text-white border-accent' : 'bg-white text-texte-secondaire border-fond-alterne'">
                    Promo
                </button>
            </div>

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
        </div>
    @endif
</x-app-layout>
