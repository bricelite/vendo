<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class CommissionService
{
    /**
     * Montant que le vendeur va réellement recevoir après la commission Vendo.
     *
     * @param  int  $montantProduit  prix payé par le client en FCFA
     */
    public function calculerMontantNet(int $montantProduit): int
    {
        if ($montantProduit < 0) {
            Log::error('Montant produit négatif passé au calcul de commission', [
                'montant' => $montantProduit,
            ]);

            return 0;
        }

        $montantCommission = $this->calculerMontantCommission($montantProduit);

        return $montantProduit - $montantCommission;
    }

    /**
     * Part prélevée par Vendo sur une vente, en FCFA.
     */
    public function calculerMontantCommission(int $montantProduit): int
    {
        $pourcentage = config('vendo.commission_pourcentage');

        return (int) round($montantProduit * $pourcentage / 100);
    }
}
