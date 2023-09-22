<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;

    public $guarded=['id'];
    public function produit_commandes(){
        return $this->belongsToMany(ProduitSuccursale::class,'produit_commandes','commande_id','produit_succursale_id')
                        ->withPivot('quantite','prix','remise');
    }
}
