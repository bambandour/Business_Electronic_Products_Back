<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Caracteristique extends Model
{
    use HasFactory;

    public $guarded=['id'];

    // public function caracteristiques(){
    //      return $this->belongsToMany(Produit::class,'caracteristique_produits','produit_id','caracteristique_id')
    //                      ->withPivot('valeur');
    // }
}
