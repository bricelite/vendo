<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg text-texte leading-tight">
            Administration Vendo
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
            <div class="glass-solid rounded-xl shadow p-4">
                <p class="text-sm text-texte-secondaire">Boutiques</p>
                <p class="text-2xl font-bold text-texte">{{ $boutiques->count() }}</p>
            </div>
            <div class="glass-solid rounded-xl shadow p-4">
                <p class="text-sm text-texte-secondaire">Commandes en attente</p>
                <p class="text-2xl font-bold text-texte">{{ $commandesEnAttente }}</p>
            </div>
        </div>

        <div class="glass-solid rounded-xl shadow p-6">
            <h3 class="text-lg font-semibold text-texte mb-4">Les boutiques</h3>

            @if ($boutiques->isEmpty())
                <p class="text-sm text-texte-secondaire">Aucune boutique pour le moment.</p>
            @else
                <div class="space-y-3">
                    @foreach ($boutiques as $boutique)
                        <div class="flex items-center justify-between border-b border-fond-alterne py-3">
                            <div>
                                <p class="font-medium text-texte">{{ $boutique->nom }}</p>
                                <p class="text-sm text-texte-secondaire">
                                    {{ $boutique->vendeur->name }} · {{ $boutique->vendeur->telephone }}
                                </p>
                            </div>
                            <div class="text-right text-sm text-texte-secondaire">
                                <p>{{ $boutique->produits_count }} produits</p>
                                <p>{{ $boutique->commandes_count }} commandes</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
