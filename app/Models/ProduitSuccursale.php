<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProduitSuccursale extends Model
{
    use HasFactory;
    public $guarded=[''];
    // public function produits(){
    //     return $this->belongsToMany(Produit::class,'produit_succursales','produit_id','succursale_id')
    //                     ->withPivot('quantite','prix','prixEnGros');
    // }
}
