<?php

namespace App\Http\Controllers;

use App\Models\clientes;
use App\Models\table_price;
use DateTime;
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

        //return var_dump($zona); //json_encode($cliente);

        $fec_min_entrega = new \DateTime('now', new \DateTimeZone('-03:00')); //date("Y-m-d");
        $fec_min_entrega -> modify('+1 day');
        $fec_max_entrega = new \DateTime('now', new \DateTimeZone('-03:00'));
        $fec_max_entrega -> modify('+2 day');

        $respu = [
            "rates" => [
                [
                    "name" => "Servicio de envío Estándar",
                    "code" => "standard",
                    "price" => $zona['precio'],
                    "price_merchant" => $zona['precio'],
                    "currency" => "ARS",
                    "type" => "ship",
                    "min_delivery_date" => $fec_min_entrega->format('Y-m-d\TH:i:sO'),  //"min_delivery_date": "2016-07-14T14:48:45-0300","2025-05-03T03:55:20+0000"
                    "max_delivery_date" => $fec_max_entrega->format('Y-m-d\TH:i:sO'),  //"max_delivery_date": "2016-07-17T14:48:45-0300",
                    "phone_required" => true,
                    "reference" => $data['carrier']['id']
                ]
            ]
        ];

        return response()->json($respu);
    }
}
