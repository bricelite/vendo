import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

/**
 * Panier partagé entre toutes les pages d'une même boutique.
 * Stocké dans le navigateur du client, avec une clé PAR BOUTIQUE :
 * les articles d'une boutique ne se mélangent jamais avec ceux d'une autre.
 * L'id de la boutique courante est posé sur <body data-boutique-id> par le layout.
 */
document.addEventListener('alpine:init', () => {
    const clePanier = 'vendo_panier_' + (document.body.dataset.boutiqueId || '0');

    Alpine.store('panier', {
        articles: JSON.parse(localStorage.getItem(clePanier) || '[]'),
        modeLivraison: localStorage.getItem(clePanier + '_mode') || 'livraison',

        sauvegarder() {
            localStorage.setItem(clePanier, JSON.stringify(this.articles));
        },

        setModeLivraison(mode) {
            this.modeLivraison = mode;
            localStorage.setItem(clePanier + '_mode', mode);
        },

        nombreArticles() {
            return this.articles.reduce((total, article) => total + article.quantite, 0);
        },

        montantTotal() {
            return this.articles.reduce((total, article) => total + article.prix * article.quantite, 0);
        },

        ajouter(produitId, nom, prix, quantite, stock, imageUrl, produitSlug) {
            const existant = this.articles.find((article) => article.produit_id === produitId);

            if (existant) {
                const nouvelleQuantite = Math.min(existant.quantite + quantite, stock);
                existant.quantite = nouvelleQuantite;
            } else {
                this.articles.push({
                    produit_id: produitId,
                    nom,
                    prix,
                    quantite: Math.min(quantite, stock),
                    image_url: imageUrl || null,
                    slug: produitSlug || null,
                });
            }

            this.sauvegarder();
        },

        retirer(produitId) {
            this.articles = this.articles.filter((article) => article.produit_id !== produitId);
            this.sauvegarder();
        },

        changerQuantite(produitId, quantite, stock) {
            const article = this.articles.find((item) => item.produit_id === produitId);

            if (!article) {
                return;
            }

            article.quantite = Math.max(1, Math.min(quantite, stock));
            this.sauvegarder();
        },

        vider() {
            this.articles = [];
            this.sauvegarder();
        },
    });

    /**
     * Aperçu d'une photo de produit : compression avant envoi, puis affichage.
     */
    Alpine.data('apercuPhoto', (imageExistante) => ({
        apercu: imageExistante || '',

        async compresse(event) {
            const entree = event.target;
            const fichier = entree.files && entree.files[0];

            if (!fichier) {
                return;
            }

            try {
                const compresse = await comprimerImage(fichier);
                const transfert = new DataTransfer();
                transfert.items.add(compresse);
                entree.files = transfert.files;
                this.apercu = URL.createObjectURL(compresse);
            } catch {
                // Si la compression échoue (photo énorme…), on envoie l'original.
                this.apercu = URL.createObjectURL(fichier);
            }
        },
    }));

    /**
     * Copie le lien de la boutique dans le presse-papiers du vendeur.
     */
    Alpine.data('partageLien', () => ({
        copier() {
            const champ = this.$root.querySelector('input[type=hidden]');
            const lien = champ ? champ.value : '';

            if (!lien) {
                return;
            }

            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(lien)
                    .then(() => vendoToast('Lien copié !'))
                    .catch(() => vendoToast('Impossible de copier le lien', 'erreur'));
            } else {
                // Sur certains téléphones : copie via une zone de sélection temporaire
                const temporaire = document.createElement('textarea');
                temporaire.value = lien;
                document.body.appendChild(temporaire);
                temporaire.select();
                document.execCommand('copy');
                temporaire.remove();
                vendoToast('Lien copié !');
            }
        },
    }));
});

/**
 * Réduit une photo (prise avec un téléphone) avant son envoi au serveur.
 * Objectif : quelques centaines de Ko maximum, pour rester utilisable en 3G.
 */
async function comprimerImage(fichier, largeurMax = 1200, qualite = 0.8) {
    const image = await new Promise((resolve, reject) => {
        const imageElement = new Image();
        const url = URL.createObjectURL(fichier);

        imageElement.onload = () => {
            URL.revokeObjectURL(url);
            resolve(imageElement);
        };
        imageElement.onerror = () => {
            URL.revokeObjectURL(url);
            reject(new Error('Impossible de lire la photo'));
        };

        imageElement.src = url;
    });

    let largeur = image.width;
    let hauteur = image.height;

    if (largeur > largeurMax) {
        hauteur = Math.round(hauteur * (largeurMax / largeur));
        largeur = largeurMax;
    }

    const canvas = document.createElement('canvas');
    canvas.width = largeur;
    canvas.height = hauteur;
    canvas.getContext('2d').drawImage(image, 0, 0, largeur, hauteur);

    return new Promise((resolve, reject) => {
        canvas.toBlob((blob) => {
            if (!blob) {
                reject(new Error('Compression impossible'));
                return;
            }

            resolve(new File([blob], 'photo-produit.jpg', { type: 'image/jpeg' }));
        }, 'image/jpeg', qualite);
    });
}

/**
 * Petite notification en bas de l'écran (message court, type succès ou erreur).
 */
