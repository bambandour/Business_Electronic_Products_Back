<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Commande extends Model
{
    use HasFactory;

    public $guarded=['id'];
    public function produit_commandes(){
        return $this->belongsToMany(ProduitSuccursale::class,'produit_commandes')
                        ->withPivot('quantite','prix','remise');
    }

    public function paiement():HasMany
    {
        return $this->hasMany(Paiement::class);
    }
    
}
