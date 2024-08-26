<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController as AuthLoginController;
use App\Http\Controllers\ControllerPackets;
use Illuminate\Support\Facades\Auth;

Route::get('/', [AuthLoginController::class,'ingreso'])->name('login');
Route::get('admin/', [AuthLoginController::class,'ingreso_admin'])->name('login_admin');
Route::get('cadete/', [AuthLoginController::class,'ingreso_cadete'])->name('login_cadete');
Route::post('/',[AuthLoginController::class,'authenticate'])->name('validar');
Route::get('/logout',[AuthLoginController::class,'logout'])->name('logout');
Route::get('/logged_packets',[ControllerPackets::class,'mostrar'])->name('mostrarenvios');



Route::get('/desktop_administrador', function(){
    return 'Ingreso valido administrador';
})->middleware('auth:administ')->name('desk_admin');

Route::get('/desktop_clientes', function(){
    return 'Ingreso valido clientes';
})->middleware('auth:clientes')->name('desk_client');

