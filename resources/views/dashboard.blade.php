<x-app-layout :sansDefilement="true">
    <x-slot name="header">Accueil</x-slot>

    @if (! $boutique)
        <div class="glass-solid p-6 text-center">
            <p class="text-texte">Vous n'avez pas encore de boutique.</p>
        </div>
    @else
        <h2 class="text-2xl font-bold text-texte">Bonjour {{ Auth::user()->name }}</h2>

        {{-- Vos gains --}}
        @php
            $maxJour = max(array_values($jours7));
            $joursCles = array_keys($jours7);
        @endphp
        <section class="mt-4 rounded-2xl bg-principale p-6 text-fond shadow-sm">
            <p class="text-sm text-fond/80">Chiffre d'affaires</p>
            <p class="mt-1 text-3xl font-bold">{{ number_format($gainsTotaux, 0, ',', ' ') }} FCFA</p>
            <div class="mt-2 flex items-end justify-between gap-3">
                <p class="text-xs text-fond/70">Commandes confirmées et livrées</p>
                {{-- Mini tendance 7 jours --}}
                <div class="flex items-end gap-1 h-8 shrink-0">
                    @foreach ($joursCles as $i => $jour)
                        @php
                            $valeur = $jours7[$jour];
                            $hauteur = $maxJour > 0 ? ($valeur / $maxJour) * 100 : 0;
                            $estAujourdhui = $i === count($joursCles) - 1;
                        @endphp
                        <div class="w-2 rounded-sm transition-all"
                             style="height: {{ max(4, $hauteur) }}%; opacity: {{ $estAujourdhui ? '1' : '0.5' }}; background: white;"
                             title="{{ \Carbon\Carbon::parse($jour)->format('d/m') }} : {{ number_format($valeur, 0, ',', ' ') }} FCFA">
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- Compteurs --}}
        <div class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-3">
            <a href="{{ route('produits.index') }}" class="glass-solid p-4">
                <p class="text-2xl font-bold text-texte">{{ $boutique->produits()->where('est_disponible', true)->count() }}</p>
                <p class="text-sm text-texte-secondaire">Produits en vente</p>
            </a>
            <a href="{{ route('commandes.index') }}" class="glass-solid p-4">
                <p class="text-2xl font-bold text-texte">{{ $nbCommandesPayees }}</p>
                <p class="text-sm text-texte-secondaire">Confirmées</p>
            </a>
            <div class="glass-solid p-4">
                <p class="text-2xl font-bold text-accent">{{ $boutique->commandes()->where('statut', 'en_attente')->count() }}</p>
                <p class="text-sm text-texte-secondaire">En attente</p>
            </div>
            <div class="glass-solid p-4">
                <p class="text-2xl font-bold text-succes">{{ $meilleureCommande ? number_format($meilleureCommande, 0, ',', ' ') : '0' }}</p>
                <p class="text-sm text-texte-secondaire">Meilleure vente (FCFA)</p>
            </div>
        </div>

        {{-- Actions rapides --}}
        <div class="mt-4 grid grid-cols-2 gap-3">
            <a href="{{ route('produits.creer') }}"
               class="inline-flex items-center justify-center gap-2 px-4 py-3 bg-accent text-white font-semibold text-sm rounded-xl shadow-sm">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Ajouter un produit
            </a>
            <a href="{{ route('boutique-publique.accueil', $boutique) }}" target="_blank"
               class="inline-flex items-center justify-center gap-2 px-4 py-3 border border-principale text-principale font-semibold text-sm rounded-xl">
                Voir ma boutique
            </a>
        </div>

        {{-- Partager la boutique --}}
        <section class="mt-4 glass-solid p-4" x-data="partageLien()">
            <input type="hidden" value="{{ url(route('boutique-publique.accueil', $boutique)) }}">
            <div class="flex items-center justify-between gap-3">
                <div class="min-w-0">
                    <p class="font-semibold text-texte truncate">{{ $boutique->nom }}</p>
                    <p class="text-xs text-texte-secondaire">Partagez votre boutique</p>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <button type="button" @click="copier()"
                            class="inline-flex items-center gap-1.5 px-3 py-2.5 bg-principale text-white font-semibold text-sm rounded-xl"
                            title="Copier le lien">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186l9.566-5.314m-9.566 7.5l9.566 5.314m0 0a2.25 2.25 0 103.935 2.186 2.25 2.25 0 00-3.935-2.186zm0-12.814a2.25 2.25 0 103.933-2.185 2.25 2.25 0 00-3.933 2.185z" />
                        </svg>
                        <span class="hidden sm:inline">Copier</span>
                    </button>
                    <a href="https://wa.me/?text={{ rawurlencode("Découvrez ma boutique {$boutique->nom} !\n" . url(route('boutique-publique.accueil', $boutique))) }}"
                       target="_blank" rel="noopener"
                       class="inline-flex items-center gap-1.5 px-3 py-2.5 bg-[#25D366] text-white font-semibold text-sm rounded-xl"
                       title="Partager sur WhatsApp">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                        <span class="hidden sm:inline">WhatsApp</span>
                    </a>
                </div>
            </div>
            <p class="mt-3 text-sm text-principale glass-subtle rounded-xl px-3 py-2 truncate">{{ url(route('boutique-publique.accueil', $boutique)) }}</p>
        </section>

        {{-- Commandes récentes --}}
        <section class="mt-6" x-data="{ filtreCommande: 'toutes' }">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-semibold text-texte">Commandes récentes</h3>
                <a href="{{ route('commandes.index') }}" class="text-sm text-principale font-medium">Tout voir</a>
            </div>

            {{-- Chips filtre --}}
            <div class="flex gap-2 mb-3">
                <button type="button" @click="filtreCommande = 'toutes'"
                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-full border transition"
                        :class="filtreCommande === 'toutes' ? 'bg-accent text-white border-accent' : 'bg-white text-texte-secondaire border-fond-alterne'">
                    Toutes
                </button>
                <button type="button" @click="filtreCommande = 'en_attente'"
                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-full border transition"
                        :class="filtreCommande === 'en_attente' ? 'bg-accent text-white border-accent' : 'bg-white text-texte-secondaire border-fond-alterne'">
                    En attente
                </button>
                <button type="button" @click="filtreCommande = 'livree'"
                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-full border transition"
                        :class="filtreCommande === 'livree' ? 'bg-accent text-white border-accent' : 'bg-white text-texte-secondaire border-fond-alterne'">
                    Livrées
                </button>
            </div>

            @if ($commandes->isEmpty())
                <div class="glass-solid p-6 text-center">
                    <p class="text-sm text-texte-secondaire">Aucune commande pour le moment.</p>
                </div>
            @else
                <div class="grid gap-3 md:grid-cols-2">
                    @foreach ($commandes as $commande)
                        <a href="{{ route('commandes.montrer', $commande) }}"
                           class="block glass-solid p-4"
                           x-show="filtreCommande === 'toutes' || filtreCommande === '{{ $commande->statut }}'"
                           x-transition>
                            <div class="flex items-center justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="font-semibold text-texte">{{ $commande->reference_courte }}</p>
                                    <p class="text-sm text-texte-secondaire truncate">
                                        {{ $commande->client_nom }} · {{ $commande->client_telephone }}
                                    </p>
                                </div>
                                <div class="text-right shrink-0">
                                    <p class="font-semibold text-texte">{{ number_format($commande->montant_produit, 0, ',', ' ') }} FCFA</p>
                                    <x-statut-commande :statut="$commande->statut" />
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </section>
    @endif
</x-app-layout>
