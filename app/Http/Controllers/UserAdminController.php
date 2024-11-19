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
}
