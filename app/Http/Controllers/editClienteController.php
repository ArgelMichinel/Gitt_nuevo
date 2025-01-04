<?php

namespace App\Http\Controllers;

use App\Models\access_meli;
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
        
        $cliente_eliminado = clientes::where('id','=',$del_client);
        $cliente_MELI = access_meli::where('user_id','=',$cliente_eliminado);
        $cliente_MELI->delete();
        //$cliente_NUBE = access_nube::where('user_id','=',$cliente_eliminado);
        //$cliente_NUBE->delete();
        $cliente_eliminado->delete();
    
        //////////////////////////////////////
        $title='Consultar información de clientes';

        $clientes = clientes::all();

        return view('edit_cliente', compact('title', 'clientes'));
    }
}
