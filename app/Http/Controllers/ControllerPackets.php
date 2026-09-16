<?php

namespace App\Http\Controllers;

use App\Models\administ;
use App\Models\cadetes;
use App\Models\clientes;
use App\Models\envios;
use App\Models\listas;
//use App\Models\access_meli;
use App\Services\MELIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;

class ControllerPackets extends Controller
{
    protected $MELIService;

    public function __construct(MELIService $MELIService)
    {
        $this->MELIService = $MELIService;
    }
    
    public function mostrarenvios() {
        
        $get_param = request()->input('new_query');
        $title='Consulta de paquetes';

        if (isset($get_param)) {
            $packets = $this->MELIService->query_customized($get_param);
        } else {
            $packets = $this->MELIService->getLatestPackets();
        }

        //dd($packets);
        
        $clients = clientes::all()->toArray();
        $cadetes = cadetes::all()->toArray();
        $admin = administ::all()->toArray();
        $credencial = Auth::user();
        $credencial = $credencial->master;
        
        return view('query_packets',compact('title', 'packets', 'clients', 'cadetes', 'admin', 'credencial'));
    }

    public function crearlista () {

        $title='Lista creada';
        $data = request()->all();

        //return $data;

        //$lista_form = json_decode($data);
        $lista_name = $data['name'];

        $lista = new listas;
        $lista->name = $lista_name;
        $lista->save();

        $list_values = json_decode($data['values'],true);
        
        $parameters = [];
        
        for ($i=0; $i < count($list_values); $i++) {
            $parameters[$i]['id_ship'] = $list_values[$i];
            $parameters[$i]['id_list'] = $lista['id_list'];
        }

        //return var_dump(count($parameters));
        
        $this->MELIService->insert_by_lots ('listasenvios', $parameters);

        
        return response()->json([
            'message' => 'Lista ' . $data['name'] . ' correctamente',
            'data' => $data,
        ]);
        
    }

    public function include_packets ()
    {
        $title='Ingresar Paquetes';

        return view('include_packets', compact('title'));
    }

    public function mostrarUpdate() {

        $title='Actualizar Paquetes';

        $shipnum = request()->input('shipnum');

        if ($shipnum) {

            $APP_ID = env('APP_ID');
            $SECRET_KEY = env('SECRET_KEY');

            $sender_id = (int) request()->input('sender_id');
            $client_info = $this->MELIService->checkValdTok($sender_id,$APP_ID, $SECRET_KEY);// checkValdTok($pdo, $sender_id);
            $ACCESS_TOK = $client_info['access_tok'];

            $shipnum = (int) request()->input('shipnum');;

            if (isset($_GET['sticker'])) {
                $sticker = $_GET['sticker'];
            } else {
                $sticker = '(vacio)';
            }

            $ship_mat = $this->MELIService->print_answer ($shipnum, $ACCESS_TOK, $sender_id,$sticker);

            $ship_mat = rawurlencode( json_encode($ship_mat));

            echo ($ship_mat);
            //print_r($ship_mat);

        } else {
            $get_param = request()->input('new_query');
            $title='Consulta de paquetes';

            if (isset($get_param)) {
                $packets = $this->MELIService->query_customized($get_param);
            } else {
                $packets = $this->MELIService->getLatestPackets();
            }

            //dd($packets);
            
            $clients = clientes::all()->toArray();
            $cadetes = cadetes::all()->toArray();
            $admin = administ::all()->toArray();
            $credencial = Auth::user();
            $credencial = $credencial->master;
            
            return view('update_packets',compact('title', 'packets', 'clients', 'cadetes', 'admin', 'credencial'));
        }
        
    }

    public function post_update() {
        $cambios = request()->input('new_list');
        $valores = request()->input('values');

        $list_values = json_decode($valores,true);
        
        $parameters = [];
        
        for ($i=0; $i < count($list_values); $i++) {
            $parameters[$i] = $cambios;
            $parameters[$i]['id_ship'] = $list_values[$i];
        }
        
        //dd($parameters);
        
        $this->MELIService->update_by_lots2('id_ship', $parameters);
        
        $title='Envíos Actualizados';

        return view('update_success',compact('title'));
    }

    public function update_TN()
    {
        $data1 = request()->input('envio');
        $status = request()->input('status');
        $idship = json_decode($data1,true);

        //return $idship;
        $envio = envios::where('id_ship','=',rtrim($idship))->first();

        if ($status == 'first_visit') {
            
            $fecha_ahora = new \DateTime();
            $fecha_ahora->modify('-3 hours');
            $fecha_ahora= $fecha_ahora->format('Y-m-d H:i:s'); 
            $envio->date_first_visit = $fecha_ahora;
            $envio->admin_status = (int)Auth::id();
            $envio->save();

        } else if ($status == 'delivered') {

            $fecha_ahora = new \DateTime();
            $fecha_ahora->modify('-3 hours');
            $fecha_ahora= $fecha_ahora->format('Y-m-d H:i:s'); 
            $envio->date_delivered = $fecha_ahora;
            $envio->status = $status;
            $envio->admin_status = (int)Auth::id();
            $envio->save();
        } else if ($status == 'cancelled') {

            $envio->status = $status;
            $envio->admin_status = (int)Auth::id();
            $envio->save();
        }


        return 'Envio actualizado';

    }

        public function include_packs_gitt_adm ()
    {
        $request = request()->input('id');

        if (isset($request)) {
            $packet = envios::where('id_ship', $request)->first();
            $title='Editar Paquete Gitt';

        } else {
            $packet = null;
            $title='Ingresar Paquete Gitt';
        }

        //dd($request);

        $clients = clientes::all();

        return view('include_packs_gitt_adm', compact('title', 'clients', 'packet'));
    }

        public function include_packs_gitt_cli ()
    {
        $request = request()->input('id');

        $dat_user = Auth::user();

        if (isset($request)) {
            $packet = envios::where('id_ship', $request)->first();
            $cliente = clientes::where('id', $dat_user->id)->first();

            if ($packet['sender_id'] != $cliente['id_Gitt']) {
                dd('No tenés permiso para editar este paquete');
            }
            $title='Editar Paquete Gitt';

        } else {
            $packet = null;
            $title='Ingresar Paquete Gitt';
        }


        return view('include_packs_gitt_cli', compact('title', 'dat_user', 'packet'));
    }

}
