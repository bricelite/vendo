<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('boutiques', function (Blueprint $table) {
            $table->unsignedInteger('seuil_fidele')->default(0)->after('google_maps_url');
            $table->unsignedInteger('reduction_fidele')->default(0)->after('seuil_fidele');
        });
    }

    public function down(): void
    {
        Schema::table('boutiques', function (Blueprint $table) {
            $table->dropColumn(['seuil_fidele', 'reduction_fidele']);
        });
    }
};
