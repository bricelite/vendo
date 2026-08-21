<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;

    protected $fillable = [
        'boutique_id',
        'user_id',
        'reference_courte',
        'client_nom',
        'client_telephone',
        'client_localite',
        'statut',
        'montant_produit',
        'mode_retrait',
        'code_retrait',
        'statut_retrait',
    ];

    public function boutique()
    {
        return $this->belongsTo(Boutique::class);
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function lignes()
    {
        return $this->hasMany(LigneCommande::class);
    }
}
