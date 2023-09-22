<?php

namespace App\Http\Controllers;

use App\Http\Requests\SuccursaleRequest;
use App\Http\Resources\SuccursaleResource;
use App\Models\Succursale;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuccursaleController extends Controller
{
    public function index(){
        $suc=Succursale::paginate(3); 
        return SuccursaleResource::collection($suc);
    }
    public function store(SuccursaleRequest $request){
        $succursale=Succursale::create([
            "libelle"=>$request->libelle,
            "adresse"=>$request->adresse,
            "telephone"=>$request->telephone,
            "reduction"=>$request->reduction,
        ]);
        return $succursale;

    }

    public function update(Request $request, Succursale $id){
        $id->update([
            'libelle'=>$request->libelle,
            'adresse'=>$request->adresse,
            'telephone'=>$request->telephone,
            'reduction'=>$request->reduction,
        ]);
 
         return [
             'statusCode' => Response::HTTP_ACCEPTED,
             'message' => 'Mise à jour reussi',
             'data'   => $id
         ];
    }

    public function destroy(Request $request, $id){
        $succ = Succursale::findOrFail($id);
        $succ->delete();
        return [
            "message" => 'L\'article a été supprimé avec succès.',
            "success" => true
        ];
    }
}
