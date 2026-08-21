<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::statement("ALTER TABLE commandes MODIFY COLUMN statut ENUM('en_attente', 'confirmee', 'livree', 'annulee', 'retiree') DEFAULT 'en_attente'");
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::statement("ALTER TABLE commandes MODIFY COLUMN statut ENUM('en_attente', 'confirmee', 'livree', 'annulee') DEFAULT 'en_attente'");
    }
};
