<?php

namespace App\Http\Controllers;

use App\Models\envios;
use App\Models\clientes;
use Illuminate\Http\Request;
use App\Services\MELIService;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Auth;

class Controller_TN_sticker extends Controller
{
    protected $MELIService;

    public function __construct(MELIService $MELIService)
    {
        $this->MELIService = $MELIService;
    }
    
    public function generar_sticker_TN(Request $request, string $shipnum)
    {
        //Guarda en la variable perfil el tipo de usuario que debe tener para la ruta solicitada
        $perfil = $this->perfil($request);
        //dd($perfil);

        if ($perfil == 'administ') {
            $info_pack = envios::where('id_ship', $shipnum)->first();
        } elseif ($perfil == 'clientes') {
            $dat_user = Auth::user();
            $info_pack_TN = envios::where('id_ship', $shipnum)
                                ->where('sender_id', $dat_user['id_TN']);
            $info_pack_Gitt = envios::where('id_ship', $shipnum)
                                ->where('sender_id', $dat_user['id_Gitt']);
            $info_pack = $info_pack_TN->first() ?? $info_pack_Gitt->first();
        } else {
            return response("No tiene permisos para ver este sticker.", 403);
        }


        if (!$info_pack) {
            return response("No se encontró el envío con número de seguimiento: $shipnum", 404);
        }
        $title = "QR envío " . $info_pack->ship;
        //dd($tag);

        try {

            $codeText = $info_pack->sticker;
            $qrCode = QrCode::size(300)->generate($codeText);
            $qr = QrCode::format('png')
                ->size(200)
                ->generate($codeText);

            $qrBase64 = base64_encode($qr);

            $clients = clientes::all()->toArray();

            for ($i=0; $i < count($clients) ; $i++) { 
                if ($clients[$i]['id_TN'] == $info_pack->sender_id) {
                    $info_pack->client_name = $clients[$i]['name'];
                    break;
                }
            }

           //return view('sticker_Gitt',compact('title','qrCode', 'info_pack'));

            $mpdf = new \Mpdf\Mpdf();
            $html = view('sticker_Gitt', compact('title','qrBase64', 'info_pack'))->render();
            $mpdf->WriteHTML($html);
            return response($mpdf->Output('archivo.pdf', 'S'))
                    ->header('Content-Type', 'application/pdf');

        } catch (\Throwable $th) {
            echo ('Problema durante la consulta ' . $th);
        }

    }

    public function etiqueta_dia()
    {
        $dat_user = Auth::user();
        $cliente = clientes::where('id', $dat_user['id'])->first();
        $new_query = [];
        $new_query['incl_date'] = True;
        $fecha2 = new \DateTime();
        $fecha = new \DateTime();
        $fecha = $fecha->modify('-1 day');
        $fecha2 = $fecha2->modify('+1 day');
        $new_query['begin_date'] = $fecha->format('Y-m-d');
        $new_query['end_date'] = $fecha2->format('Y-m-d');
        $title='Etiquetas del día';
        $packets = envios::whereIn('sender_id', [$dat_user['id_Gitt'], $dat_user['id_TN']])
                        ->whereBetween('date_in', [$new_query['begin_date'], $new_query['end_date']])
                        ->where('TN','=', 1)
                        ->get()
                        ->toArray();
        
        //dd($packets);
        
        for ($i=0; $i < count($packets) ; $i++) { 
            $codeText = $packets[$i]['sticker'];
            $qr = QrCode::format('png')
                ->size(200)
                ->generate($codeText);
            
            $qrBase64 = base64_encode($qr);
            $packets[$i]['qrBase64'] = $qrBase64;
            $packets[$i]['client_name'] = $cliente->name;
        }

        $title = "Etiquetas del día";

        try {

            $mpdf = new \Mpdf\Mpdf();
            $html = view('sticker_Gitt_dia', compact('title','packets'))->render();
            $mpdf->WriteHTML($html);
            return response($mpdf->Output('archivo.pdf', 'S'))
                    ->header('Content-Type', 'application/pdf');

        } catch (\Throwable $th) {
            echo ('Problema durante la consulta ' . $th);
        }
    }

    protected function perfil(Request $request): string {
        //Guarda en la variable perfil el tipo de usuario que debe tener para la ruta solicitada
        if ($request->is('admin*')) {
            return 'administ';
        } elseif ($request->is('cadetes*')) {
            return 'cadetes';
        } else {
            return 'clientes';
        }
    }
}
