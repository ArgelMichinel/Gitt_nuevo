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
use App\Http\Controllers\editListController;
use App\Http\Controllers\infoPackMeliController;
use App\Http\Controllers\savePackController;
use App\Http\Controllers\testerController;
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
Route::get('/admin/logged_packets',[ControllerPackets::class,'mostrarenvios'])->middleware('auth:administ')->name('mostrarenvios');
Route::post('/admin/logged_packets',[ControllerPackets::class,'crearlista'])->middleware('auth:administ'); 
Route::get('/admin/assign_packets',[ControllerAsignar::class,'ingresoGet'])->name('asignar');  //bloquear solo admin
Route::post('/admin/assign_packets',[ControllerAsignar::class,'AsignarPost']);  //bloquear solo admin
Route::get('/admin/QRgenerator/{tag}',[Controller_QRgenerator::class, 'generarQR'])->name('Genera_QR');  //Bloquear sólo admin
Route::get('/admin/user_admin',[UserAdminController::class,'mostrarAdmin'])->name('mostrarAdmin');  //bloquear solo admin
Route::post('/admin/user_admin',[UserAdminController::class,'borrarAdmin']);  //bloquear solo admin
Route::get('/admin/add_admin',[AddAdminController::class,'formuAddAdmin'])->name('aniadirAdmin');  //bloquear solo admin
Route::post('/admin/add_admin',[AddAdminController::class,'someterAdmin']);  //bloquear solo admin
Route::get('/admin/check_ingresado',[checkPackController::class,'check'])->name('checkearIngreso');  //bloquear solo admin
Route::get('/admin/show_cadete',[editCadeteController::class,'mostrar'])->middleware('auth:administ')->name('mostrarCadete');
Route::get('/admin/include_cadete',[editCadeteController::class,'planilla_cadete'])->middleware('auth:administ')->name('registrarCadete');
Route::post('/admin/include_cadete',[editCadeteController::class,'registrar_cadete'])->middleware('auth:administ');
Route::get('/admin/edit_cadete',[editCadeteController::class,'editar'])->middleware('auth:administ')->name('editarCadete');
Route::post('/admin/edit_cadete',[editCadeteController::class,'delet_cadete'])->middleware('auth:administ');
Route::get('/admin/show_list',[editListController::class,'mostrar'])->middleware('auth:administ')->name('mostrarListas');
Route::post('/admin/show_list',[editListController::class,'delet_lista'])->middleware('auth:administ');
Route::get('/admin/include_packets',[ControllerPackets::class,'include_packets'])->name('incluirEnvio');  //bloquear solo admin
Route::get('/admin/edit_client',[editClienteController::class,'mostrar'])->middleware('auth:administ')->name('infoCliente');
Route::post('/admin/edit_client',[editClienteController::class,'delet_client'])->middleware('auth:administ');
Route::get('/admin/info_packets/',[infoPackMeliController::class,'info_packets_get'])->name('info_packets');  //bloquear solo para admin
Route::post('/admin/info_packets/',[infoPackMeliController::class,'info_packets_post']);  //bloquear solo para admin
Route::get('/admin/update_packets',[ControllerPackets::class,'mostrarUpdate'])->middleware('auth:administ')->name('mostrarUpdate');
Route::post('/admin/update_packets',[ControllerPackets::class,'post_update'])->middleware('auth:administ'); 
Route::post('/admin/save_packet',[savePackController::class,'save_pack'])->middleware('auth:administ'); 
Route::post('/admin/save_packets_u',[savePackController::class,'save_pack_actua'])->middleware('auth:administ'); 
//Route::post('/admin/prueba',[savePackController::class,'prueba'])->middleware('auth:administ'); 
//Route::get('/admin/prueba',[savePackController::class,'prueba'])->middleware('auth:administ'); 
Route::get('/clientes/logged_packets',[ClientesPackController::class,'mostrarenvios'])->middleware('auth:clientes')->name('mostrarenvios_cientes');  //bloquear solo clientes
Route::get('/clientes/grant_permission',[IntegracionController::class,'otorgarPermiso'])->middleware('auth:clientes')->name('grant_permission');  //bloquear solo clientes
//////
Route::get('creartester/', [testerController::class,'generar']);

