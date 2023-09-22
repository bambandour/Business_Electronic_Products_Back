<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    use HasFactory;
    public $guarded=['id'];

    public function produit_succursales(){
        return $this->belongsToMany(Succursale::class,'produit_succursales','produit_id','succursale_id')
                        ->withPivot('quantite','prix','prixEnGros');        
    }
    public function caracteristiques(){
        return $this->belongsToMany(Caracteristique::class,'caracteristique_produits','produit_id','caracteristique_id')
                        ->withPivot('valeur');
    }
}
