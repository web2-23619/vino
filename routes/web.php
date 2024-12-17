<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CellarController;

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
    return view('welcome');
});



// Affiche la liste des celliers
Route::get('/cellars', [CellarController::class, 'index'])->name('cellars.index');

// Affiche le formulaire de création d'un nouveau cellier
Route::get('/cellars/create', [CellarController::class, 'create'])->name('cellars.create');
    
// Enregistre un nouveau cellier
Route::post('/cellars', [CellarController::class, 'store'])->name('cellars.store');

// Affiche le formulaire d'édition pour un cellier spécifique
Route::get('/cellars/{cellar}/edit', [CellarController::class, 'edit'])->name('cellars.edit');

// Met à jour un cellier spécifique
Route::put('/cellars/{cellar}', [CellarController::class, 'update'])->name('cellars.update');

