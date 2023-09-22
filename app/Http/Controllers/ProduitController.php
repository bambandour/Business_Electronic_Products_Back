<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProduitResource;
use App\Models\Produit;
use App\Models\Succursale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\ResponseTrait;
use Symfony\Component\HttpFoundation\Response;

class ProduitController extends Controller
{
    use ResponseTrait;
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
            return $this->formatResponse('Une erreur est survenue lors de l\'ajout du produit.', [], false, 500,$e->getMessage());
        }

        $data = [new ProduitResource($produit)];
        return $this->formatResponse('Le produit a été ajouté avec succés', $data, true,200);
    }

    public function searchProduct($productCode) {
        try {
            
            $localProduct= Produit::where('code',"$productCode")
            ->with(['produit_succursales'=> function($query){
                $query->where('succursale_id',1)
                ->where('quantite', '>', 0);
            }])->first();
            //  return $localProduct;

            // $localProduct = Produit::where('code', $productCode)
            //     ->whereHas('produit_succursales', function ($query) {
            //         $query->where('succursale_id', 1)
            //     ->where('quantite', '>', 0);
            //     })
            //     ->first();

            // $localProduct = Produit::where('code', 'like', "$productCode")->bySuccursale(1)->with('produit_succursales')
            //     ->first();
            //     return $localProduct; 
            
            if ($localProduct ) {
                $data = [new ProduitResource($localProduct)];
                return $this->formatResponse('Produit trouvé dans mon succursale.', $data, true);
            }
            else {
                $amieSuccursales = Succursale::
                // with(['relations'=>function($query) {
                //         $query->where('estAmis', true)
                //               ->where('demandeur_id', 1);
                //     }])->pluck('id')->toArray();
                //     dd($amieSuccursales);          

                whereHas('relations', function($query) {
                    $query->where('estAmis', true)
                          ->where('demandeur_id', 1);
                })->pluck('id')->toArray();
                
                $amieProducts = Produit::where('code', $productCode)
                // ->with(['produit_succursales', function($query)use($amieSuccursales){
                //     $query->whereIn('succursale_id', $amieSuccursales);
                // }])->get();
                ->whereHas('produit_succursales', function($query) use ($amieSuccursales) {
                    $query->whereIn('succursale_id', $amieSuccursales);
                })
                ->get();
    
                if ($amieProducts->isEmpty()) {
                    return $this->formatResponse('Le produit n\'est pas disponible.', [], false);
                } else {
                    $data = ProduitResource::collection($amieProducts);
                    return $this->formatResponse('Produit trouvé chez les succursales amies.', $data, true);
                }
            }
        } catch (\Exception $e) {
            return $this->formatResponse('Une erreur est survenu lors de la recherche du produit en question.', [], false,500,$e->getMessage());
        }
    }

    public function search(string $id, string $code)
    {
        $limit = request()->query('limit');

        $produit = Produit::where("code", $code)->first();
        if (!$produit) {
            return response(["message" => "code introuvable"], Response::HTTP_NOT_FOUND);
        }
        $hisProduit = DB::table('produit_succursales')->where(['succursale_id' => $id, "produit_id" => $produit->id])->where('quantite', '>', 0)->first();
        if (!$hisProduit){
            $ids = Succursale::myFriends($id)->map(function ($a) {
                return $a->id;
            });
            
            // $produit = Produit::with(['succursales' => function ($q) use ($ids, $limit) {
            //     $q->whereIn('succursale_id', $ids)->where('quantite', ">", 0)->orderBy('prix_gros', "asc")
            //         ->when($limit, fn ($q) => $q->limit($limit));
            // }, 'caracteristiques'])->where('code', $code)->first();
            
            $produit = Produit::quantitePositive($ids,$limit , $code)->first();
            return $produit;
        }
        $produit = Produit::with(['produit_succursales' => function ($q) use ($id) {
            $q->where('succursale_id', $id);
        }, 'caracteristiques'])->where('code', $code)->first();
        return $produit;
    }
    
}
