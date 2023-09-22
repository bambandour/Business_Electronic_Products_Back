<?php

namespace App\Http\Controllers;

use App\Models\Caracteristique;
use Illuminate\Http\Request;

class CaracteristiqueController extends Controller
{
    public function index(){
        return Caracteristique::all();
    }
    public function store(Request $request){
        $car=Caracteristique::create([
            "libelle"=>$request->libelle,
        ]);
        return $car;
    }
}
