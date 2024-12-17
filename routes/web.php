<?php

use App\Http\Controllers\CellarController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('layouts/app');
});


route::get('/spider', [App\Http\Controllers\SpiderController::class, 'index'])->name('spider');

Route::delete('supprimer/cellier/{cellar}', [CellarController::class, 'destroy'])->name('cellar.delete');

// Affiche la liste des celliers
Route::get('/cellier', [CellarController::class, 'index'])->name('cellars.index');

// Affiche le formulaire de création d'un nouveau cellier
Route::get('/create/cellier', [CellarController::class, 'create'])->name('cellars.create');
    
// Enregistre un nouveau cellier
Route::post('/create/cellier', [CellarController::class, 'store'])->name('cellars.store');

// Affiche le formulaire d'édition pour un cellier spécifique
Route::get('/edit/cellier/{cellar}', [CellarController::class, 'edit'])->name('cellars.edit');

// Met à jour un cellier spécifique
Route::put('/edit/cellier/{cellar}', [CellarController::class, 'update'])->name('cellars.update');

