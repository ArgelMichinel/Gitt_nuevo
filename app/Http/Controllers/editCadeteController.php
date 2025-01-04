<?php

namespace App\Http\Controllers;

use App\Models\cadetes;
use Illuminate\Http\Request;

class editCadeteController extends Controller
{
    public function mostrar() {
        $title='Eliminar Cadete';

        $cadetes = cadetes::all();

        return view('edit_cadete', compact('title', 'cadetes'));
    }

    public function delet_cadete() {
        $id_cade = request()->input('cadete');

        $cadete_eliminado = cadetes::where('num_cadete','=',$id_cade);
        $cadete_eliminado->delete();
    
        //////////////////////////////////////
        $title='Cadete Eliminado';
        return view('deleted_Cadete', compact('title'));
    }
}
