<?php

namespace App\Http\Controllers;

use App\Models\cadetes;
use App\Models\clientes;
use App\Models\envios;
use Illuminate\Http\Request;

class ControllerPackets extends Controller
{
    //
    public function mostrarenvios() {
        $title='Consulta de paquetes';
        $packets = envios::latest()->take(300)->get();;
        $clients = clientes::all();
        $cadetes = cadetes::all();
        
        return view('mostrar_envios',compact('packets', 'clients', 'cadetes'));

    }
}
