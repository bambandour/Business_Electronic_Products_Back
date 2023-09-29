<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategorieResource;
use App\Models\Categorie;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    public function index(){
        $cat=Categorie::all();
        return CategorieResource::collection($cat);
    }

    public function store(Request $request){
        $marque=Categorie::create([
            "libelle"=>$request->libelle
        ]);
        return CategorieResource::make($marque);
    }

}
