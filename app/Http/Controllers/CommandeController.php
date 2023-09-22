<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommandeController extends Controller
{
    public function index(){
        return Commande::all();
    }
    public function store(Request $request){
        DB::beginTransaction();
        try{
        $com=Commande::create([
            "date"=>now(),
            "reduction"=>$request->reduction,
            "user_id"=>$request->user_id,
            "client_id"=>$request->client_id,
        ]);
        $com->produit_commandes()->attach(
            $request->produit_succursale_id, [
                'quantite' => $request->quantite,
                'prix' => $request->prix,
                'remise' => $request->remise,
        ]);
        DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return [
                "message" => 'Une erreur est survenue lors de l\'ajout du panier .',
                "error" => $e->getMessage(),
                "success" => false
            ];
        }

        return $com;
    }
}
