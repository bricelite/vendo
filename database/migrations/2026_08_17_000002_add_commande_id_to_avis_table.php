<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('avis', function (Blueprint $table) {
            $table->foreignId('commande_id')->nullable()->after('produit_id')->constrained()->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('avis', function (Blueprint $table) {
            $table->dropForeign(['commande_id']);
            $table->dropColumn('commande_id');
        });
    }
};
