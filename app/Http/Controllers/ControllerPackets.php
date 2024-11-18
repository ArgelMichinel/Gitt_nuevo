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
        
        return view('query_packets',compact('title', 'packets', 'clients', 'cadetes', 'admin'));
    }

    public function crearlista () {

        $title='Lista creada';
        $data = request()->all();

        //$lista_form = json_decode($data);
        //dd($lista_form);
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
        
        $this->MELIService->insert_by_lots ('listasenvios', $parameters);

        
        return response()->json([
            'message' => 'Lista ' . $data['name'] . ' correctamente',
            'data' => $data,
        ]);
        
        //return view('Lista_creada',compact('title', 'lista_name')); // Esta vista no se usa. Es sólo un requisito porque los controladores siempre necesitan una vista
    }


}
