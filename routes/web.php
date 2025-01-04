<?php

use App\Http\Controllers\AddAdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController as AuthLoginController;
use App\Http\Controllers\Controller_QRgenerator;
use App\Http\Controllers\ControllerPackets;
use App\Http\Controllers\ControllerAsignar;
use App\Http\Controllers\UserAdminController;
use App\Http\Controllers\checkPackController;
use App\Http\Controllers\ClientesPackController;
use App\Http\Controllers\IntegracionController;
use App\Http\Controllers\editCadeteController;
use App\Http\Controllers\editClienteController;
use App\Http\Controllers\infoPackMeliController;
use Illuminate\Support\Facades\Auth;

Route::get('/', [AuthLoginController::class,'ingreso'])->name('login');
Route::post('/',[AuthLoginController::class,'authenticate']);
Route::get('admin/', [AuthLoginController::class,'ingreso'])->name('login_admin');
Route::post('admin/',[AuthLoginController::class,'authenticate']);
Route::get('cadete/', [AuthLoginController::class,'ingreso'])->name('login_cadete');
Route::get('/logout',[AuthLoginController::class,'logout'])->name('logout');
Route::get('integracion', [IntegracionController::class,'Integracion'])->name('integracion');
Route::get('integracion/MELI', [IntegracionController::class,'IntegrarMELI'])->name('integrar_MELI');
Route::post('integracion/MELI', [IntegracionController::class,'IntegrarMELI_respu']);
Route::get('/admin/logged_packets',[ControllerPackets::class,'mostrarenvios'])->name('mostrarenvios');  //bloquear solo admin
Route::post('/admin/logged_packets',[ControllerPackets::class,'crearlista']);  //bloquear solo admin
Route::get('/admin/assign_packets',[ControllerAsignar::class,'ingresoGet'])->name('asignar');  //bloquear solo admin
Route::post('/admin/assign_packets',[ControllerAsignar::class,'AsignarPost']);  //bloquear solo admin
Route::get('/admin/QRgenerator/{tag}',[Controller_QRgenerator::class, 'generarQR'])->name('Genera_QR');  //Bloquear sólo admin
Route::get('/admin/user_admin',[UserAdminController::class,'mostrarAdmin'])->name('mostrarAdmin');  //bloquear solo admin
Route::post('/admin/user_admin',[UserAdminController::class,'borrarAdmin']);  //bloquear solo admin
Route::get('/admin/add_admin',[AddAdminController::class,'formuAddAdmin'])->name('aniadirAdmin');  //bloquear solo admin
Route::post('/admin/add_admin',[AddAdminController::class,'someterAdmin']);  //bloquear solo admin
Route::get('/admin/check_ingresado',[checkPackController::class,'check'])->name('checkearIngreso');  //bloquear solo admin
Route::get('/admin/manage_cadete',[editCadeteController::class,'mostrar'])->name('mostrarCadete');  //bloquear solo admin
Route::post('/admin/manage_cadete',[editCadeteController::class,'delet_cadete']);  //bloquear solo admin
Route::get('/admin/include_packets',[ControllerPackets::class,'include_packets'])->name('incluirEnvio');  //bloquear solo admin
Route::get('/admin/edit_client',[editClienteController::class,'mostrar'])->name('editarCliente');  //bloquear solo admin
Route::get('/admin/info_packets/',[infoPackMeliController::class,'info_packets_get'])->name('info_packets');  //bloquear solo para admin
Route::post('/admin/info_packets/',[infoPackMeliController::class,'info_packets_post']);  //bloquear solo para admin
Route::get('/clientes/logged_packets',[ClientesPackController::class,'mostrarenvios'])->middleware('auth:clientes')->name('mostrarenvios_cientes');  //bloquear solo clientes
Route::get('/clientes/grant_permission',[IntegracionController::class,'otorgarPermiso'])->middleware('auth:clientes')->name('grant_permission');  //bloquear solo clientes


Route::get('/admin/desktop_administrador', function(){
    return 'Ingreso valido administrador';
})->middleware('auth:administ')->name('desk_admin');

/* Route::get('/desktop_clientes', function(){
    return 'Ingreso valido clientes';
})->middleware('auth:clientes')->name('desk_client'); */

