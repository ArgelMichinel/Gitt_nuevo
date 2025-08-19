<?php

namespace App\Http\Controllers;

use App\Models\access_nube;
use App\Models\clientes;
use App\Services\MELIService;
use Illuminate\Http\Request;

class infoPackMeliController extends Controller
{
    protected $MELIService;

    public function __construct(MELIService $MELIService)
    {
        $this->MELIService = $MELIService;
    }

    public function info_packets_get () {

        $dat_query = request() -> input();

        if (isset($dat_query['shipnum'])) {
            //dd('Está entrando');
            $sender_id = (int) $dat_query['sender_id'];
            $clientes = clientes::all();
            $shipnum = (int)$dat_query['shipnum'];

            if (isset($dat_query['sticker'])) {
                $sticker = base64_decode($dat_query['sticker']);  // Para evitar el problema que se genera al enviar "/" en el sticker
                $sticker = rawurldecode($sticker);
            } else {
                $sticker = '(vacio)';
            }

            //dd($sticker);

            ///////Acá se evalúa si el envío es de MELI o TN
            for ($i=0; $i < count($clientes); $i++) { 

                //if ($i == 2) dd('Tabla cliente ' . $clientes[$i]->id_MELI . ' SenderID ' . $sender_id );
                
                if ($sender_id == $clientes[$i]->id_MELI) {  //Metodos que se aplican si el envío es MELI
                    
                    $APP_ID = env('APP_ID');
                    $SECRET_KEY = env('SECRET_KEY');
                    $client_info = $this -> MELIService -> checkValdTok($sender_id,$APP_ID,$SECRET_KEY);
                    //dd($client_info);
                    $ACCESS_TOK = $client_info['access_tok'];
                    try {
                        $ship_mat = $this -> MELIService -> print_answer ($shipnum, $ACCESS_TOK, $sender_id ,$sticker);
                    } catch (\Throwable $th) {
                        $ship_mat = 'Error';
                    }
                    //$ship_mat = $this -> MELIService -> print_answer ($shipnum, $ACCESS_TOK, $sender_id ,$sticker);
                    break;
                }
                if ($sender_id == $clientes[$i]->id_TN) {  //Metodos que se aplican si el envío es TN
                    
                    $user_id = $sender_id;
                    $cliente = access_nube::where('user_id','=',$user_id)->first();
                    $id_order = $shipnum;

                    $NOMBRE_CARRIER_TN = env('NOMBRE_CARRIER_TN');
                    $CONTACT_APP_TN = env('CONTACT_APP_TN');
                    try {
                        $ship_mat = $this -> MELIService -> print_answer_TN ($user_id,$cliente['access_tok'],$NOMBRE_CARRIER_TN,$CONTACT_APP_TN,$id_order);
                    } catch (\Throwable $th) {
                        $ship_mat = 'Error';
                    }
                    //$ship_mat = $this -> MELIService -> print_answer_TN ($user_id,$cliente['access_tok'],$NOMBRE_CARRIER_TN,$CONTACT_APP_TN,$id_order);
                    
                    break;
                }

            }

            $ship_mat = rawurlencode( json_encode($ship_mat));
            //dd($ship_mat);

            print_r($ship_mat);
            return;
            
        } else {
            $title='Consultar paquetes sin registrar';
            $clientes = clientes::all();

            return view('info_packets',compact('title','clientes'));
        }
    }

