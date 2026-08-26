<x-app-layout>
    <x-slot name="header">{{ $commande->reference_courte }}</x-slot>

    {{-- Statut et date --}}
    <div class="glass-solid p-5 flex items-center justify-between gap-3">
        <div>
            <p class="text-sm text-texte-secondaire">Commande du {{ $commande->created_at->translatedFormat('d M Y · H:i') }}</p>
            <p class="mt-1 text-lg font-bold text-texte">{{ number_format($commande->montant_produit, 0, ',', ' ') }} FCFA</p>
        </div>
        <x-statut-commande :statut="$commande->statut" />
    </div>

    {{-- Client --}}
    <div class="mt-4 glass-solid p-5">
        <h3 class="text-sm font-semibold text-texte uppercase tracking-wide">Client</h3>
        <p class="mt-2 font-medium text-texte">{{ $commande->client_nom }}</p>
        <a href="tel:{{ $commande->client_telephone }}"
           class="mt-1 inline-flex items-center gap-1.5 text-principale font-medium text-sm">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
            </svg>
            {{ $commande->client_telephone }}
        </a>
        @if ($commande->client_localite)
            <p class="mt-1 text-sm text-texte-secondaire">{{ $commande->client_localite }}</p>
        @endif
    </div>

    {{-- Articles --}}
    <div class="mt-4 glass-solid p-5">
        <h3 class="text-sm font-semibold text-texte uppercase tracking-wide">Articles</h3>
        <div class="mt-2 divide-y divide-fond-alterne">
            @foreach ($commande->lignes as $ligne)
                <div class="py-3 flex items-center justify-between gap-3">
                    <div class="min-w-0">
                        <p class="font-medium text-texte truncate">{{ $ligne->nom_produit }}</p>
                        <p class="text-sm text-texte-secondaire">{{ $ligne->quantite }} × {{ number_format($ligne->prix_unitaire, 0, ',', ' ') }} FCFA</p>
                    </div>
                    <p class="font-semibold text-texte shrink-0">{{ number_format($ligne->prix_unitaire * $ligne->quantite, 0, ',', ' ') }} FCFA</p>
                </div>
            @endforeach
        </div>
        <div class="mt-2 pt-3 border-t border-fond-alterne flex items-center justify-between">
            <p class="font-medium text-texte">Total</p>
            <p class="font-bold text-texte">{{ number_format($commande->montant_produit, 0, ',', ' ') }} FCFA</p>
        </div>
    </div>

    {{-- Retrait en boutique --}}
    @if ($commande->mode_retrait === 'boutique' && $commande->statut === 'confirmee' && $commande->statut_retrait === 'en_attente')
        <div class="mt-4 bg-avertissement/10 border border-avertissement/20 rounded-2xl p-5 shadow-sm"
             x-data="{ code: '', sending: false }">
            <div class="flex items-start gap-3">
                <div class="mt-0.5 shrink-0 w-8 h-8 rounded-full bg-avertissement text-white flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold text-texte">Retrait en boutique</h3>
                    <p class="mt-1 text-sm text-texte-secondaire">Demandez le code à 6 chiffres au client, puis validez :</p>
                    <form method="POST" action="{{ route('commandes.retrait', $commande) }}"
                          data-ajax data-action="retrait" class="mt-3 flex gap-2"
                          x-on:submit="sending = true">
                        @csrf
                        <input type="text" name="code_retrait" maxlength="6" pattern="[0-9]{6}"
                               x-model="code"
                               placeholder="_ _ _ _ _ _"
                               required inputmode="numeric" autocomplete="one-time-code"
                               class="flex-1 rounded-xl border-fond-alterne text-center text-lg tracking-[0.4em] font-mono text-texte focus:border-avertissement focus:ring-avertissement/30">
                        <button type="submit"
                                :disabled="sending || code.length !== 6"
                                class="shrink-0 inline-flex items-center gap-1.5 px-5 py-2.5 bg-avertissement text-white font-semibold text-sm rounded-xl disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg x-show="sending" class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            Valider
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- Actions selon le statut --}}
    <div class="mt-4 flex flex-col gap-3" data-actions-statut>
        <form method="POST" action="{{ route('commandes.statut', $commande) }}" data-ajax data-action="statut"
              class="{{ $commande->statut === 'en_attente' ? '' : 'hidden' }}">
            @csrf
            @method('PATCH')
            <input type="hidden" name="statut" value="confirmee">
            <button type="submit" data-statut-pour="en_attente"
                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-3.5 bg-succes text-white font-semibold text-sm rounded-xl shadow-sm">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
                Confirmer la commande
            </button>
        </form>

        <form method="POST" action="{{ route('commandes.statut', $commande) }}" data-ajax data-action="statut"
              class="{{ in_array($commande->statut, ['en_attente', 'confirmee'], true) ? '' : 'hidden' }}">
            @csrf
            @method('PATCH')
            <input type="hidden" name="statut" value="annulee">
            <button type="submit" data-statut-pour="en_attente,confirmee"
                    onclick="return confirm('Annuler cette commande ? Le stock sera remis en place.')"
                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-3.5 border border-alerte text-alerte font-semibold text-sm rounded-xl">
                Annuler la commande
            </button>
        </form>

        <form method="POST" action="{{ route('commandes.statut', $commande) }}" data-ajax data-action="statut"
              class="{{ $commande->statut === 'confirmee' ? '' : 'hidden' }}">
            @csrf
            @method('PATCH')
            <input type="hidden" name="statut" value="livree">
            <button type="submit" data-statut-pour="confirmee"
                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-3.5 bg-succes text-white font-semibold text-sm rounded-xl shadow-sm">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                </svg>
                Marquer comme livrée
            </button>
        </form>
    </div>
</x-app-layout>
