<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CellarController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [AuthController::class, 'apiLogin']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'apiLogout']);

Route::middleware('auth:sanctum')->delete('supprimer/achat/{purchase}', [PurchaseController::class, 'destroy']);
Route::middleware('auth:sanctum')->get('afficher/achat', [PurchaseController::class, 'AllPurchaseApi']);
Route::delete('/supprimer/utilisateur/{id}', [UserController::class, 'destroy'])->name('user.destroy');
Route::middleware('auth:sanctum')->delete('supprimer/cellier/{cellar}', [CellarController::class, 'destroy']);
Route::middleware('auth:sanctum')->delete('retirer/{cellar_id}/{bottle_id}', [CellarController::class, 'apiRemoveBottle']);
Route::middleware('auth:sanctum')->patch('cellier/{cellar_id}/{bottle_id}', [CellarController::class, 'updateQuantityApi']);
Route::middleware('auth:sanctum')->get('cellier/{cellar}/bouteille', [CellarController::class, 'showBottlesApi']);
Route::middleware('auth:sanctum')->patch('achat/{purchase}/quantite', [PurchaseController::class, 'updateQuantityApi']);
Route::middleware('auth:sanctum')->patch('/mesBouteilles', [UserController::class, 'updateQuantityApi']);
