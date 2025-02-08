<?php

namespace App\Http\Controllers;

use App\Models\cadetes;
use Illuminate\Http\Request;

class editCadeteController extends Controller
{
    public function editar() {
        $title='Editar Cadete';

        $cadetes = cadetes::all();

        return view('edit_cadete', compact('title', 'cadetes'));
    }

    public function mostrar() {
        $title='Lista de cadetes';

        $cadetes = cadetes::all()->toArray();

        //dd($cadetes[0]); 
        //Hay que acomodar la vista porque no muestra bien la tabla de los cadetes. http://localhost/gitt_nuevo/getittoday/public/admin/show_cadete

        return view('list_cadete', compact('title', 'cadetes'));
    }

    public function delet_cadete() {
        $id_cade = request()->input('cadete');

        $cadete_eliminado = cadetes::where('num_cadete','=',$id_cade);
        $cadete_eliminado->delete();
    
        //////////////////////////////////////
        $title='Cadete Eliminado';
        return view('deleted_Cadete', compact('title'));
    }

    public function registrar_cadete() {
        $request = request()->input();
        
        $new_cadete = $request['new_cadete'];
            
        $bande = 1; // indica que el cadete existe
        
        if ($new_cadete['num_cadete'] == "" ) {
            $bande = 0;
            $cadete = new cadetes;
        } else {
            $cadete = cadetes::where('num_cadete','=',$new_cadete['num_cadete'])->first();
        }
            
        while ($bande == 0 ) {
            $num_cadete= rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9);
            $new_cadete ['num_cadete']= $num_cadete;
            
            $num_logged = cadetes::where('num_cadete','=',$num_cadete)->first()->toArray();
            if ( !isset($num_logged['dni']) ) {
                $bande = 1;
            }
        }
        
        $cadete->nombre = $new_cadete['nombre'];
        $cadete->apellido = $new_cadete['apellido'];
        $cadete->dni = $new_cadete['dni'];
        $cadete->telefono = $new_cadete['telefono'];
        $cadete->direcc = $new_cadete['direcc'];
        $cadete->status = $new_cadete['status'];

        $cadete -> save();
    
        //////////////////////////////////////
        $title='Registro de Cadete';
        
        return view('included_cadete_success', compact('title','cadete'));
    }

    public function planilla_cadete() {
        
        $request = request()->input();

        if (isset($request['num_cadete'])) {
            $cadete = cadetes::where('num_cadete','=',$request['num_cadete'])->first()->toArray();
            $title='Actualizar Cadete';
        } else {
            $cadete = [];
            $title='Registrar Cadete';
        }

        return view('regis_cadete', compact('title','cadete'));
    }
}
