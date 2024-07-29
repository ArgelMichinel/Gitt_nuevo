<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController as AuthLoginController;
use Illuminate\Support\Facades\Auth;

Route::get('/', [AuthLoginController::class,'ingreso'])->name('login');
Route::post('/',[AuthLoginController::class,'authenticate'])->name('validar');
Route::get('/logout',[AuthLoginController::class,'logout'])->name('logout');



Route::get('/desktop', function(){
    return 'Ingreso valido';
})->middleware('auth')->name('login_client');
