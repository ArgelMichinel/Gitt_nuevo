<?php

namespace App\Http\Controllers;

use App\Models\cadetes;
use App\Models\listas;
use App\Models\listasenvios;
use App\Services\MELIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ControllerAsignar extends Controller
{
    protected $MELIService;

    public function __construct(MELIService $MELIService)
    {
        $this->MELIService = $MELIService;
    }

    public function ingresoGet() {
        $title='Asignar envíos a cadete';
        /* $packets = envios::orderBy('date_in', 'desc')->take(300)
            ->get()->toArray(); */
        $listas = listas::all()->toArray();
        $cadetes = cadetes::all()->toArray();
        
        return view('assign_packets',compact('title', 'listas', 'cadetes'));
    }

    public function AsignarPost() {

        $parametros = request()->input();

        if (isset($parametros['select_list'])) {
            
            $list = $parametros['select_list']['list'];
            $packets_list = listasenvios::where('id_list','=',$list)->get(); //findSeveral('listasenvios','id_list',$list);
            $cadete = $parametros['select_list']['cadete'];
            $id_adm = Auth::id();
            
            $array_assign = [];
            for ($i = 0; $i < count($packets_list) ; $i++ ) {
                $array_assign[$i]['id_ship'] = $packets_list[$i]['id_ship'];
                $array_assign[$i]['cadete1'] = $cadete;
                $array_assign[$i]['admin_cad1'] = $id_adm;
            }
            
            $this->MELIService->assign_packets ($array_assign);

        } 
        elseif (isset($parametros['select_scanner'])) {
            
            $packets_list = $parametros['select_scanner']['values'];
            $packets_list = json_decode($packets_list,true);
            $cadete = $parametros['select_scanner']['cadete'];
            $id_adm = Auth::id();
            
            $array_assign = [];
            for ($i = 0; $i < count($packets_list) ; $i++ ) {
                $array_assign[$i]['id_ship'] = $packets_list[$i];
                $array_assign[$i]['cadete1'] = $cadete;
                $array_assign[$i]['admin_cad1'] = $id_adm;
            }
            
            $this->MELIService->assign_packets ($array_assign);

        } else {
            return view('fail_assign',compact('title'));
        }

        $title='Asignación de paquetes';
        return view('success_assign',compact('title'));
    }


}
