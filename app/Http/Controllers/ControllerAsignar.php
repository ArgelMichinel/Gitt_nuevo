<?php

namespace App\Http\Controllers;

use App\Models\cadetes;
use App\Models\listas;
use Illuminate\Http\Request;

class ControllerAsignar extends Controller
{
    public function ingresoGet() {
        $title='Asignar envíos a cadete';
        /* $packets = envios::orderBy('date_in', 'desc')->take(300)
            ->get()->toArray(); */
        $listas = listas::all()->toArray();
        $cadetes = cadetes::all()->toArray();
        
        return view('assign_packets',compact('title', 'listas', 'cadetes'));
    }

}
