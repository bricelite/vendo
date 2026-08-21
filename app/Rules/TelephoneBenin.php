<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Valide un numéro de téléphone béninois.
 *
 * Accepte les deux formats en circulation :
 *   - Ancien format (8 chiffres) : 9X XX XX XX — accepté temporairement
 *   - Nouveau format (10 chiffres, depuis 30/11/2024) : 01 XX XX XX XX — recommandé
 *
 * Nettoie les espaces, points et tirets avant validation.
 */
class TelephoneBenin implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $nettoye = preg_replace('/[\s.\-]/', '', (string) $value);

        if (! preg_match('/^(01\d{8}|[6-9]\d{7})$/', $nettoye)) {
            $fail('Numéro de téléphone invalide. Utilisez le format béninois à 10 chiffres (ex. : 01 97 12 34 56).');
        }
    }
}
