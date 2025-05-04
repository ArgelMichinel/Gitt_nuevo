<?php

namespace App\Http\Controllers;

use App\Models\clientes;
use Illuminate\Http\Request;
use App\Services\MELIService;
use Illuminate\Support\Facades\Auth;

class WebhookOrdersController extends Controller
{
    protected $MELIService;

    public function __construct(MELIService $MELIService)
    {
        $this->MELIService = $MELIService;
    }

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

        $NOMBRE_CARRIER_TN = env('$NOMBRE_CARRIER_TN');
        $CONTACT_APP_TN = env('CONTACT_APP_TN');

        $num = count($ids);

        for ($i=0; $i < $num; $i++) { 

            $data = $this -> MELIService -> print_answer_TN ($cliente['user_id'],$cliente['access_tok'],$NOMBRE_CARRIER_TN,$CONTACT_APP_TN,$ids[$i]);
            usleep(1000000);

            # codigo de savePackController
            switch ($data[1][6]) {
                case "Argentina":
                    $pais = 1;
                    break;
                case "Brasil":
                    $pais = 2;
                    break;
                case "Chile":
                    $pais = 3;
                    break;
                case "Perú":
                    $pais = 4;
                    break;
                case "Venezuela":
                    $pais = 5;
                    break;
                default:
                    $pais = 1;
            }
            
            if ($data[4][0]==NULL) {
                $f_first_visita = NULL;
            } else {
                $f_first_visita = new \DateTime(substr($data[4][0], 0, 19));
                $f_first_visita->modify('+1 hours');
            }
    
            if ($data[4][1]==NULL) {
                $f_delivered = NULL;
            } else {
                $f_delivered = new \DateTime(substr($data[4][1], 0, 19));
                $f_delivered->modify('+1 hours');
            }
    
            if ($data[4][2]==NULL) {
                $f_not_delivered = NULL;
            } else {
                $f_not_delivered = new \DateTime(substr($data[4][2], 0, 19));
            }
            
            $colum = [];
            $colum['id_ship'] = $data[0][0];
            $colum['date_in'] = new \DateTime();
            //$colum['date_in'] = $data[0][1]['date'];
            $colum['status'] = $data[0][2];
            $colum['sender_id'] = $data[0][3];
            $colum['order_id'] = $data[0][4];
            $colum['street_name'] = $data[1][0];
            $colum['street_number'] = intval ($data[1][1]);
            $colum['comment'] = $data[1][2];
            $colum['zip_code'] = intval ($data[1][3]);
            $colum['city'] = $data[1][4];
            $colum['state'] = $data[1][5];
            $colum['Latit'] = $data[1][7];
            $colum['Longi'] = $data[1][8];
            //$colum['last_geo'] = $data[1][9];
            $colum['country'] = $pais;
            
            if ($data[1][10]=='business') {
                $colum['delivery_preference'] = 1;
            } else {
                $colum['delivery_preference'] = 0;
            }
            $colum['receiver_name'] = $data[2][0];
            $colum['receiver_phone'] = $data[2][1];
            $colum['description'] = $data[3][0];
            $colum['date_first_visit'] = $f_first_visita;
            $colum['date_delivered'] = $f_delivered;
            $colum['date_not_delivered'] = $f_not_delivered;
            $colum['admin_ingre'] = (int)Auth::id();
            $colum['sticker'] = $data[0][5];
            
            $this->MELIService->insert_pack($colum);

        }
        return var_dump($ids);
    }
}
