<?php


use App\Http\Controllers\CellarController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SpiderController;


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
})->name('welcome');

route::get('/spider', [App\Http\Controllers\SpiderController::class, 'index'])->name('spider');


Route::middleware('auth')->group(function(){
    Route::get('/profile', [UserController::class, 'profile'])->name('user.profile');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('user.destroy');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('user.update');
});
// Registration Routes
Route::get('/registration', [UserController::class, 'create'])->name('user.create');
Route::post('/registration', [UserController::class, 'store'])->name('user.store');

// Authentication Routes
Route::get('/login', [AuthController::class, 'create'])->name('login');
Route::post('/login', [AuthController::class, 'store'])->name('login.store');
Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');

// Profile Page (requires login)
Route::middleware('auth')->get('/profile', [UserController::class, 'profile'])->name('user.profile');

Route::get('/password/forgot', [UserController::class, 'forgot'])->name('user.forgot');
Route::post('/password/forgot', [UserController::class, 'email'])->name('user.email');
Route::get('/password/reset/{user}/{token}', [UserController::class, 'reset'])->name('user.reset');
Route::put('/password/reset/{user}/{token}', [UserController::class, 'resetUpdate'])->name('user.reset.update');


Route::get('/cellier', [CellarController::class, 'index'])->name('cellar.index');
Route::get('/creer/cellier', [CellarController::class, 'create'])->name('cellar.create');
Route::post('/creer/cellier', [CellarController::class, 'store'])->name('cellar.store');
Route::get('/modifier/cellier/{cellar}', [CellarController::class, 'edit'])->name('cellar.edit');
Route::put('/modifier/cellier/{cellar}', [CellarController::class, 'update'])->name('cellar.update');
Route::delete('supprimer/cellier/{cellar}', [CellarController::class, 'destroy'])->name('cellar.delete');


