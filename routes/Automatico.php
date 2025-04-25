<?php

use App\Http\Controllers\ControllerPackets;
use App\Models\clientes;
use App\Models\envios;
use App\Services\MELIService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

$MELIServices = new MELIService;

$var = new ControllerPackets($MELIServices);


// Obtener la fecha y hora actual
date_default_timezone_set('America/Argentina/Buenos_Aires');
$parameters['begin_date'] = date("Y-m-d");
// Establecer el día de mañana
$dia_manana = strtotime('+1 day', strtotime($parameters['begin_date']));
$dia_manana = date('Y-m-d', $dia_manana);
$parameters['end_date'] = $dia_manana;

$envios_query = DB::table('envios');

$envios = $envios_query -> where('date_in', '>=', $parameters['begin_date']) -> where('date_in', '<', $parameters['end_date'])->get()->toArray();

$clientes = clientes::all();

for ($j=0; $j < count($envios); $j++) { 

    $sticker = $envios[$j]['sticker'];

    ///////Acá se evalúa si el envío es de MELI o TN
    for ($i=0; $i < count($clientes); $i++) { 
                
        if ($envios[$j]['sender_id'] == $clientes[$i]->id_MELI) {  //Metodos que se aplican si el envío es MELI
            $sender_id = (int) $envios[$j]['sender_id'];
            $APP_ID = env('APP_ID');
            $SECRET_KEY = env('SECRET_KEY');
            $client_info = $MELIServices -> checkValdTok($sender_id,$APP_ID,$SECRET_KEY);
            $ACCESS_TOK = $client_info['access_tok'];
            usleep(200);
            $ship_mat = $MELIServices -> print_answer ($envios[$j]['id_ship'], $ACCESS_TOK, $sender_id ,$sticker);
            break;
        }
        if ($sender_id == $clientes[$i]->id_TN) {  //Metodos que se aplican si el envío es TN
            /*
            *
            *
            */
            break;
        }

    }

    $packets = envios::find($envios[$j]['id_num']);

    $packets->status = $ship_mat[0][2];
    $packets->street_name = $ship_mat[1][0];
    $packets->date_first_visit = $ship_mat[4][0];
    $packets->date_delivered = $ship_mat[4][1];
    $packets->date_not_delivered = $ship_mat[4][2];

    $packets->save();
}


