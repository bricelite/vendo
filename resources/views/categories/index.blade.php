<x-app-layout>
    <x-slot name="header">Catégories</x-slot>

    <div class="glass-solid p-5">
        <p class="text-sm text-texte-secondaire mb-4">Organisez vos produits par catégorie pour aider vos clients à trouver ce qu'ils cherchent.</p>

        {{-- Formulaire d'ajout --}}
        <form method="POST" action="{{ route('categories.store') }}" class="flex items-end gap-3">
            @csrf
            <div class="flex-1">
                <x-input-label for="nom" value="Nouvelle catégorie" />
                <x-text-input id="nom" name="nom" type="text" class="mt-1 block w-full"
                              value="{{ old('nom') }}" maxlength="100" required
                              placeholder="Ex. : Vêtements, Chaussures..." />
                <x-input-error class="mt-2" :messages="$errors->get('nom')" />
            </div>
            <x-primary-button class="w-auto shrink-0 h-[42px]">Ajouter</x-primary-button>
        </form>
    </div>

    {{-- Liste des catégories --}}
    @if ($categories->isEmpty())
        <div class="mt-4 glass-solid p-10 text-center">
            <p class="text-texte-secondaire">Aucune catégorie. Ajoutez-en une ci-dessus.</p>
        </div>
    @else
        <div class="mt-4 space-y-2">
            @foreach ($categories as $categorie)
                <div class="glass-solid p-4 flex items-center justify-between gap-3"
                     x-data="{ edition: false }">
                    <div x-show="!edition" class="flex items-center gap-3 min-w-0">
                        <div class="h-10 w-10 rounded-full bg-principale/10 flex items-center justify-center text-principale font-semibold text-sm shrink-0">
                            {{ mb_substr($categorie->nom, 0, 1) }}
                        </div>
                        <div class="min-w-0">
                            <p class="font-medium text-texte truncate">{{ $categorie->nom }}</p>
                            <p class="text-xs text-texte-secondaire">{{ $categorie->produits_count }} produit{{ $categorie->produits_count > 1 ? 's' : '' }}</p>
                        </div>
                    </div>

                    <div x-show="edition" class="flex-1">
                        <form method="POST" action="{{ route('categories.update', $categorie) }}" class="flex items-center gap-2">
                            @csrf
                            @method('PATCH')
                            <input type="text" name="nom" value="{{ $categorie->nom }}" maxlength="100" required
                                   class="flex-1 rounded-xl border-fond-alterne text-sm text-texte focus:border-principale focus:ring-principale/30">
                            <button type="submit" class="text-succes text-sm font-medium shrink-0">OK</button>
                            <button type="button" @click="edition = false" class="text-texte-secondaire text-sm shrink-0">Annuler</button>
                        </form>
                    </div>

                    <div x-show="!edition" class="flex items-center gap-1 shrink-0">
                        <button type="button" @click="edition = true"
                                class="inline-flex items-center justify-center h-9 w-9 rounded-full text-texte-secondaire hover:text-principale hover:bg-fond-alterne">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                            </svg>
                        </button>
                        <form method="POST" action="{{ route('categories.destroy', $categorie) }}"
                              x-data="{ confirmation: false }" @submit="if(!confirmation){event.preventDefault();confirmation=true;}">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="inline-flex items-center justify-center h-9 w-9 rounded-full text-texte-secondaire hover:text-alerte hover:bg-fond-alterne"
                                    :title="confirmation ? 'Confirmer la suppression' : 'Supprimer'"
                                    @click="if(confirmation){return true;} event.preventDefault(); confirmation=true; setTimeout(()=>confirmation=false, 3000)">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</x-app-layout>
