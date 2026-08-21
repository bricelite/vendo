<?php

return [

    /*
    | Pourcentage de la commission Vendo prélevé sur chaque vente.
    | Lisible côté métier : « Vous recevrez 9 500 FCFA sur une vente de 10 000 FCFA ».
    */
    'commission_pourcentage' => (float) env('VENDO_COMMISSION_POURCENTAGE', 5),

    /*
    | Mode de paiement actif. Valeurs possibles :
    |   'a_la_reception' — le client paie en espèces/MoMo à la livraison (par défaut)
    |   'mobile_money'   — paiement Mobile Money avant confirmation (à brancher)
    |
    | Ce choix détermine le message affiché au client dans le panier et sur la page
    | de confirmation. Modifier cette valeur suffit à basculer tout le flux.
    */
    'mode_paiement' => env('VENDO_MODE_PAIEMENT', 'a_la_reception'),

    /*
    | Message de paiement affiché au client dans le panier.
    | S'adapte au mode_paiement : à terme, remplacer par le vrai message
    | Mobile Money quand l'agrégateur sera branché.
    */
    'message_paiement' => [
        'a_la_reception' => 'Vous paierez le vendeur à la réception de votre commande.',
        'mobile_money'   => 'Vous serez débité par Mobile Money avant la confirmation.',
    ],

];
