<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CellarController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;


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


Route::middleware('auth')->group(function(){
    Route::get('/users', [UserController::class, 'index'])->name('user.index');
    Route::get('/profile', [UserController::class, 'profile'])->name('user.profile');
});
Route::get('/registration', [UserController::class, 'create'])->name('user.create');
Route::post('/registration', [UserController::class, 'store'])->name('user.store');



Route::get('/password/forgot', [UserController::class, 'forgot'])->name('user.forgot');
Route::post('/password/forgot', [UserController::class, 'email'])->name('user.email');
Route::get('/password/reset/{user}/{token}', [UserController::class, 'reset'])->name('user.reset');
Route::put('/password/reset/{user}/{token}', [UserController::class, 'resetUpdate'])->name('user.reset.update');

Route::get('/login', [AuthController::class, 'create'])->name('login');
Route::post('/login', [AuthController::class, 'store'])->name('login.store');
Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');


Route::get('/cellier', [CellarController::class, 'index'])->name('cellar.index');
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


