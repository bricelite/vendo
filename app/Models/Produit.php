<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    use HasFactory;

    protected $fillable = [
        'boutique_id',
        'categorie_id',
        'nom',
        'description',
        'prix',
        'prix_promo',
        'stock_quantite',
        'image_url',
        'images_supplementaires',
        'est_disponible',
        'est_en_solde',
        'alerte_stock_bas',
    ];

    protected $casts = [
        'est_disponible' => 'boolean',
        'est_en_solde' => 'boolean',
        'alerte_stock_bas' => 'boolean',
        'images_supplementaires' => 'array',
    ];

    public function boutique()
    {
        return $this->belongsTo(Boutique::class);
    }

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function avis()
    {
        return $this->hasMany(Avis::class);
    }

    public function estEnRupture(): bool
    {
        return $this->stock_quantite === 0;
    }

    public function estEnStockFaible(): bool
    {
        return ! $this->estEnRupture() && $this->stock_quantite <= 5;
    }

    public function prixActuel(): int
    {
        return $this->prix_promo ?? $this->prix;
    }

    public function promoActive(): bool
    {
        return $this->est_en_solde && $this->prix_promo !== null && $this->prix > 0;
    }

    public function pourcentageReduction(): int
    {
        if (! $this->promoActive()) {
            return 0;
        }

        return (int) round((1 - $this->prix_promo / $this->prix) * 100);
    }
}
