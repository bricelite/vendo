<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Convertit les anciens numéros béninois à 8 chiffres vers le format 10 chiffres
 * (préfixe 01 ajouté devant). Depuis le 30/11/2024, tous les numéros béninois
 * sont passés à 10 chiffres.
 *
 * Ne touche qu'aux numéros de 8 chiffres commençant par 9 (ancien format).
 * Les numéros déjà à 10 chiffres ou les formats étrangers ne sont pas modifiés.
 *
 * Cette migration est MySQL-only : CONCAT et regexp sont des fonctions MySQL.
 * Sur SQLite (tests), les données sont déjà en format 10 chiffres via le seeder.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        $champs = [
            ['table' => 'users', 'colonne' => 'telephone'],
            ['table' => 'commandes', 'colonne' => 'client_telephone'],
        ];

        foreach ($champs as $config) {
            DB::table($config['table'])
                ->where($config['colonne'], 'regexp', '^9[0-9]{7}$')
                ->update([
                    $config['colonne'] => DB::raw("CONCAT('01', {$config['colonne']})"),
                ]);
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        $champs = [
            ['table' => 'users', 'colonne' => 'telephone'],
            ['table' => 'commandes', 'colonne' => 'client_telephone'],
        ];

        foreach ($champs as $config) {
            DB::table($config['table'])
                ->where($config['colonne'], 'regexp', '^019[0-9]{7}$')
                ->update([
                    $config['colonne'] => DB::raw("SUBSTRING({$config['colonne']}, 3)"),
                ]);
        }
    }
};
