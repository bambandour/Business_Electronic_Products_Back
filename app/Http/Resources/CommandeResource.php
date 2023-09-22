<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommandeResource extends JsonResource
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
            "montant"=>$this->montant,
            "reduction"=>$this->reduction,
            "client"=>$this->client_id,
            "utilisateur"=>$this->user_id,
            "commandes"=>ProduitCommandeResource::collection($this->produit_commandes),
            // "id"=>$this->produit_commandes[0]->pivot->produit_succursale_id,
        ];
    }
}
