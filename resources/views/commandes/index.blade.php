<x-app-layout>
    <x-slot name="header">Mes commandes</x-slot>

    @php
        $filtres = [
            '' => 'Toutes',
            'en_attente' => 'En attente',
            'confirmee' => 'Confirmées',
            'livree' => 'Livrées',
            'annulee' => 'Annulées',
        ];
    @endphp

    <div class="flex gap-2 overflow-x-auto pb-1 -mx-4 px-4 md:mx-0 md:px-0 md:flex-wrap">
        @foreach ($filtres as $valeur => $libelle)
            <a href="{{ route('commandes.index', $valeur ? ['statut' => $valeur] : []) }}"
               class="shrink-0 px-4 py-2 rounded-full text-sm font-medium border
                      {{ ($statut ?? '') === $valeur
                            ? 'bg-principale text-white border-principale'
                            : 'bg-fond text-texte-secondaire border-fond-alterne' }}">
                {{ $libelle }}
            </a>
        @endforeach
    </div>

    @if ($commandes->isEmpty())
        <div class="mt-6 glass-solid p-10 text-center">
            <p class="font-semibold text-texte">Aucune commande</p>
            <p class="mt-1 text-sm text-texte-secondaire">Les commandes des clients apparaîtront ici.</p>
        </div>
    @else
        <div class="mt-4 grid gap-3 md:grid-cols-2">
            @foreach ($commandes as $commande)
                <a href="{{ route('commandes.montrer', $commande) }}"
                   class="block glass-solid p-4">
                    <div class="flex items-center justify-between gap-3">
                        <div class="min-w-0">
                            <p class="font-semibold text-texte">{{ $commande->reference_courte }}</p>
                            <p class="text-sm text-texte-secondaire truncate">
                                {{ $commande->client_nom }} · {{ $commande->client_telephone }}
                            </p>
                            <p class="text-xs text-texte-secondaire mt-0.5">
                                {{ $commande->created_at->translatedFormat('d M · H:i') }}
                            </p>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="font-semibold text-texte">{{ number_format($commande->montant_produit, 0, ',', ' ') }} FCFA</p>
                            <div class="mt-1"><x-statut-commande :statut="$commande->statut" /></div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</x-app-layout>
