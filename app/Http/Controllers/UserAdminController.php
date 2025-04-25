<?php

namespace App\Http\Controllers;

use App\Models\administ;
use Illuminate\Http\Request;

class UserAdminController extends Controller
{
    public function mostrarAdmin() {
        $administra = administ::all('name', 'email')->toArray();

        $title='Usuarios Administradores';

        return view('userAdmin',compact('title', 'administra'));
    }

    public function borrarAdmin() {
        $id_admin = request()->input('del_admin');

        $cadete_eliminado = administ::where('email','=',$id_admin);
        $cadete_eliminado->delete();
    
        //////////////////////////////////////
        $title='Administrador Eliminado';
        return view('deleted_admin', compact('title'));
    }
}
