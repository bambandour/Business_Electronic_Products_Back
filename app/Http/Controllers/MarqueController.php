<?php

namespace App\Http\Controllers;

use App\Http\Resources\MarqueResource;
use App\Models\Marque;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class MarqueController extends Controller
{
    use ResponseTrait;
    public function index(){
        $marque=Marque::all();
        return MarqueResource::collection($marque);
    }

    public function store(Request $request){
        $marque=Marque::create([
            "libelle"=>$request->libelle
        ]);
        return MarqueResource::make($marque);
    }
}
