@props(['statut'])

@php
    $config = [
        'en_attente' => ['En attente', 'bg-avertissement text-white'],
        'confirmee' => ['Confirmée', 'bg-succes text-white'],
        'livree' => ['Livrée', 'bg-succes text-white'],
        'retiree' => ['Retirée', 'bg-succes text-white'],
        'annulee' => ['Annulée', 'bg-alerte text-white'],
    ][$statut] ?? [$statut, 'bg-fond-alterne text-texte-secondaire'];
@endphp

<span data-statut-badge="{{ $statut }}"
      class="inline-block text-xs font-medium px-2.5 py-1 rounded-full {{ $config[1] }}">
    {{ $config[0] }}
</span>
