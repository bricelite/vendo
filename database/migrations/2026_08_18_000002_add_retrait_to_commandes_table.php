<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('commandes', function (Blueprint $table) {
            $table->string('mode_retrait')->nullable()->after('montant_produit');
            $table->string('code_retrait')->nullable()->after('mode_retrait');
            $table->string('statut_retrait')->nullable()->after('code_retrait');
        });
    }

    public function down(): void
    {
        Schema::table('commandes', function (Blueprint $table) {
            $table->dropColumn(['mode_retrait', 'code_retrait', 'statut_retrait']);
        });
    }
};
