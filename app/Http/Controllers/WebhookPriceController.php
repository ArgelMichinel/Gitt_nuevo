<?php

namespace App\Http\Controllers;

use App\Models\clientes;
use App\Models\table_price;
use Illuminate\Http\Request;

class WebhookPriceController extends Controller
{
    
    public function RetrivePrice()
    {
        if (request()->header('User-Agent') !== 'Tiendanube Webhooks' ||
            request()->query('token') !== env('TIENDANUBE_WEBHOOK_TOKEN')) {
            abort(403, 'Unauthorized');
        }

        $data = request()->input();
        //dd($data);
        // verificación de que la tienda que solicita está integrada
        //$data = json_decode($data,true);
        $cliente = clientes::where('id_TN','=',$data['store_id'])->first();

        if ( !$cliente ) {
            abort(403, 'Tienda no integrada');
        }

        $zona = table_price::where('codigos', 'like', '%' . $data['destination']['postal_code'] . '%')->first()->toArray();

        return var_dump($zona); //json_encode($cliente);
    }
}
