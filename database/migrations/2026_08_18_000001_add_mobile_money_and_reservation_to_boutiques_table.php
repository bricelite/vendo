<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('boutiques', function (Blueprint $table) {
            $table->string('numero_mobile_money')->nullable()->after('reduction_fidele');
            $table->string('operateur_mobile_money')->nullable()->after('numero_mobile_money');
            $table->unsignedInteger('duree_reservation_defaut_minutes')->nullable()->default(360)->after('operateur_mobile_money');
        });
    }

    public function down(): void
    {
        Schema::table('boutiques', function (Blueprint $table) {
            $table->dropColumn(['numero_mobile_money', 'operateur_mobile_money', 'duree_reservation_defaut_minutes']);
        });
    }
};
