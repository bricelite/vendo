# ARCHITECTURE.md — Vendo

> Ce fichier est une référence obligatoire. Il doit être respecté à chaque
> fois que du code est écrit, modifié ou revu sur ce projet — que ce soit
> par vous, par un futur développeur, ou par un assistant IA à qui vous
> déléguez une tâche de code. En cas de doute entre "faire vite" et
> "respecter ce fichier", ce fichier gagne toujours.
>
> Contexte du projet : Vendo est une plateforme de commerce social pour
> vendeurs indépendants au Bénin (voir documents de cadrage et
> d'architecture technique). Développée en solo. Stack : Laravel (backend
> + rendu Blade), MySQL, Alpine.js, Cloudinary.

---

## 0. Les deux règles qui priment sur tout le reste

1. **Simplicité avant élégance.** Un code que vous ne pouvez pas relire et
   comprendre en 6 mois, sans effort, est un mauvais code — même s'il est
   "propre" ou "moderne". Ce projet est maintenu par une seule personne :
   la lisibilité vaut plus que la sophistication.
2. **Le vendeur ne voit jamais la complexité technique.** Toute décision
   d'interface doit être jugée avec cette question : *"Est-ce qu'Aïcha,
   qui vend des vêtements sur WhatsApp et n'a jamais utilisé d'outil de
   gestion, comprend ça immédiatement ?"* Si la réponse n'est pas oui,
   on simplifie.

---

## 1. FRONTEND — Style et conventions d'interface
	 respecter l'aligenment de chaque chose (taille)

### 1.1 Palette de couleurs

Utiliser exclusivement ces couleurs (variables CSS à définir une fois dans
`resources/css/app.css`, jamais de couleur codée en dur ailleurs) :

```css
:root {
  --couleur-principale: #1A4883;   /* bleu marine du logo — en-têtes, boutons primaires */
  --couleur-accent: #F2801F;       /* orange du logo — éléments d'action secondaires, badges promo */
  --couleur-texte: #1A1A1A;
  --couleur-texte-secondaire: #555555;
  --couleur-fond: #FFFFFF;
  --couleur-fond-alterne: #F2F2F2;
  --couleur-succes: #2E7D32;       /* confirmation paiement, stock disponible */
  --couleur-alerte: #C0392B;       /* rupture de stock, erreur */
  --couleur-avertissement: #C08A2E; /* stock faible, en attente */
}
```

Règle : jamais plus de 3 couleurs visibles sur un même écran (hors nuances
de gris). Un vendeur qui regarde son tableau de bord ne doit jamais se
demander "pourquoi c'est rouge ici ?".

### 1.2 Typographie

- Une seule famille de police pour tout le site (système par défaut ou une
  seule police web légère — pas de chargement de plusieurs polices, ça
  ralentit le chargement sur connexion 3G).
- Tailles fixes, pas de valeurs improvisées : `text-sm` (texte secondaire),
  `text-base` (texte courant), `text-lg` (sous-titres), `text-2xl`
  (titres de page). Utiliser les classes Tailwind standard, jamais de
  `text-[17px]` sur-mesure.

### 1.3 Hiérarchie des boutons et CTA

**Règle absolue : un seul bouton primaire visible par écran.** Si deux
actions semblent aussi importantes l'une que l'autre, c'est un signal
qu'il faut simplifier l'écran, pas ajouter un deuxième bouton primaire.

| Type de bouton | Usage | Position |
|---|---|---|
| **Primaire** (fond `--couleur-principale`, texte blanc) | L'action principale de l'écran (ex. "Ajouter le produit", "Payer maintenant") | Toujours en bas de formulaire sur mobile, pleine largeur. Jamais en haut de page. |
| **Secondaire** (contour, fond transparent) | Action alternative (ex. "Annuler", "Retour") | À gauche ou en dessous du bouton primaire, jamais à sa droite sur mobile |
| **Destructif** (`--couleur-alerte`) | Suppression, annulation définitive | Toujours accompagné d'une confirmation ("Êtes-vous sûr ?"), jamais un clic direct |
| **Lien texte simple** | Action mineure (ex. "Voir plus", "Modifier") | Intégré dans le texte, pas de fond de bouton |

