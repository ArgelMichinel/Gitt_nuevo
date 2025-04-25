<?php

namespace App\Console\Commands;

use App\Services\MELIService;
use Illuminate\Console\Command;
use App\Models\clientes;
use App\Models\envios;
use Illuminate\Support\Facades\DB;

class UpdateShipmentStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:shipment-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para actualizar envios de paquetes de MELI y TN';

    protected $MELIService;

    public function __construct(MELIService $MELIService)
    {
        parent::__construct();
        $this->MELIService = $MELIService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $parameters['begin_date'] = date("Y-m-d");
        $dia_manana = strtotime('+1 day', strtotime($parameters['begin_date']));
        $dia_manana = date('Y-m-d', $dia_manana);
        $parameters['end_date'] = $dia_manana;

        $envios = DB::table('envios')
            ->where('date_in', '>=', $parameters['begin_date'])
            ->where('date_in', '<', $parameters['end_date'])
            ->get(); 

        $clientes = clientes::all();

        foreach ($envios as $envio) { // Use foreach for cleaner iteration

            $sticker = $envios->sticker;

            foreach ($clientes as $cliente) {
                if ($envio->sender_id == $cliente->id_MELI) {
                    $sender_id = (int) $envio->sender_id;
                    $APP_ID = env('APP_ID');
                    $SECRET_KEY = env('SECRET_KEY');
                    $client_info = $this->MELIService->checkValdTok($sender_id, $APP_ID, $SECRET_KEY);
                    $ACCESS_TOK = $client_info['access_tok'];
                    usleep(200);
                    $ship_mat = $this->MELIService->print_answer($envio->id_ship, $ACCESS_TOK, $sender_id, $sticker); 
                    break;
                }
                if ($envio->sender_id == $cliente->id_TN) { //Metodos que se aplican si el envío es TN
                    // ... TN logic ...
                    break;
                }
            }

            $packet = envios::find($envio->id_num);
            if ($packet) { // Verifica si el paquete existe
                $packet->status = $ship_mat[0][2] ?? null;
                $packet->street_name = $ship_mat[1][0] ?? null;
                $packet->date_first_visit = $ship_mat[4][0] ?? null;
                $packet->date_delivered = $ship_mat[4][1] ?? null;
                $packet->date_not_delivered = $ship_mat[4][2] ?? null;
                $packet->save();
            }
        }

        $this->info('Shipment status updated successfully.');
    }
}
