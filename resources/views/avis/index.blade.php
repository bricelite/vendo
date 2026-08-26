<x-app-layout>
    <x-slot name="header">Avis des clients</x-slot>

    @if ($avis->isEmpty())
        <div class="glass-solid p-10 text-center">
            <div class="mx-auto h-16 w-16 rounded-full bg-fond-alterne flex items-center justify-center">
                <svg class="h-8 w-8 text-texte-secondaire" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.017 18L14.017 18.001M14.017 18v-.017A9.98 9.98 0 0112 18c-2.456 0-4.688-.884-6.417-2.349l-3.53.884 1.577-2.55A9.95 9.95 0 012.25 9.75c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75z" />
                </svg>
            </div>
            <p class="mt-4 font-semibold text-texte">Aucun avis pour le moment</p>
            <p class="mt-1 text-sm text-texte-secondaire">Quand vos clients donnent leur avis, ils apparaissent ici.</p>
        </div>
    @else
        <div class="glass-solid p-5 flex items-center justify-between gap-3">
            <div>
                <p class="text-3xl font-bold text-texte">{{ $noteMoyenne ? number_format($noteMoyenne, 1) : '—' }}</p>
                <div class="mt-1 flex gap-0.5">
                    @for ($i = 1; $i <= 5; $i++)
                        <svg class="h-5 w-5 {{ $i <= round($noteMoyenne) ? 'text-accent' : 'text-fond-alterne' }}"
                             viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z" clip-rule="evenodd" />
                        </svg>
                    @endfor
                </div>
            </div>
            <p class="text-sm text-texte-secondaire">{{ $avis->count() }} avis</p>
        </div>

        <div class="mt-4 space-y-3">
            @foreach ($avis as $avisItem)
                <div class="glass-solid p-4">
                    <div class="flex items-center justify-between gap-3">
                        <div class="min-w-0">
                            <p class="font-medium text-texte truncate">{{ $avisItem->produit->nom }}</p>
                            <p class="text-xs text-texte-secondaire">{{ $avisItem->client_nom }} · {{ $avisItem->created_at->translatedFormat('d M Y') }}</p>
                        </div>
                        <div class="flex gap-0.5 shrink-0">
                            @for ($i = 1; $i <= 5; $i++)
                                <svg class="h-4 w-4 {{ $i <= $avisItem->note ? 'text-accent' : 'text-fond-alterne' }}"
                                     viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z" clip-rule="evenodd" />
                                </svg>
                            @endfor
                        </div>
                    </div>
                    @if ($avisItem->commentaire)
                        <p class="mt-2 text-sm text-texte">{{ $avisItem->commentaire }}</p>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</x-app-layout>