CTA (boutique publique côté client) : le bouton "Commander" ou "Réserver"
reste **toujours visible sans scroll** sur la fiche produit (fixé en bas
de l'écran sur mobile) — ne jamais l'enterrer sous la description.

### 1.4 Formulaires

- **Un champ par ligne**, jamais deux champs côte à côte sur mobile.
- Label toujours **au-dessus** du champ, jamais à l'intérieur seul
  (placeholder ≠ label — un placeholder qui disparaît à la saisie ne doit
  jamais être la seule indication du champ).
- Message d'erreur affiché **juste sous le champ concerné**, en
  `--couleur-alerte`, texte court et concret ("Numéro invalide" plutôt que
  "Erreur de validation du champ téléphone").
- Jamais plus de **5 champs visibles en même temps** sur un même écran —
  si un formulaire en a plus, le découper en étapes (voir principe de
  divulgation progressive, section 1.6).
- Bouton de soumission désactivé pendant l'envoi, avec un indicateur de
  chargement visible — un vendeur sur 3G doit voir que ça travaille, pas
  cliquer deux fois par doute.

### 1.5 Badges de statut

Toujours la même forme (pastille arrondie, texte court, une seule
couleur de fond) :

| Badge | Couleur | Texte exact |
|---|---|---|
| Rupture de stock | `--couleur-alerte` | "Rupture de stock" |
| Stock faible | `--couleur-avertissement` | "Il en reste {n}" |
| Nouveau produit | `--couleur-succes` | "Nouveau" |
| Promotion active | `--couleur-accent` | "Promo" (+ prix barré à côté) |

Jamais deux badges superposés visuellement — s'il y a plusieurs états
vrais en même temps (ex. nouveau ET stock faible), n'afficher que le
plus prioritaire pour la décision d'achat (rupture > stock faible >
promo > nouveau).

### 1.6 Divulgation progressive (rappel obligatoire)

Aucun écran ne doit demander une information non indispensable à l'étape
en cours. Concrètement :
- Inscription vendeur : 3 étapes max (téléphone/nom → nom boutique →
  premier produit), rien d'autre.
- Numéro Mobile Money, gestion des livreurs : configurables *après*,
  jamais bloquants à l'inscription.
- Vocabulaire toujours "métier", jamais technique : "Vous recevrez
  9 500 FCFA", jamais "commission de 5% déduite" ni "webhook", ni
  "statut de transaction" dans une interface vendeur.

### 1.7 Mobile-first et performance

- Toute page doit être pensée d'abord pour un écran de ~360px de large,
  ensuite adaptée aux écrans plus grands — jamais l'inverse.
- Images toujours compressées avant upload (voir backend, section 2.6) et
  chargées en `loading="lazy"` sauf la première image visible à l'écran.
- Pas de librairie JavaScript lourde. Alpine.js suffit pour l'interactivité
  du V0 — ne pas introduire Vue/React tant que Blade + Alpine répond au
  besoin.

---

## 2. BACKEND — Conventions de code

**Principe directeur : ce code doit pouvoir être lu et compris par un
développeur qui a commencé à programmer il y a un mois.** Ce n'est pas une
contrainte esthétique, c'est une nécessité : c'est vous, dans six mois,
qui aurez besoin de comprendre ce code rapidement sans tout réapprendre.

### 2.1 Nommage — toujours explicite, jamais abrégé

```php

Règles :
- Un booléen se lit comme une question : `$estPayee`, `$peutEncaisser()`,
  jamais `$statut2` ou `$flag`.

### 2.2 Une fonction = une seule responsabilité

Si une méthode fait plus de 20-25 lignes, ou si sa description a besoin du
mot "et" ("calcule le montant et envoie la notification et met à jour le
stock"), elle doit être découpée.

```php
// ❌ À éviter : une méthode qui fait tout
public function traiterPaiement($commande) {
    // calcul commission...
    // appel API paiement...
    // mise à jour stock...
    // envoi notification...
    // 40 lignes plus tard...
}

