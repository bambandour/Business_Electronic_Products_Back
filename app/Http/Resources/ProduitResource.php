<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProduitResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"=>$this->id,
            "libelle"=>$this->libelle,
            "code"=>$this->code,
            "photo"=>$this->photo,
            "description"=>$this->description,
            "caracteristiques"=>CaracteristiqueProduitResource::collection($this->caracteristiques),
            // "quantite"=>$this->produit_succursales[0]->pivot->quantite,
            "succursales"=>ProduitSuccursaleResource::collection($this->produit_succursales),
        ];
    }
}
