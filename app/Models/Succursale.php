<?php

namespace App\Models;

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

   
}
