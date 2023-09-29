<?php

namespace App\Http\Controllers;

use App\Http\Resources\CaracteristiqueResource;
use App\Http\Resources\CategorieResource;
use App\Http\Resources\MarqueResource;
use App\Http\Resources\ProduitResource;
use App\Models\Caracteristique;
use App\Models\Categorie;
use App\Models\Marque;
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
    public function all(){
        $marques =Marque::all();
        $categories =Categorie::all();
        $caracteristiques =Caracteristique::all();
        // $produits=Produit::all();
        return [
            "marques"=>MarqueResource::collection($marques) ,
            "categories"=> CategorieResource::collection($categories),
            "caracteristiques"=>CaracteristiqueResource::collection($caracteristiques),
        ];
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
            "description"=>$request->description,
            "marque_id"=>$request->marque,
            "categorie_id"=>$request->categorie
        ]);
        
        $produit->produits()->attach($request->succursales);

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
            
            // $localProduct= Produit::where('code',"$productCode")
            // ->with(['produits'=> function($query){
            //     $query->where('succursale_id',1)
            //     ->where('quantite', '>', 0);
            // }])->first();
            //  return $localProduct;

            $localProduct = Produit::where('code', $productCode)
                ->whereHas('produits', function ($query) {
                    $query->where('succursale_id', 1)
                ->where('quantite', '>', 0);
                })
                ->first();

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
                          ->where('succursale_id', 1)
                          ->orWhere('demandeur_id', 1);
                })->pluck('id')->toArray();
                
                $amieProducts = Produit::where('code', $productCode)
                // ->with(['produit_succursales', function($query)use($amieSuccursales){
                //     $query->whereIn('succursale_id', $amieSuccursales);
                // }])->get();
                ->whereHas('produits', function($query) use ($amieSuccursales) {
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

    public function search($id, string $code){
        try {
        $limit = request()->query('limit');

        $produit = Produit::where("code", $code)->first();
        if (!$produit) {
            return $this->formatResponse(' le code du produit est introuvable', [], false,Response::HTTP_NOT_FOUND);
        }
        $hisProduit = DB::table('produits')->where(['succursale_id' => $id, "produit_id" => $produit->id])->where('quantite', '>', 0)->first();
        if (!$hisProduit){
            $tab=[];
            $ids = Succursale::myFriends($id)->map(function ($a)use($tab ,$id) {
                if($a->demandeur_id!==$id){
                    return  $tab[]=$a->succursale_id;
                }
                
                if($a->succursale_id!==$id){
                    return $tab[]=$a->demandeur_id;  
                }
                return $tab;
            });
            // return $ids;
                $produit = Produit::quantitePositive($ids,$limit , $code)->first();
                return ProduitResource::make($produit);
            }
            $produit = Produit::with(['produits' => function ($q) use ($id) {
                $q->where('succursale_id', $id);
            }, 'caracteristiques'])->where('code', $code)->first();
        } catch (\Exception $e) {
        
        }
        return ProduitResource::make($produit);
        
    }

    // public function showProductBySuccursale($succursaleId){
    //     $produit = Produit::with(['produits' => function ($query) use ($succursaleId) {
    //         $query->where('succursale_id', $succursaleId);
    //     }])->get();
    //     return ProduitResource::collection($produit);
    // }

    public function showProductBySuccursale($succursaleId) {
        $suc=Succursale::find($succursaleId);
        if ($suc) {
            $produits = Produit::whereHas('produits', function ($q) use ($succursaleId) {
                $q->where('succursale_id', $succursaleId);
            })->with(['produits', 'caracteristiques'])->get();
            $data= ProduitResource::collection($produits);
            return $this->formatResponse('La liste des produits trouvés dans mon succursale.', $data, true);
        }else{
            return $this->formatResponse('La succursale n\'existe pas .', [], false);
        }
        
    }
    
    
}
