<?php

namespace App\Http\Controllers;

use App\Services\MELIService;
use Illuminate\Http\Request;
use App\Models\clientes;
use App\Models\envios;
use Illuminate\Support\Facades\DB;

class automController extends Controller
{
    protected $MELIService;

    public function __construct(MELIService $MELIService)
    {
        $this->MELIService = $MELIService;
    }

    public function automatico ()
    {
        // Obtener la fecha y hora actual
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $fecha_inicio = new \DateTime();
        $parameters['begin_date'] =  $fecha_inicio->modify('-10 day'); //date("Y-m-d");
        // Establecer el día de mañana
        $dia_manana = new \DateTime();
        $dia_manana->modify('+1 day');
        $parameters['end_date'] = $dia_manana;

        //$envios_query = DB::table('envios');

        $envios = envios::where('date_in', '>=', $parameters['begin_date']) 
                    -> where('date_in', '<', $parameters['end_date'])
                    ->whereIn('status', ['shipped', 'ready_to_ship'])
                    ->get()->toArray();

        $numero = count($envios);

        $clientes = clientes::all();

        for ($j=0; $j < count($envios); $j++) { 

            $sticker = $envios[$j]['sticker'];

            ///////Acá se evalúa si el envío es de MELI o TN
            for ($i=0; $i < count($clientes); $i++) { 
                        
                if ((int) $envios[$j]['sender_id'] == (int) $clientes[$i]->id_MELI) {  //Metodos que se aplican si el envío es MELI

                    $sender_id = (int) $envios[$j]['sender_id'];
                    $APP_ID = env('APP_ID');
                    $SECRET_KEY = env('SECRET_KEY');
                    $client_info = $this->MELIService -> checkValdTok($sender_id,$APP_ID,$SECRET_KEY);
                    $ACCESS_TOK = $client_info['access_tok'];
                    usleep(200);
                    $ship_mat = $this->MELIService -> print_answer ($envios[$j]['id_ship'], $ACCESS_TOK, $sender_id ,$sticker);
                    break;
                }
                if ($envios[$j]['sender_id'] == $clientes[$i]->id_TN) {  //Metodos que se aplican si el envío es TN
                    /*
                    *
                    *
                    */
                    break;
                }

            }

            $packets = envios::find($envios[$j]['id_num']);

            if ($ship_mat[4][0]==NULL) {
                $f_first_visita = NULL;
            } else {
                $f_first_visita = new \DateTime(substr($ship_mat[4][0], 0, 19));
                $f_first_visita->modify('+1 hours');
            }
    
            if ($ship_mat[4][1]==NULL) {
                $f_delivered = NULL;
            } else {
                $f_delivered = new \DateTime(substr($ship_mat[4][1], 0, 19));
                $f_delivered->modify('+1 hours');
            }
    
            if ($ship_mat[4][2]==NULL) {
                $f_not_delivered = NULL;
            } else {
                $f_not_delivered = new \DateTime(substr($ship_mat[4][2], 0, 19));
            }

            $packets->status = $ship_mat[0][2];
            $packets->street_name = $ship_mat[1][0];
            $packets->date_first_visit = $f_first_visita;
            $packets->date_delivered = $f_delivered;
            $packets->date_not_delivered = $f_not_delivered;

            $packets->save();


        }

        echo("Se actualizaron " . $numero . " envios con exito." );
        return;
    }
}
