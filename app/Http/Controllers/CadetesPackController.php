<?php

namespace App\Http\Controllers;

use App\Services\MELIService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CadetesPackController extends Controller
{
    protected $MELIService;

    public function __construct(MELIService $MELIService)
    {
        $this->MELIService = $MELIService;
    }

    public function mostrarenvios () 
    {
        
        $dat_user = Auth::user();
        
        $var = request()->input('new_query');
        $new_query = $var;
        $new_query['incl_cadete'] = true;
        $new_query['cadete'] = $dat_user['id'];

        if (isset($var)) {
            $title='Consulta de envíos';
            $packets = $this->MELIService->query_customized($new_query);
        } else {
            $new_query['incl_date'] = True;
            $fecha2 = new \DateTime();
            $fecha = new \DateTime();
            $fecha = $fecha->modify('-1 day');
            $fecha2 = $fecha2->modify('+1 day');
            $new_query['begin_date'] = $fecha->format('Y-m-d');
            $new_query['end_date'] = $fecha2->format('Y-m-d');
            $title='Envíos en la jornada anterior';
            $packets = $this->MELIService->query_customized($new_query);
        }

        //dd($packets);
        
        return view('query_packets_client',compact('title', 'packets'));
        
    }
}
