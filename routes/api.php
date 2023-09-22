<?php

use App\Http\Controllers\CaracteristiqueController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\SuccursaleController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['prefix' => 'breukh'], function () {
    Route::get('/succursales', [SuccursaleController::class, 'index'])->name('succursale.index');

    Route::post('/succursale', [SuccursaleController::class, 'store'])->name('succursale.store');

    Route::put('/succursale/{id}', [SuccursaleController::class, 'update'])->name('succursale.update');

    Route::delete('/succursale/{id}', [SuccursaleController::class, 'destroy'])->name('succursale.destroy');
});

Route::group(['prefix' => 'breukh'], function () {
    Route::get('/products', [ProduitController::class, 'index'])->name('product.index');

    Route::get('/product/search/{code}', [ProduitController::class, 'searchProduct'])->name('product.search');

    Route::get('/succursales/{id}/search/{code}', [ProduitController::class, 'search'])->name('product.searchwithFreind');;

    Route::post('/product', [ProduitController::class, 'store'])->name('product.store');

    Route::put('/product/{id}', [ProduitController::class, 'update'])->name('product.update');

    Route::delete('/product/{id}', [ProduitController::class, 'destroy'])->name('product.destroy');

});

Route::group(['prefix' => 'breukh'], function () {
    Route::get('/caracteristique', [CaracteristiqueController::class, 'index'])->name('caracteristiques.index');

    Route::post('/caracteristique', [CaracteristiqueController::class, 'store'])->name('caracteristiques.store');

    Route::put('/caracteristique/{id}', [CaracteristiqueController::class, 'update'])->name('caracteristiques.update');

    Route::delete('/caracteristique/{id}', [CaracteristiqueController::class, 'destroy'])->name('caracteristiques.destroy');

});

Route::group(['prefix' => 'breukh'], function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');

    Route::post('/user', [UserController::class, 'store'])->name('users.store');

    Route::put('/user/{id}', [UserController::class, 'update'])->name('users.update');

    Route::delete('/user/{id}', [UserController::class, 'destroy'])->name('users.destroy');

});

Route::group(['prefix' => 'breukh'], function () {
    Route::get('/commandes', [CommandeController::class, 'index'])->name('commandes.index');

    Route::post('/commande', [CommandeController::class, 'store'])->name('commandes.store');

    Route::put('/commande/{id}', [CommandeController::class, 'update'])->name('commandes.update');

    Route::delete('/commande/{id}', [CommandeController::class, 'destroy'])->name('commandes.destroy');

});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
