<?php

namespace App\Http\Controllers;

use App\Models\access_meli;
use App\Models\access_nube;
use App\Models\clientes;
use Illuminate\Http\Request;

class editClienteController extends Controller
{
    public function mostrar() {
        $title='Consultar información de clientes';

        $clientes = clientes::all();

        return view('edit_cliente', compact('title', 'clientes'));
    }

    public function delet_client() {
        $del_client = request()->input('del_client');
        
        $cliente_eliminado = clientes::where('id','=',$del_client)->first();
        
        if ($cliente_eliminado['id_MELI']) {        /// Elimina cliente de Mercadolibre
            $cliente_MELI = access_meli::where('user_id','=',$cliente_eliminado['id_MELI'])->first();
            $cliente_MELI->delete();
        }
        if ($cliente_eliminado['id_TN']) {        /// Elimina cliente de Tienda Nube
            $cliente_NUBE = access_nube::where('user_id','=',$cliente_eliminado['id_TN'])->first();
            $cliente_NUBE->delete();
        }
        
        $cliente_eliminado->delete();
    
        //////////////////////////////////////
        $title='Consultar información de clientes';

        $clientes = clientes::all();

        return view('edit_cliente', compact('title', 'clientes'));
    }
}
