<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProduitSuccursaleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            // "id"=>$this->id,
            "quantite"=>$this->pivot->quantite,
            "prix"=>$this->pivot->prix,
            "prixEnGros"=>$this->pivot->prixEnGros,
            "succursale"=>$this->libelle
        ];
    }
}
