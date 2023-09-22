<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    use HasFactory;
    public $guarded=['id'];

    public function scopeBySuccursale(Builder $builder,$id){
        return $builder->whereHas('produit_succursales', function($query)use ($id) {
            $query->where('succursale_id', $id);
        });
    }
    public function produit_succursales(){
        return $this->belongsToMany(Succursale::class,'produit_succursales','produit_id','succursale_id')
                        ->withPivot('quantite','prix','prixEnGros', 'id');        
    }
    public function caracteristiques(){
        return $this->belongsToMany(Caracteristique::class,'caracteristique_produits','produit_id','caracteristique_id')
                        ->withPivot('valeur');
    }

    public function scopeQuantitePositive(Builder $builder , $ids , $limit , $code):Builder {
        return  $builder->with(['succursales' => function ($q) use ($ids, $limit) {
             $q->whereIn('succursale_id', $ids)->where('quantite', ">", 0)->orderBy('prixEnGros', "asc")
                 ->when($limit, fn ($q) => $q->limit($limit));
         }, 'caracteristiques'])->where('code', $code);
     }
}
