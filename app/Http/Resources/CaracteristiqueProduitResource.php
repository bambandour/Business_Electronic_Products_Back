<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CaracteristiqueProduitResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            // "caracteristique"=>new CaracteristiqueResource($this->caracteristiques) ,
            "id"=>$this->id,
            "libelle"=>$this->libelle,
            "valeur"=>$this->pivot->valeur
        ];
    }
}
