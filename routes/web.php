<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentController;
use Illuminate\Support\Facades\Auth;

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

// Rutas de autenticación

/* Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [DocumentController::class, 'index'])->name('home');
    Route::resource('document', DocumentController::class)->except('destroy');
    Route::get('/document/{id}', [DocumentController::class, 'destroy'])->name('document.destroy');
    Route::get('/download/{id}', [DocumentController::class, 'downloadDocument'])->name('download');
});
Route::get('/logout', [LoginController::class, 'logout'])->name('logout'); */

Auth::routes();

Route::get('/home', [DocumentController::class, 'index'])->name('home');
Route::get('/',[DocumentController::class, 'index'])->middleware('auth');

Route::get('/home', [DocumentController::class, 'index'])->name('home')->middleware('auth');

Route::resource('document',DocumentController::class)->middleware('auth');

Route::get('/document/{id}',[DocumentController::class,'destroy'])->name('document.destroy')->middleware('auth');

Route::get('/download/{id}',[DocumentController::class, 'downloadDocument'])->name('download')->middleware('auth');

Route::post('/logout',[LoginController::class,'logout'])->name('logout'); 

Route::get('/login',function (){
    return view('auth.login');
})->name('login');





