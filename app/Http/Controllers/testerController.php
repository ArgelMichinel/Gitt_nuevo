<?php

namespace App\Http\Controllers;

use App\Models\access_meli;
use Illuminate\Http\Request;

class testerController extends Controller
{
    public function generar () {

        $cuenta = access_meli::where('id','=',143)->first()->toArray();
        $ACCESS_TOKEN = $cuenta['access_tok'];
        $APP_ID = env('APP_ID');


        $body = json_encode([
            'site_id' => 'MLA' // Define el sitio, por ejemplo, MLA (Argentina)
        ]);

        $headers_req =array(
            'Authorization' => 'Bearer ' . $ACCESS_TOKEN,
            'Content-Type' => 'application/json'
            );

        $cliente = curl_init();
        curl_setopt($cliente, CURLOPT_URL, "https://api.mercadolibre.com/users/test_user");
        curl_setopt($cliente, CURLOPT_POST, TRUE);
        curl_setopt($cliente, CURLOPT_HEADER, false);
        curl_setopt($cliente, CURLOPT_HTTPHEADER, array('Authorization: Bearer '. $ACCESS_TOKEN, "Content-type: application/json"));
        curl_setopt($cliente, CURLOPT_POSTFIELDS, $body);
        curl_setopt($cliente, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($cliente);
        curl_close($cliente);

        //print_r($result);

        $datos=json_decode($result,true);

        dd($datos);

    }
}
