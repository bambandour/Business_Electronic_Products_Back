<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaracteristiqueProduit extends Model
{
    use HasFactory;

    public function produit_caracteristiques(){
        return $this->hasMany(CaracteristiqueProduit::class);
    }
}
