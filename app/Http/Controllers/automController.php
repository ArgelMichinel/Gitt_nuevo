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

        /* echo("Prueba" );
        return; */

        $envios = envios::where('date_in', '>=', $parameters['begin_date']) 
                    -> where('date_in', '<', $parameters['end_date'])
                    ->whereIn('status', ['shipped', 'ready_to_ship'])
                    ->get()->toArray();

        $numero = count($envios);
        //$numero = 1;

        $clientes = clientes::all();

        for ($j=0; $j < $numero; $j++) { 

            try {
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
                //echo("Actualizando el envio con número de identificación " . $envios[$j]['id_ship'] . "." );
                
                //return print_r($packets);

                if ($ship_mat[4][0]==NULL) {
                    $f_first_visita = NULL;
                } else {
                    try {
                        $f_first_visita = new \DateTime(substr($ship_mat[4][0], 0, 18));
                        $f_first_visita->modify('+1 hours');
                        $f_first_visita = $f_first_visita->format('Y-m-d H:i:s');
                    } catch (\Throwable $th) {
                        $f_first_visita = "Error";
                        echo("Hubo error al actualizar f_first_visita." );
                    }
                }
                //return var_dump($f_first_visita);
        
                if ($ship_mat[4][1]==NULL) {
                    $f_delivered = NULL;
                } else {
                    try {
                        $f_delivered = new \DateTime(substr($ship_mat[4][1], 0, 18));
                        $f_delivered->modify('+1 hours');
                        $f_delivered = $f_delivered->format('Y-m-d H:i:s');
                    } catch (\Throwable $th) {
                        $f_delivered = "Error";
                        echo("Hubo error al actualizar f_delivered." );
                    }
                }

                //return var_dump($f_delivered);
        
                if ($ship_mat[4][2]==NULL) {
                    $f_not_delivered = NULL;
                } else {
                    try {
                        $f_not_delivered = new \DateTime(substr($ship_mat[4][2], 0, 18));
                        $f_not_delivered = $f_not_delivered->format('Y-m-d H:i:s');
                    } catch (\Throwable $th) {
                        $f_not_delivered = "Error";
                        echo("Hubo error al actualizar f_not_delivered." );
                    }
                }

                //return var_dump($f_not_delivered);

                $packets->status = $ship_mat[0][2];
                $packets->street_name = $ship_mat[1][0];
                $packets->date_first_visit = $f_first_visita;
                $packets->date_delivered = $f_delivered;
                $packets->date_not_delivered = $f_not_delivered;

                //return var_dump($packets->status);
                //return var_dump($packets->date_not_delivered);

                $packets->save();

                //return var_dump($packets);
            } catch (\Throwable $th) {
                echo("Hubo error al actualizar el envio con número de identificación " . $envios[$j]['id_ship'] . ".\n" );
                echo("Fecha 1ra visita: " . $f_first_visita . "\n" );
                echo("Fecha entregado: " . $f_delivered . "\n" );
                echo("Fecha no entregado: " . $f_not_delivered . "\n" );
                echo("\n \n");
                continue;
            }

            

        }

        echo("Se actualizaron " . $numero . " envios con exito." );
        return;
    }
}
