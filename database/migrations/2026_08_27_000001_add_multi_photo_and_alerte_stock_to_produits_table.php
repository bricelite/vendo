<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('produits', function (Blueprint $table) {
            $table->boolean('alerte_stock_bas')->default(false)->after('est_en_solde');
            $table->json('images_supplementaires')->nullable()->after('image_url');
        });
    }

    public function down(): void
    {
        Schema::table('produits', function (Blueprint $table) {
            $table->dropColumn(['alerte_stock_bas', 'images_supplementaires']);
        });
    }
};
