<?php


use App\Http\Controllers\CellarController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PurchaseController;
use Illuminate\Support\Facades\Auth;
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

route::get('/spider', [App\Http\Controllers\SpiderController::class, 'index'])->name('spider');

Route::get('/', function () {
	if (!Auth::check()) {
		return view('welcome');;
	} else {
		return redirect()->route('cellar.index');
	}
})->name('welcome');

Route::middleware('guest')->group(function () {

	// Route enregistrement
	Route::get('/registration', [UserController::class, 'create'])->name('user.create');
	Route::post('/registration', [UserController::class, 'store'])->name('user.store');

	// Route connexion
	Route::get('/login', [AuthController::class, 'create'])->name('login');
	Route::post('/login', [AuthController::class, 'store'])->name('login.store');

	// Route mot de passe oublié
	Route::get('/password/forgot', [UserController::class, 'forgot'])->name('user.forgot');
	Route::post('/password/forgot', [UserController::class, 'email'])->name('user.email');
	Route::get('/password/reset/{user}/{token}', [UserController::class, 'reset'])->name('user.reset');
	Route::put('/password/reset/{user}/{token}', [UserController::class, 'resetUpdate'])->name('user.reset.update');
});

Route::middleware('auth')->group(function () {

	Route::get('/profile', [UserController::class, 'profile'])->name('user.profile');
	Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('user.edit');
	Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('user.destroy');
	Route::put('/users/{user}', [UserController::class, 'update'])->name('user.update');

	Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');
	Route::middleware('auth')->get('/profile', [UserController::class, 'profile'])->name('user.profile');

	Route::get('/cellier', [CellarController::class, 'index'])->name('cellar.index');
	Route::get('/creer/cellier', [CellarController::class, 'create'])->name('cellar.create');
	Route::post('/creer/cellier', [CellarController::class, 'store'])->name('cellar.store');
	Route::get('/modifier/cellier/{cellar}', [CellarController::class, 'edit'])->name('cellar.edit');
	Route::put('/modifier/cellier/{cellar}', [CellarController::class, 'update'])->name('cellar.update');
	Route::delete('supprimer/cellier/{cellar}', [CellarController::class, 'destroy'])->name('cellar.delete');

	Route::get('/mesBouteilles', [UserController::class, 'showBottles'])->name('user.showBottles');
	Route::get('/cellier/{cellar}/bouteille', [CellarController::class, 'showBottles'])->name('cellar.showBottles');

	Route::get('/listeAchat', [PurchaseController::class, 'index'])->name('purchase.index');

});
