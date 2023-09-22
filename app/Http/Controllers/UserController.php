<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(){
        return User::all();
    }

    public function store(Request $request){
        $user=User::create([
            "nomComplet"=>$request->nomComplet,
            "login"=>$request->login,
            "password"=>$request->password,
            "telephone"=>$request->telephone,
            "succursale_id"=>$request->succursale_id,
        ]);
        return $user;
    }
}