    public function info_packets_post() 
    {
        $sender_id = request()->input('sender_id');
        $clientes = clientes::all();
        $shipnum = (int) request()->input('shipnum');
        $sticker = 'Vacio';
        ///////Acá se evalúa si el envío es de MELI o TN
        for ($i=0; $i < count($clientes); $i++) { 
            
            if ($sender_id == $clientes[$i]->id_MELI) {  //Metodos que se aplican si el envío es MELI
                $sender_id = (int) $sender_id;
                $APP_ID = env('APP_ID');
                $SECRET_KEY = env('SECRET_KEY');
                $client_info = $this -> MELIService -> checkValdTok($sender_id,$APP_ID,$SECRET_KEY);
                $ACCESS_TOK = $client_info['access_tok'];
                $ship_mat = $this -> MELIService -> print_answer ($shipnum, $ACCESS_TOK, $sender_id ,$sticker);
                break;
            }
            if ($sender_id == $clientes[$i]->id_TN) {  //Metodos que se aplican si el envío es TN
                
                $user_id = $sender_id;
                $cliente = access_nube::where('user_id','=',$user_id)->first();
                $id_order = $shipnum;
                
                $$NOMBRE_CARRIER_TN = env('$NOMBRE_CARRIER_TN');
                $CONTACT_APP_TN = env('CONTACT_APP_TN');
                $ship_mat = $this -> MELIService -> print_answer_TN ($user_id,$cliente['access_tok'],$NOMBRE_CARRIER_TN,$CONTACT_APP_TN,$id_order);
            
                break;
            }

        }

        $packets = [];
        $packets['id_ship'] = $ship_mat[0][0];
        $packets['date_in'] = $ship_mat[0][1];
        $packets['status'] = $ship_mat[0][2];
        $packets['sender_id'] = $ship_mat[0][3];
        $packets['order_id'] = $ship_mat[0][4];
        $packets['street_name'] = $ship_mat[1][0];
        $packets['street_number'] = $ship_mat[1][1];
        $packets['comment'] = $ship_mat[1][2];
        $packets['zip_code'] = $ship_mat[1][3];
        $packets['city'] = $ship_mat[1][4];
        $packets['state'] = $ship_mat[1][5];
        $packets['country'] = $ship_mat[1][6];
        $packets['delivery_preference'] = $ship_mat[1][10];
        $packets['receiver_name'] = $ship_mat[2][0];
        $packets['receiver_phone'] = $ship_mat[2][1];
        $packets['description'] = $ship_mat[3][0];
        $packets['dimensions'] = $ship_mat[3][1];
        $packets['date_first_visit'] = $ship_mat[4][0];
        $packets['date_delivered'] = $ship_mat[4][1];
        $packets['date_not_delivered'] = $ship_mat[4][2];

        $title='Consultar paquetes sin registrar';

        return view('info_packets_respon',compact('title','packets'));
    }

    public function ruta_prueba () {

        $dat_query = request() -> input();

        if (isset($dat_query['shipnum'])) {
            //dd('Está entrando');
            $sender_id = (int) $dat_query['sender_id'];
            $clientes = clientes::all();
            $shipnum = (int)$dat_query['shipnum'];

            if (isset($dat_query['sticker'])) {
                $sticker = $dat_query['sticker'];
            } else {
                $sticker = '(vacio)';
            }

            //dd($sticker);

            ///////Acá se evalúa si el envío es de MELI o TN
            for ($i=0; $i < count($clientes); $i++) { 

                //if ($i == 2) dd('Tabla cliente ' . $clientes[$i]->id_MELI . ' SenderID ' . $sender_id );
                
                if ($sender_id == $clientes[$i]->id_MELI) {  //Metodos que se aplican si el envío es MELI
                    
                    $APP_ID = env('APP_ID');
                    $SECRET_KEY = env('SECRET_KEY');
                    $client_info = $this -> MELIService -> checkValdTok($sender_id,$APP_ID,$SECRET_KEY);
                    //dd($client_info);
                    $ACCESS_TOK = $client_info['access_tok'];
                    $ship_mat = $this -> MELIService -> info_shipping($shipnum,$ACCESS_TOK);// ($shipnum, $ACCESS_TOK, $sender_id ,$sticker);
                    break;
                }
                if ($sender_id == $clientes[$i]->id_TN) {  //Metodos que se aplican si el envío es TN
                    
                    $user_id = $sender_id;
                    $cliente = access_nube::where('user_id','=',$user_id)->first();
                    $id_order = $shipnum;

                    $NOMBRE_CARRIER_TN = env('NOMBRE_CARRIER_TN');
                    $CONTACT_APP_TN = env('CONTACT_APP_TN');
                    $ship_mat = $this -> MELIService -> print_answer_TN ($user_id,$cliente['access_tok'],$NOMBRE_CARRIER_TN,$CONTACT_APP_TN,$id_order);
                    
                    break;
                }

            }

            //$ship_mat = rawurlencode( json_encode($ship_mat));
            //dd($ship_mat);

            print_r($ship_mat);
            return;
            
        }
    }
}
