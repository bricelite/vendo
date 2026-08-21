<x-app-layout>
    <x-slot name="header">Ajouter un produit</x-slot>

    @include('produits._form', [
        'action' => route('produits.store'),
        'methode' => 'POST',
        'produit' => null,
        'boutique' => $boutique,
        'bouton' => 'Ajouter le produit',
    ])
</x-app-layout>
