<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProduitResource;
use App\Models\Produit;
use App\Models\Succursale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProduitController extends Controller
{
    public function index(){
        $suc=Produit::all(); 
        return ProduitResource::collection($suc);
    }
    public function store(Request $request){
        DB::beginTransaction();
        try{
        $filename='';
        if ($request->hasFile('photo')) {
            $filename=$request->getSchemeAndHttpHost().'/assets/img/'.time().'.'.$request->photo->extension();
            $request->photo->move(public_path('/assets/img/'),$filename);
        }

        $produit=Produit::create([
            "libelle"=>$request->libelle,
            "code"=>$request->code,
            "photo"=>$filename,
            "description"=>$request->description
        ]);

        $produit->produit_succursales()->attach($request->succursales);

        $produit->caracteristiques()->attach($request->caracteristiques);

        DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return [
                "message" => 'Une erreur est survenue lors de l\'ajout du produit.',
                "error" => $e->getMessage(),
                "success" => false
            ];
        }
        return [
            "message" => 'Le produit a été ajouté avec succès.',
            "data" => [$produit],
            "success" => true
        ];
    }

    public function search($code){
        $lib=Produit::where('code',$code)->first();
        $length=\Illuminate\Support\Str::length($code);
        if ($length<3) {
            return response()->json([
                "data"=>[null]
            ]);
        }
        if (!$lib) {
            return response()->json([
                "data"=>[]
            ]);
        }
        return response()->json([
            "data"=>[$lib]
        ]);
    }

    public function searchProduct(Request $request) {
        try {
            $productCode = $request->productCode;
    
            $localProduct = Produit::where('code', 'like', "%$productCode%")
                ->whereHas('produit_succursales', function($query) {
                    $query->where('succursale_id', 1);
                })
                ->first();
    
            if ($localProduct) {
    
                return [
                    "message" => 'Produit trouvé dans mon succursale.',
                    "data" => [$localProduct],
                    "success" => true
                ];
            } else {
                $amieSuccursales = Succursale::whereHas('relations', function($query) {
                    $query->where('estAmis', true)
                          ->where('demandeur_id', 1);
                })->pluck('id')->toArray();
    
                $amieProducts = Produit::where('code', 'like', "%$productCode%")
                    ->whereHas('produit_succursales', function($query) use ($amieSuccursales) {
                        $query->whereIn('succursale_id', $amieSuccursales);
                    })
                    ->get();
    
                if ($amieProducts->isEmpty()) {
                    return [
                        "message" => 'Le produit n\'est pas disponible.',
                        "success" => false
                    ];
                } else {
                    return [
                        "message" => 'Produit trouvé chez les succursales amies.',
                        "data" => $amieProducts,
                        "success" => true
                    ];
                }
            }
        } catch (\Exception $e) {
            return [
                "message" => 'Une erreur est survenue lors de la recherche du produit.',
                "error" => $e->getMessage(),
                "success" => false
            ];
        }
    }
    
}
