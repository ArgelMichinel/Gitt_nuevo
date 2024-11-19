<?php

use App\Http\Controllers\AddAdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController as AuthLoginController;
use App\Http\Controllers\Controller_QRgenerator;
use App\Http\Controllers\ControllerPackets;
use App\Http\Controllers\ControllerAsignar;
use App\Http\Controllers\UserAdminController;
use Illuminate\Support\Facades\Auth;

Route::get('/', [AuthLoginController::class,'ingreso'])->name('login');
Route::post('/',[AuthLoginController::class,'authenticate'])->name('validar');
Route::get('admin/', [AuthLoginController::class,'ingreso'])->name('login_admin');
Route::post('admin/',[AuthLoginController::class,'authenticate'])->name('login_admin');
Route::get('cadete/', [AuthLoginController::class,'ingreso'])->name('login_cadete');
Route::get('/logout',[AuthLoginController::class,'logout'])->name('logout');
Route::get('/admin/logged_packets',[ControllerPackets::class,'mostrarenvios'])->name('mostrarenvios');  //bloquear solo admin
Route::post('/admin/logged_packets',[ControllerPackets::class,'crearlista']);  //bloquear solo admin
Route::get('/admin/assign_packets',[ControllerAsignar::class,'ingresoGet'])->name('asignar');  //bloquear solo admin
Route::get('/admin/QRgenerator/{tag}',[Controller_QRgenerator::class, 'generarQR'])->name('Genera_QR');  //Bloquear sólo admin
Route::get('/admin/user_admin',[UserAdminController::class,'mostrarAdmin'])->name('mostrarAdmin');  //bloquear solo admin
Route::post('/admin/user_admin',[UserAdminController::class,'borrarAdmin']);  //bloquear solo admin
Route::get('/admin/add_admin',[AddAdminController::class,'formuAddAdmin'])->name('aniadirAdmin');  //bloquear solo admin
Route::post('/admin/add_admin',[AddAdminController::class,'someterAdmin']);  //bloquear solo admin



Route::get('/admin/desktop_administrador', function(){
    return 'Ingreso valido administrador';
})->middleware('auth:administ')->name('desk_admin');

Route::get('/desktop_clientes', function(){
    return 'Ingreso valido clientes';
})->middleware('auth:clientes')->name('desk_client');

