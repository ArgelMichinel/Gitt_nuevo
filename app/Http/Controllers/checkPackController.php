<?php

namespace App\Http\Controllers;

use App\Models\envios;
use Illuminate\Http\Request;

class checkPackController extends Controller
{
    public function check() {
        $shipnum = request()->input('id_ship');
        $pack = envios::where('id_ship','=',$shipnum)->get()->toArray();
        //dd($pack[0]);

        return response()->json($pack[0]);
    }
}
