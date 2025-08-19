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
        //dd($clientes);

        return view('edit_cliente', compact('title', 'clientes'));
    }

    public function delet_client() {
        $del_client = request()->input('del_client');
        
        $cliente_eliminado = clientes::where('id','=',$del_client)->first();
        
        if ($cliente_eliminado['id_MELI'] != null) {        /// Elimina cliente de Mercadolibre
            //dd((int) $cliente_eliminado['id_MELI']);
            $cliente_MELI = access_meli::where('user_id','=', (int) $cliente_eliminado['id_MELI'])->first();
            //dd($cliente_MELI);
            $cliente_MELI->delete();
        }
        if ($cliente_eliminado['id_TN'] != null) {        /// Elimina cliente de Tienda Nube
            $cliente_NUBE = access_nube::where('user_id','=',(int) $cliente_eliminado['id_TN'])->first();
            $cliente_NUBE->delete();
        }
        
        $cliente_eliminado->delete();
    
        //////////////////////////////////////
        $title='Consultar información de clientes';

        $clientes = clientes::all();

        return view('edit_cliente', compact('title', 'clientes'));
    }

    public function planilla_cliente() {
        
        $request = request()->input();

        if (isset($request['id'])) {
            $cliente = clientes::where('id','=',$request['id'])->first()->toArray();
            $title='Actualizar Cliente';
        } else {
            redirect('infoCliente');
        }

        return view('actual_cliente', compact('title','cliente'));
    }


    public function ActualizarCliente() {
        $request = request()->input();
        
        $cliente_formu = $request['cliente'];
        
        $cliente = clientes::where('id','=',$cliente_formu['id'])->first();
        
        $cliente->name = $cliente_formu['name'];

        $cliente -> save();
    
        //////////////////////////////////////
        $title='Actualización de cliente';
        
        return view('update_cliente_success', compact('title','cliente'));
    }
}
