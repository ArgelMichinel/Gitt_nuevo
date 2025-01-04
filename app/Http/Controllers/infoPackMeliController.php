<?php

namespace App\Http\Controllers;

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
            dd('Está entrando');
            $sender_id = (int) $dat_query['sender_id'];
            $clientes = clientes::all();
            $shipnum = (int)$dat_query['shipnum'];

            if (isset($dat_query['sticker'])) {
                $sticker = $dat_query['sticker'];
            } else {
                $sticker = '(vacio)';
            }

            ///////Acá se evalúa si el envío es de MELI o TN
            for ($i=0; $i < count($clientes); $i++) { 
                
                if ($sender_id == $clientes[$i]->id_MELI) {  //Metodos que se aplican si el envío es MELI
                    $sender_id = (int) $sender_id;
                    $client_info = $this -> MELIService -> checkValdTok($sender_id);
                    $ACCESS_TOK = $client_info['access_tok'];
                    $ship_mat = $this -> MELIService -> print_answer ($shipnum, $ACCESS_TOK, $sender_id ,$sticker);
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

            $ship_mat = rawurlencode( json_encode($ship_mat));

            return print ($ship_mat);
        } else {
            $title='Consultar paquetes sin registrar';
            $clientes = clientes::all();

            return view('info_packets',compact('title','clientes'));
        }
    }

    public function info_Meli_post() 
    {
        $sender_id = request()->input('sender_id');
        $clientes = clientes::all();
        $shipnum = (int) request()->input('shipnum');
        $sticker = 'Vacio';
        ///////Acá se evalúa si el envío es de MELI o TN
        for ($i=0; $i < count($clientes); $i++) { 
            
            if ($sender_id == $clientes[$i]->id_MELI) {  //Metodos que se aplican si el envío es MELI
                $sender_id = (int) $sender_id;
                $client_info = $this -> MELIService -> checkValdTok($sender_id);
                $ACCESS_TOK = $client_info['access_tok'];
                $ship_mat = $this -> MELIService -> print_answer ($shipnum, $ACCESS_TOK, $sender_id ,$sticker);
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
        $packets['sticker'] = $sticker;

        $title='Consultar paquetes sin registrar';

        return view('info_packets_respon',compact('title','packets'));
    }
}
