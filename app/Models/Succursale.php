<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Succursale extends Model
{
    use HasFactory;
    protected $guarded=['id'];

    // Modèle Succursale
public function relations() {
    return $this->belongsToMany(Succursale::class, 'relations', 'succursale_id', 'demandeur_id')
        ->withPivot('estAmis');
}

 public function produits(){
        return $this->belongsToMany(Produit::class,'produit_succursales')
                        ->withPivot('quantite','prix','prixEnGros');
}

public function scopeByProductCode(Builder $builder, $code){
    return $builder->whereHas('produits', function($query)use ($code) {
        $query->where('code', $code);
    });
}

   
}
