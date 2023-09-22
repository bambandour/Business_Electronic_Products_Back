<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommandeResource;
use App\Models\Commande;
use App\Models\Paiement;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommandeController extends Controller
{
    use ResponseTrait;
    public function index(){
        $commande= Commande::all();
        return CommandeResource::collection($commande);
    }
    public function store(Request $request){
        DB::beginTransaction();
        try{
        $com=Commande::create([
            "date"=>now(),
            "montant"=>$request->montant,
            "reduction"=>$request->reduction,
            "user_id"=>$request->user_id,
            "client_id"=>$request->client_id,
        ]);

        $com->produit_commandes()->attach($request->produits);

        $payement=Paiement::create([
            "date"=>now(),
            "montant"=>$request->montant_payer,
            "commande_id"=>$com->id
        ]);

        foreach($request->produits as $produit ){
            DB::statement("UPDATE produit_succursales set quantite = quantite -$produit[quantite] where id =$produit[produit_succursale_id]");
        }

        DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return $this->formatResponse('Une erreur est survenue lors de l\'ajout du panier .', [], false,500,$e->getMessage());
        }
        // $data = [new CommandeResource($com)];
        return $this->formatResponse('La commande a été éffectué avec succes.', $com, true,200);
    }
}
