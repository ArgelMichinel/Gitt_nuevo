<?php

namespace App\Http\Controllers;

use App\Models\listas;
use App\Models\listasenvios;
use Illuminate\Http\Request;

class editListController extends Controller
{
    public function mostrar() {
        $title='Eliminar lista';

        $listas = listas::all();

        return view('edit_lista', compact('title', 'listas'));
    }

    public function delet_lista() {
        $id_lista = request()->input('list');

        $lista_eliminada =listasenvios::where('id_list','=',$id_lista);
        $lista_eliminada->delete();
        $lista_eliminada = listas::where('id_list','=',$id_lista);
        $lista_eliminada->delete();
    
        //////////////////////////////////////
        $title='Eliminar lista';
        return view('deleted_lista', compact('title'));
    }
}