window.vendoToast = function (message, type = 'succes') {
    const conteneur = document.getElementById('vendo-toasts');

    if (!conteneur) {
        return;
    }

    const toast = document.createElement('div');
    toast.className = type === 'erreur' ? 'vendo-toast vendo-toast-erreur' : 'vendo-toast';
    toast.textContent = message;
    conteneur.appendChild(toast);

    requestAnimationFrame(() => toast.classList.add('visible'));

    setTimeout(() => {
        toast.classList.remove('visible');
        setTimeout(() => toast.remove(), 300);
    }, 2600);
};

/**
 * Toasts rendus par le serveur (après un enregistrement, un statut, etc.) :
 * on les anime, on permet de les fermer, et on les retire automatiquement.
 */
document.querySelectorAll('[data-vendo-toast]').forEach((toast) => {
    toast.classList.add('visible');

    const boutonFermer = toast.querySelector('[data-vendo-toast-fermer]');
    if (boutonFermer) {
        boutonFermer.addEventListener('click', () => toast.remove());
    }

    setTimeout(() => {
        toast.classList.remove('visible');
        setTimeout(() => toast.remove(), 300);
    }, 3500);
});

/**
 * Libellés et couleurs des statuts, utilisés pour mettre à jour l'écran
 * sans recharger la page après une action du vendeur.
 */
const STATUTS_COMMANDE = {
    en_attente: ['En attente', 'bg-avertissement text-white'],
    confirmee: ['Confirmée', 'bg-succes text-white'],
    livree: ['Livrée', 'bg-succes text-white'],
    retiree: ['Retirée', 'bg-succes text-white'],
    annulee: ['Annulée', 'bg-alerte text-white'],
};

/**
 * Actions du vendeur envoyées en arrière-plan (sans recharger la page) :
 * masquer/remettre un produit, changer un statut, supprimer un produit.
 */
document.addEventListener('submit', async (event) => {
    const formulaire = event.target;

    if (!(formulaire instanceof HTMLFormElement) || !formulaire.hasAttribute('data-ajax')) {
        return;
    }

    event.preventDefault();

    const bouton = formulaire.querySelector('button[type="submit"]');
    if (bouton) {
        bouton.disabled = true;
    }

    let reponse;

    try {
        reponse = await fetch(formulaire.action, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                Accept: 'application/json',
            },
            body: new FormData(formulaire),
        });
    } catch {
        if (bouton) {
            bouton.disabled = false;
        }
        vendoToast('Pas de connexion. Réessayez.', 'erreur');
        return;
    }

    const donnees = await reponse.json().catch(() => ({}));

    if (bouton) {
        bouton.disabled = false;
    }

    if (!reponse.ok) {
        vendoToast(donnees.message || 'Une erreur est survenue.', 'erreur');
        return;
    }

    vendoToast(donnees.message || 'Enregistré.');

    const action = formulaire.getAttribute('data-action');

    if (action === 'disponibilite') {
        gererDisponibilite(formulaire, donnees);
    }

    if (action === 'statut') {
        gererStatut(donnees);
    }

    if (action === 'retrait') {
        gererRetrait(donnees);
    }

    if (action === 'supprimer') {
        const cible = formulaire.getAttribute('data-remove-target');
        const element = cible ? document.querySelector(cible) : null;

        if (element) {
            element.style.transition = 'opacity .3s ease, transform .3s ease';
            element.style.opacity = '0';
            element.style.transform = 'translateX(10px)';
            setTimeout(() => element.remove(), 300);
        }
    }
});

/**
 * Met à jour la carte d'un produit après un masquage / une remise en vente.
 */
function gererDisponibilite(formulaire, donnees) {
    const carte = formulaire.closest('[data-produit-carte]');

    if (!carte) {
        return;
    }

    const label = carte.querySelector('[data-dispo-label]');
    if (label) {
        label.textContent = donnees.disponible ? 'Masquer' : 'Remettre en vente';
    }

    const badge = carte.querySelector('[data-dispo-badge]');
    if (badge) {
        badge.classList.toggle('hidden', Boolean(donnees.disponible));
    }
}

/**
 * Met à jour le badge de statut et les actions disponibles d'une commande.
 */
function gererStatut(donnees) {
    const styles = STATUTS_COMMANDE[donnees.statut] || [donnees.statut, 'bg-fond-alterne text-texte-secondaire'];

    document.querySelectorAll('[data-statut-badge]').forEach((badge) => {
        badge.textContent = styles[0];
        badge.className = 'inline-block text-xs font-medium px-2.5 py-1 rounded-full ' + styles[1];
    });

    document.querySelectorAll('[data-actions-statut] [data-statut-pour]').forEach((bouton) => {
        const autorises = bouton.getAttribute('data-statut-pour').split(',');
        bouton.closest('form').classList.toggle('hidden', !autorises.includes(donnees.statut));
    });
}

/**
 * Masque la section de retrait et met à jour le statut après validation du code.
 */
function gererRetrait(donnees) {
    const sectionRetrait = document.querySelector('[data-action="retrait"]')?.closest('[x-data]');

    if (sectionRetrait) {
        sectionRetrait.style.transition = 'opacity .3s ease, transform .3s ease';
        sectionRetrait.style.opacity = '0';
        sectionRetrait.style.transform = 'translateY(-8px)';
        setTimeout(() => sectionRetrait.remove(), 300);
    }

    gererStatut(donnees);
}

Alpine.start();