// ✅ À faire : chaque étape est nommée et isolée
public function traiterPaiement(Commande $commande): void {
    $montantNet = $this->commissionService->calculerMontantNet($commande->montant_produit);
    $this->payoutService->declencherTransfert($commande, $montantNet);
    $this->notifierVendeur($commande);
}
```

Une fonction courte et bien nommée se comprend sans lire son contenu —
c'est l'objectif à chaque fois.

### 2.3 Structure des fichiers (rappel, voir document d'architecture)

```
app/
├── Models/        → uniquement les relations et la logique propre à la donnée
├── Services/       → toute la logique métier complexe (paiement, commission)
├── Jobs/           → tout traitement asynchrone (paiement, expiration)
└── Http/Controllers/ → reçoit la requête, appelle un Service, retourne une réponse
                        (un contrôleur ne doit JAMAIS contenir de calcul métier)
```

Un contrôleur doit ressembler à ceci — court, lisible, sans logique cachée :

```php
public function payer(Request $request, Commande $commande, PaymentService $paymentService)
{
    $lienPaiement = $paymentService->initierPaiement($commande);
    return redirect($lienPaiement);
}
```

Si un contrôleur dépasse ~15 lignes dans une méthode, c'est le signal
qu'une partie de la logique doit être déplacée dans un `Service`.

### 2.4 Commentaires — expliquer le "pourquoi", pas le "quoi"

```php
// ❌ Inutile : le code dit déjà "quoi"
// on incrémente le stock
$produit->increment('stock_quantite');

// ✅ Utile : explique une décision qui n'est pas évidente à la lecture
// On remet le stock disponible car la réservation a expiré sans paiement
// (voir section 14.1 du document de cadrage — durée définie par le vendeur)
$produit->increment('stock_quantite', $ligne->quantite);
```

Un commentaire doit toujours répondre à "pourquoi c'est fait comme ça",
jamais reformuler ce que le code montre déjà.

### 2.5 Gestion des erreurs — toujours explicite, jamais silencieuse

```php
// ❌ À éviter : l'erreur disparaît sans laisser de trace
try {
    $payoutService->declencherTransfert($commande, $montant);
} catch (\Throwable $e) {
    // rien
}

