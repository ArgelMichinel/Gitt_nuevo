<?php

namespace App\Http\Controllers;

use App\Models\access_meli;
use App\Models\access_nube;
use App\Models\clientes;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;

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

    public function agregar_get() {
        $title='Agregar Cliente';

        return view('agregar_cliente', compact('title'));
    }

    public function agregar_post() {
        $request = request()->input();

        $cliente = new clientes();
        $cliente->name = $request['cliente']['name'];
        $cliente->email = $request['cliente']['email'];
        $cliente->password = rand(100000000,999999999);

        Try {
            $cliente->save();
            $title='Cliente añadido con éxito';
            $mensaje = 'El cliente '.$cliente->name.' ha sido añadido con éxito a la base de datos.';

            $act_cliente = clientes::where('id','=',$cliente["id"])->get()->first();
            $act_cliente->id_Gitt = 'G'. $cliente["id"];
            $act_cliente->save();

        } catch (\Exception $e) {
            $title='Error al agregar cliente';
            $mensaje = 'Ha ocurrido un error al intentar agregar el cliente. ' . $e->getMessage();
        }

        return view('added_cliente_success', compact('title', 'mensaje'));

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
