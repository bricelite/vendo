@php
    $produit = $produit ?? null;
    $estDisponible = $estDisponible ?? true;
    $imageActuelle = $produit && $produit->image_url ? url('/uploads/'.$produit->image_url) : '';
@endphp

<form method="POST" action="{{ $action }}" enctype="multipart/form-data"
      x-data="{ envoiEnCours: false, promoActive: {{ $produit && $produit->prix_promo ? 'true' : 'false' }} }">
    @csrf
    @method($methode)

    {{-- Photo du produit --}}
    <div class="bg-fond rounded-2xl p-5 shadow-sm">
        <div x-data="apercuPhoto('{{ $imageActuelle }}')" class="flex flex-col items-center">
            <template x-if="apercu">
                <img :src="apercu" alt="" class="h-44 w-44 object-cover rounded-xl border border-fond-alterne">
            </template>
            <template x-if="!apercu">
                <div class="h-44 w-44 rounded-xl border-2 border-dashed border-fond-alterne flex items-center justify-center bg-fond-alterne/40">
                    <svg class="h-10 w-10 text-texte-secondaire" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>
                </div>
            </template>

            <label class="mt-3 cursor-pointer inline-flex items-center gap-2 px-4 py-2.5 border border-principale text-principale text-sm font-semibold rounded-lg">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0z" />
                </svg>
                <span x-text="apercu ? 'Changer la photo' : 'Ajouter une photo'"></span>
                <input type="file" name="image" accept="image/jpeg,image/png,image/webp" class="hidden" @change="compresse($event)">
            </label>
            <p class="mt-2 text-xs text-texte-secondaire">La photo est réduite automatiquement pour charger vite sur téléphone.</p>
        </div>
        <x-input-error :messages="$errors->get('image')" class="mt-2" />
    </div>

    {{-- Informations --}}
    <div class="mt-4 bg-fond rounded-2xl p-5 shadow-sm space-y-4">
        <div>
            <x-input-label for="produit_nom" value="Nom du produit" />
            <x-text-input id="produit_nom" class="block mt-1 w-full" type="text" name="nom"
                          :value="old('nom', $produit?->nom)" required maxlength="150"
                          placeholder="Ex. : Robe en pagne" />
            <x-input-error :messages="$errors->get('nom')" class="mt-2" />
        </div>

        @if ($boutique->categories->isNotEmpty())
            <div x-data="{
                envoiCategorie: false,
                categoriesJson: @js($boutique->categories->map(fn ($c) => ['id' => $c->id, 'nom' => $c->nom])->values()->all()),
                async suggererCategorie() {
                    var nomInput = document.getElementById('produit_nom');
                    var descInput = document.getElementById('produit_description');
                    var nom = nomInput ? nomInput.value.trim() : '';
                    var description = descInput ? descInput.value.trim() : '';

                    if (!nom) {
                        vendoToast('Entrez d\\'abord le nom du produit', 'erreur');
                        nomInput && nomInput.focus();
                        return;
                    }
                    if (this.categoriesJson.length === 0) {
                        vendoToast('Ajoutez d\\'abord des catégories à votre boutique', 'erreur');
                        return;
                    }

                    this.envoiCategorie = true;
                    try {
                        var token = document.querySelector('meta[name=csrf-token]').getAttribute('content');
                        var reponse = await fetch('{{ route('description-ia.categorie') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                nom_produit: nom,
                                description: description,
                                categories: this.categoriesJson
                            })
                        });

                        var donnees = await reponse.json();

                        if (!reponse.ok) {
                            vendoToast(donnees.erreur || 'Erreur lors de la suggestion', 'erreur');
                            return;
                        }

                        if (donnees.categorie_id) {
                            var select = document.getElementById('produit_categorie');
                            if (select) {
                                select.value = donnees.categorie_id;
                                select.dispatchEvent(new Event('change'));
                            }
                            var catTrouvée = this.categoriesJson.find(c => c.id === donnees.categorie_id);
                            vendoToast('Catégorie suggérée : ' + (catTrouvée ? catTrouvée.nom : 'trouvée'));
                        } else {
                            vendoToast('Aucune catégorie ne correspond à ce produit', 'erreur');
                        }
                    } catch (e) {
                        vendoToast('Erreur de connexion. Vérifiez internet.', 'erreur');
                    } finally {
                        this.envoiCategorie = false;
                    }
                }
            }">
                <div class="flex items-center justify-between">
                    <x-input-label for="produit_categorie" value="Catégorie" />
                    <button type="button" @click="suggererCategorie()" :disabled="envoiCategorie"
                            class="inline-flex items-center gap-1.5 text-xs font-medium text-accent hover:text-accent/80 transition disabled:opacity-50">
                        <template x-if="!envoiCategorie">
                            <span class="inline-flex items-center gap-1">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" />
                                </svg>
                                Suggérer
                            </span>
                        </template>
                        <template x-if="envoiCategorie">
                            <span class="inline-flex items-center gap-1">
                                <span class="h-3 w-3 border-2 border-accent/40 border-t-accent rounded-full animate-spin"></span>
                                Recherche…
                            </span>
                        </template>
                    </button>
                </div>
                <select id="produit_categorie" name="categorie_id"
                        class="mt-1 block w-full rounded-lg border-fond-alterne text-texte text-sm focus:border-principale focus:ring-principale/30">
                    <option value="">— Aucune catégorie —</option>
                    @foreach ($boutique->categories as $cat)
                        <option value="{{ $cat->id }}" @selected(old('categorie_id', $produit?->categorie_id) == $cat->id)>{{ $cat->nom }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('categorie_id')" class="mt-2" />
            </div>
        @endif

        <div>
            <x-input-label for="produit_prix" value="Prix en FCFA" />
            <x-text-input id="produit_prix" class="block mt-1 w-full" type="number" min="1" name="prix"
                          :value="old('prix', $produit?->prix)" required placeholder="Ex. : 5000" />
            <x-input-error :messages="$errors->get('prix')" class="mt-2" />
        </div>

        <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" x-model="promoActive" class="h-5 w-5 rounded-md border-fond-alterne text-accent focus:ring-accent">
            <span class="text-sm font-medium text-texte">Mettre ce produit en promo</span>
        </label>

        <div x-show="promoActive" x-transition.opacity>
            <x-input-label for="produit_prix_promo" value="Prix en promo (FCFA)" />
            <x-text-input id="produit_prix_promo" class="block mt-1 w-full" type="number" min="1" name="prix_promo"
                          :value="old('prix_promo', $produit?->prix_promo)" placeholder="Moins cher que le prix normal" />
            <x-input-error :messages="$errors->get('prix_promo')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="produit_stock" value="Combien en avez-vous en stock ?" />
            <x-text-input id="produit_stock" class="block mt-1 w-full" type="number" min="0" name="stock_quantite"
                          :value="old('stock_quantite', $produit?->stock_quantite)" required placeholder="Ex. : 10" />
            <x-input-error :messages="$errors->get('stock_quantite')" class="mt-2" />
        </div>

        {{-- Description + IA --}}
        <div x-data="{
            modeLangage: 'beninois',
            envoiIa: false,
            async genererDescription() {
                var nomInput = document.getElementById('produit_nom');
                var prixInput = document.getElementById('produit_prix');
                var nom = nomInput ? nomInput.value.trim() : '';
                var prix = prixInput ? prixInput.value.trim() : '';

                if (!nom) {
                    vendoToast('Entrez d\\'abord le nom du produit', 'erreur');
                    nomInput && nomInput.focus();
                    return;
                }
                if (!prix) {
                    vendoToast('Entrez d\\'abord le prix', 'erreur');
                    prixInput && prixInput.focus();
                    return;
                }

                this.envoiIa = true;
                try {
                    var token = document.querySelector('meta[name=csrf-token]').getAttribute('content');
                    var reponse = await fetch('{{ route('description-ia.generer') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            nom_produit: nom,
                            prix: prix,
                            mode_langage: this.modeLangage
                        })
                    });

                    var donnees = await reponse.json();

                    if (!reponse.ok) {
                        vendoToast(donnees.erreur || 'Erreur lors de la génération', 'erreur');
                        return;
                    }

                    var textarea = document.getElementById('produit_description');
                    if (textarea) {
                        textarea.value = donnees.description;
                        textarea.dispatchEvent(new Event('input'));
                    }
                    vendoToast('Description générée !');
                } catch (e) {
                    vendoToast('Erreur de connexion. Vérifiez internet.', 'erreur');
                } finally {
                    this.envoiIa = false;
                }
            }
        }">
            <div class="flex items-center justify-between">
                <x-input-label for="produit_description" value="Description" />
            </div>

            <textarea id="produit_description" name="description" rows="3"
                      class="mt-1 block w-full rounded-lg border-gray-300 text-texte"
                      placeholder="Taille, couleur, matière…">{{ old('description', $produit?->description) }}</textarea>
            <x-input-error :messages="$errors->get('description')" class="mt-2" />

            {{-- Bloc IA --}}
            <div class="mt-3 p-3 bg-fond-alterne rounded-xl">
                <p class="text-xs font-medium text-texte-secondaire mb-2">Générer une description avec l'IA</p>

                <div class="flex flex-wrap gap-2 mb-3">
                    <label class="cursor-pointer">
                        <input type="radio" name="mode_langage_ia" value="beninois" x-model="modeLangage" class="sr-only peer">
                        <span class="inline-block px-3 py-1.5 text-xs font-medium rounded-full border transition
                                     peer-checked:bg-principale peer-checked:text-white peer-checked:border-principale
                                     bg-fond text-texte-secondaire border-fond-alterne hover:border-principale/50">
                            Béninois
                        </span>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="mode_langage_ia" value="decontracte" x-model="modeLangage" class="sr-only peer">
                        <span class="inline-block px-3 py-1.5 text-xs font-medium rounded-full border transition
                                     peer-checked:bg-principale peer-checked:text-white peer-checked:border-principale
                                     bg-fond text-texte-secondaire border-fond-alterne hover:border-principale/50">
                            Décontracté
                        </span>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="mode_langage_ia" value="standard" x-model="modeLangage" class="sr-only peer">
                        <span class="inline-block px-3 py-1.5 text-xs font-medium rounded-full border transition
                                     peer-checked:bg-principale peer-checked:text-white peer-checked:border-principale
                                     bg-fond text-texte-secondaire border-fond-alterne hover:border-principale/50">
                            Standard
                        </span>
                    </label>
                </div>

                <button type="button" @click="genererDescription()" :disabled="envoiIa"
                        class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-accent text-white font-semibold text-sm rounded-lg transition disabled:opacity-50">
                    <template x-if="!envoiIa">
                        <span class="inline-flex items-center gap-2">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 00-2.455 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z" />
                            </svg>
                            Générer
                        </span>
                    </template>
                    <template x-if="envoiIa">
                        <span class="inline-flex items-center gap-2">
                            <span class="h-4 w-4 border-2 border-white/40 border-t-white rounded-full animate-spin"></span>
                            Génération…
                        </span>
                    </template>
                </button>
            </div>
        </div>

        <label class="flex items-center justify-between cursor-pointer">
            <span class="text-sm font-medium text-texte">Disponible à la vente</span>
            <input type="checkbox" name="est_disponible" value="1"
                   @checked($estDisponible)
                   class="h-6 w-6 rounded-md border-fond-alterne text-accent focus:ring-accent">
        </label>

        <label class="flex items-center justify-between cursor-pointer">
            <div>
                <span class="text-sm font-medium text-alerte">En solde</span>
                <p class="text-xs text-texte-secondaire">Affiche un badge "SOLDE" sur votre produit</p>
            </div>
            <input type="checkbox" name="est_en_solde" value="1"
                   @checked(old('est_en_solde', $produit?->est_en_solde))
                   class="h-6 w-6 rounded-md border-fond-alterne text-alerte focus:ring-alerte">
        </label>
    </div>

    <div class="mt-4 flex flex-col gap-3">
        <button type="submit" :disabled="envoiEnCours" :class="envoiEnCours ? 'opacity-60' : ''"
                class="w-full inline-flex items-center justify-center gap-2 px-4 py-3.5 bg-accent text-white font-semibold text-sm rounded-lg shadow-sm">
            <span x-show="envoiEnCours" x-cloak class="h-4 w-4 border-2 border-white/40 border-t-white rounded-full animate-spin"></span>
            {{ $bouton }}
        </button>
        <a href="{{ route('produits.index') }}"
           class="w-full inline-flex items-center justify-center px-4 py-3 border border-principale text-principale font-semibold text-sm rounded-lg">
            Annuler
        </a>
    </div>
</form>
