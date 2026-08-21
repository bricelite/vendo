<?php

namespace Tests\Unit;

use App\Services\CommandeService;
use App\Services\CommissionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServicesTest extends TestCase
{
    use RefreshDatabase;

    public function test_la_commission_de_5_pourcent_donne_le_montant_net(): void
    {
        $service = new CommissionService;

        $this->assertSame(9500, $service->calculerMontantNet(10000));
        $this->assertSame(500, $service->calculerMontantCommission(10000));
    }

    public function test_la_commission_dun_montant_nul_donne_zero(): void
    {
        $service = new CommissionService;

        $this->assertSame(0, $service->calculerMontantNet(0));
    }

    public function test_la_reference_courte_est_unique_et_lisible(): void
    {
        $service = new CommandeService;

        $reference = $service->genererReferenceCourte();

        $this->assertMatchesRegularExpression('/^VE-[A-Z0-9]{4}$/', $reference);
    }
}
