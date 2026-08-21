<x-app-layout>
    <x-slot name="header">Mon compte</x-slot>

    {{-- Onglets boutique / compte --}}
    <div class="flex gap-2 mb-4">
        <a href="{{ route('profile.boutique') }}"
           class="px-4 py-2 rounded-full text-sm font-medium bg-fond text-texte-secondaire border border-fond-alterne">Ma boutique</a>
        <a href="{{ route('profile.edit') }}"
           class="px-4 py-2 rounded-full text-sm font-semibold bg-principale text-white">Mon compte</a>
    </div>

    {{-- Mes informations --}}
    <div class="bg-fond rounded-2xl p-5 shadow-sm">
        @include('profile.partials.update-profile-information-form')
    </div>

    {{-- Mon mot de passe --}}
    <div class="mt-4 bg-fond rounded-2xl p-5 shadow-sm">
        @include('profile.partials.update-password-form')
    </div>

    {{-- Supprimer mon compte --}}
    <div class="mt-4 bg-fond rounded-2xl p-5 shadow-sm">
        @include('profile.partials.delete-user-form')
    </div>
</x-app-layout>
