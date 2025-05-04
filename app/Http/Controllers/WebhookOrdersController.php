<?php

namespace App\Http\Controllers;

use App\Models\clientes;
use Illuminate\Http\Request;

class WebhookOrdersController extends Controller
{
    public function recibirOrden(Request $request) {
        
        if (request()->header('User-Agent') !== 'Tiendanube Webhooks' ) {
            abort(403, 'Unauthorized');
        }

        $data = request()->input();
        //dd($data);
        // verificación de que la tienda que solicita está integrada
        //$data = json_decode($data,true);
        $cliente = clientes::where('id_TN','=',$data['store'])->first();

        if ( !$cliente ) {
            abort(403, 'Tienda no integrada');
        }

        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //////////////////////// Instrucciones para poder sacar el valor de los ids por tener el mismo identificador en la query                // 
        $rawQuery = $_SERVER['REQUEST_URI']; // Ej: /webhook/notify_orders?locale=es&store=5755375&id=128158567&id=127034942                    //
        $parsedUrl = parse_url($rawQuery);                                                                                                      //
        $queryString = $parsedUrl['query'] ?? '';                                                                                               //
                                                                                                                                                //
        parse_str($queryString, $queryParams);                                                                                                  //
                                                                                                                                                //
        $ids = [];                                                                                                                              //
                                                                                                                                                //
        // Extraer todos los pares clave=valor                                                                                                  //
        parse_str($queryString, $allParams); // Esto aún es útil para los parámetros que no están repetidos                                     //
        preg_match_all('/(?:^|&)([^=&]+)=([^&]*)/', $queryString, $matches, PREG_SET_ORDER);                                                    //
                                                                                                                                                //
        foreach ($matches as $match) {                                                                                                          //
            $key = urldecode($match[1]);                                                                                                        //
            $value = urldecode($match[2]);                                                                                                      //
                                                                                                                                                //
            if ($key === 'id') {                                                                                                                //
                $ids[] = $value;                                                                                                                //
            }                                                                                                                                   //
        }                                                                                                                                       //
                                                                                                                                                //
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        return var_dump($ids);
    }
}
