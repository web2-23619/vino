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


Route::get('/cellar', [CellarController::class, 'index'])->name('cellar.index');
Route::delete('/cellar/{task}', [CellarController::class, 'destroy'])->name('cellar.delete');