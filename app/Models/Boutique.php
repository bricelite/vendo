<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Boutique extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nom',
        'slug',
        'description',
        'localisation',
        'google_maps_url',
        'logo_url',
        'couverture_url',
        'seuil_fidele',
        'reduction_fidele',
        'numero_mobile_money',
        'operateur_mobile_money',
        'duree_reservation_defaut_minutes',
    ];

    public function vendeur()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function produits()
    {
        return $this->hasMany(Produit::class);
    }

    public function commandes()
    {
        return $this->hasMany(Commande::class);
    }

    public function categories()
    {
        return $this->hasMany(Categorie::class)->orderBy('ordre');
    }
}