// ✅ À faire : on journalise et on relance, jamais d'échec silencieux
try {
    $payoutService->declencherTransfert($commande, $montant);
} catch (\Throwable $e) {
    \Log::error("Échec du transfert pour la commande {$commande->reference_courte}", [
        'erreur' => $e->getMessage(),
    ]);
    throw $e;
}
```

Règle absolue pour tout ce qui touche à l'argent (paiement, commission,
transfert) : **aucune erreur ne doit pouvoir disparaître sans log.** Un
vendeur non payé sans trace de l'erreur est le pire scénario possible pour
ce projet.

### 2.6 Uploads d'images

Toujours compresser côté client (JavaScript, avant l'envoi au serveur)
avant d'uploader vers Cloudinary — jamais envoyer une photo brute prise
au téléphone. Objectif : quelques centaines de Ko maximum par image, pour
rester utilisable sur une connexion 3G.

### 2.7 Ce qu'il ne faut jamais faire dans ce projet

- Jamais de logique métier dans une vue Blade (`@if($commande->montant * 0.05 ...)`
  — ce calcul doit venir d'un Service, pas être écrit dans le template).
- Jamais de requête SQL brute sauf nécessité absolue — utiliser Eloquent,
  plus lisible pour un débutant.
- Jamais de clé API ou de secret écrit dans le code — uniquement dans `.env`.
- Jamais de traitement de paiement synchrone dans un contrôleur — toujours
  via un `Job` (voir document d'architecture, section 6).
- Jamais une fonctionnalité "presque" terminée qu'on laisse de côté pour
  passer à la suivante — la dette technique non traitée devient très
  coûteuse à rattraper en solo.

### 2.8 Avant chaque commit — checklist rapide

- [ ] Est-ce qu'un développeur d'un mois d'expérience comprendrait ce
      fichier sans explication orale ?
- [ ] Les noms de variables/fonctions sont-ils explicites, sans abréviation ?
- [ ] Chaque fonction fait-elle une seule chose ?
- [ ] Toute opération liée à l'argent est-elle journalisée en cas
      d'erreur ?
- [ ] L'interface respecte-t-elle un seul bouton primaire par écran et le
      principe de divulgation progressive ?
- [ ] Rien n'est codé en dur qui devrait être dans `.env` ou une variable
      CSS ?

---

## 3. En cas de doute

Si une décision de code ou de design n'est pas couverte par ce fichier,
revenir au principe de la section 0 : **simplicité et lisibilité pour un
développeur débutant, clarté immédiate pour un vendeur non technique.**
Ce fichier doit être mis à jour dès qu'une nouvelle convention est décidée
— il n'est utile que s'il reste le reflet exact de ce qui est réellement
appliqué dans le code.

---

## 4. Conventions décidées (à respecter)

### 4.1 Authentification par téléphone

- L'identifiant de connexion est le **numéro de téléphone**, pas l'email.
  L'email est **facultatif** sur le profil (`nullable` en base).
- Le champ formulaire s'appelle `telephone`, jamais `email`, sur la
  connexion et l'inscription.
- Pas de réinitialisation de mot de passe ni de vérification email pour le
  MVP : ces écrans sont retirés (l'email étant facultatif, un reset par
  email serait une fonctionnalité « presque finie » — interdite, §2.7).
- Un vendeur est créé en **3 étapes** (§1.6) : compte (nom, téléphone,
  mot de passe) → nom boutique → premier produit. Les trois sont créés
  dans une seule transaction via `BoutiqueService::creerCompteVendeur()`.

### 4.2 Environnement de développement (ChromeOS / mount 9p)

Le dossier de travail `/mnt/chromeos/MyFiles` est un montage 9p qui ne
supporte **pas les liens symboliques** : `node_modules` (avec son `.bin`)
ne peut pas y être installé ni utilisé. Le mount est aussi très lent pour
les opérations qui touchent des milliers de petits fichiers.

Concrètement :

- **PHP / artisan / serveur** : fonctionnent depuis le projet, mais le
  serveur de dev est très lent sur ce mount. Pour les tests et le serveur
  de dev, utiliser un miroir local rapide (`/tmp/opencode/vendo-build`)
  synchronisé avec `rsync`, puis synchroniser les fichiers modifiés vers
  le projet.
- **Script `sync.sh`** (à la racine du projet) : `./sync.sh push` envoie
  le projet vers le miroir, `./sync.sh pull` récupère dans le projet les
  fichiers modifiés côté miroir (jamais `.env`, `vendor/`, `node_modules/`
  ni `storage/`). Ne jamais faire tourner deux serveurs en même temps
  (miroir et mount partagent la même base MySQL).
- **Commande unique `./dev.sh`** (à la racine du projet) : synchronise le
  projet vers le miroir, recrée celui-ci s'il a disparu (redémarrage),
  puis démarre le serveur sur http://127.0.0.1:8000. `./dev.sh stop`
  l'arrête. C'est la seule commande nécessaire au quotidien ; `sync.sh`
  reste utile pour un `pull` manuel après une modification côté miroir.
- **Frontend (npm / vite)** : toujours construire sur le disque local
  (`/tmp/opencode/vendo-build`), puis copier `public/build/` vers le
  projet. Ne jamais lancer `npm install` ou `npm run build` directement
  dans le dossier du projet sur le mount 9p.
- Les assets compilés (`public/build/`) sont versionnés dans le projet :
  l'application fonctionne sans `node_modules` sur le mount.
