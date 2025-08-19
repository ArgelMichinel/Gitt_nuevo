<?php

namespace App\Http\Controllers;

use App\Models\table_price;
use Illuminate\Http\Request;

class ControllerPrice extends Controller
{

    public function mostrarPrecios() {

        $title='Asignar precio de envíos';
        
        $precios = table_price::all()->toArray();
        //dd($precios);

        return view('price_screen',compact('precios','title'));
    }

    public function actualizarPrecios() {
        
        $title='Asignar precio de envíos';

        $num = table_price::all()->count();
        $datos = request()->input('new_list');
        //dd($datos);
        
        for ($i=0; $i < $num; $i++) { 
            $precio = table_price::where('id','=',($i+1))->first();
            //dd($precio);
            $precio ->precio = $datos['precio'][$i+1];
            $precio->save();
        }

        return view('success_prices',compact('title'));
    }
}
