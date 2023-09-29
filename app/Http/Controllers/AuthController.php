<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserPostRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    use ResponseTrait;
    
    public function register(UserPostRequest $request)
    {
        $user = User::create([
            "nomComplet" => $request->nomComplet,
            "login" => $request->login,
            "password" =>Hash::make($request->password),
            "telephone" => $request->telephone,
            "succursale_id" => $request->succursale_id,
        ]);
        // return response($user, Response::HTTP_CREATED);
        $data=UserResource::make($user);
        return $this->formatResponse("L'utilisateur a été ajouté avec succés",$data,true,Response::HTTP_CREATED);
    }

    public function login(Request $request){
        if (!Auth::attempt($request->only("login", "password"))) {
            return response([
                "message" => "Invalid credentials"
            ], Response::HTTP_UNAUTHORIZED);
        }
        $user = Auth::user();
        $token = $user->createToken('token')->plainTextToken;
        $cookie = cookie("token", $token, 24 * 60);

        return response([
            "token" => $token,
            "user"=>UserResource::make($request->user())
        ])->withCookie($cookie);
    }

    public function user(Request $request)
    {
        return  UserResource::make($request->user());
        // return $request->user();
    }

    public function logout()
    {
        // Auth::logout();
        // Cookie::forget("token");
        Auth::guard('sanctum')->user()->tokens()->delete();
        return response([
            "message" => "Deconnexion réussie !"
        ]);
    }
}
