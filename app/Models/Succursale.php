<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

public static function mesAmis($id)
    {
        return  DB::table('relations')->where('estAmis', 1)
            ->where('succursale_id', $id)
            ->orWhere('demandeur_id', $id)
            ->get();
    }

    public function scopeMyFriends(Builder $builder , $id){

        return  $builder->from('relations')->where('estAmis' , 1)
        ->where('succursale_id', $id)
        ->orWhere('demandeur_id', $id)
        ->get();
    }
    public function scopeWait(Builder $builder , $id)
    {
        return  $builder->from('amis')->where(['estAmis' => 0 , 'demandeur_id' => $id])->get();
    }

    public function scopeOthers(Builder $builder , $id){
        $mesAmis = $this->mesAmis($id)->pluck('id');
        return $builder->from('produit_succursales')->whereNotIn('id' , $mesAmis);
    }
}

