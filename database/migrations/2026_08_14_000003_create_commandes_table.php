<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('commandes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('boutique_id')->constrained()->onDelete('cascade');
            $table->string('reference_courte')->unique();
            $table->string('client_nom');
            $table->string('client_telephone');
            $table->string('client_localite')->nullable();
            $table->enum('statut', ['en_attente', 'confirmee', 'livree', 'annulee'])->default('en_attente');
            $table->unsignedInteger('montant_produit');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commandes');
    }
};
