<x-app-layout>
    <x-slot name="header">Modifier le produit</x-slot>

    @include('produits._form', [
        'action' => route('produits.update', $produit),
        'methode' => 'PATCH',
        'produit' => $produit,
        'boutique' => $boutique,
        'bouton' => 'Enregistrer les modifications',
    ])
</x-app-layout>
