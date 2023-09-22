<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProduitCommandeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "produit_succrsale_id"=>$this->pivot->produit_succursale_id,
            "quantite_vendu"=>$this->pivot->quantite,
            "prix_vente"=>$this->pivot->prix,
        ];
    }
}
