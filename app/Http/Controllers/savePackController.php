<?php

namespace App\Http\Controllers;

use App\Models\envios;
use App\Services\MELIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Models\clientes;

class savePackController extends Controller
{
    protected $MELIService;
    protected $LoginController;

    public function __construct(MELIService $MELIService, LoginController $LoginController)
    {
        $this->MELIService = $MELIService;
        $this->LoginController = $LoginController;
    }

    public function save_pack ()
    {
        $data = request()->input();
    
        $fields=[];
            
        switch ($data[1][6]) {
        case "Argentina":
            $pais = 1;
            break;
        case "Brasil":
            $pais = 2;
            break;
        case "Chile":
            $pais = 3;
            break;
        case "Perú":
            $pais = 4;
            break;
        case "Venezuela":
            $pais = 5;
            break;
        default:
            $pais = 1;
        }
        
        if ($data[4][0]==NULL) {
            $f_first_visita = NULL;
        } else {
            $f_first_visita = new \DateTime(substr($data[4][0], 0, 19));
            $f_first_visita->modify('+1 hours');
        }

        if ($data[4][1]==NULL) {
            $f_delivered = NULL;
        } else {
            $f_delivered = new \DateTime(substr($data[4][1], 0, 19));
            $f_delivered->modify('+1 hours');
        }

        if ($data[4][2]==NULL) {
            $f_not_delivered = NULL;
        } else {
            $f_not_delivered = new \DateTime(substr($data[4][2], 0, 19));
        }

        $fecha_ahora = new \DateTime();
        $fecha_ahora->modify('-3 hours');
        
        $colum = [];
        $colum['id_ship'] = $data[0][0];
        $colum['date_in'] = $fecha_ahora;
        //$colum['date_in'] = $data[0][1]['date'];
        $colum['status'] = $data[0][2];
        $colum['sender_id'] = $data[0][3];
        $colum['order_id'] = $data[0][4];
        $colum['street_name'] = $data[1][0];
        $colum['street_number'] = intval ($data[1][1]);
        $colum['comment'] = $data[1][2];
        $colum['zip_code'] = intval ($data[1][3]);
        $colum['city'] = $data[1][4];
        $colum['state'] = $data[1][5];
        $colum['Latit'] = $data[1][7];
        $colum['Longi'] = $data[1][8];
        //$colum['last_geo'] = $data[1][9];
        $colum['country'] = $pais;
        
        if ($data[1][10]=='business') {
            $colum['delivery_preference'] = 1;
        } else {
            $colum['delivery_preference'] = 0;
        }
        $colum['receiver_name'] = $data[2][0];
        $colum['receiver_phone'] = $data[2][1];
        $colum['description'] = $data[3][0];
        $colum['date_first_visit'] = $f_first_visita;
        $colum['date_delivered'] = $f_delivered;
        $colum['date_not_delivered'] = $f_not_delivered;
        $colum['admin_ingre'] = (int)Auth::id();
        $colum['sticker'] = $data[0][5];
        
        $fields = $colum;

        try {
            $this->MELIService->insert_pack($fields);
        
            echo("Exito");
        } catch (\Throwable $th) {
            echo($th);
        }
        return;
    }

    public function save_pack_gitt (Request $request)
    {
        $data = request()->input('new_packet');

        //dd($data);
        
        $f_first_visita = NULL;
        $f_delivered = NULL;
        $f_not_delivered = NULL;

        $fecha_ahora = new \DateTime();
        $fecha_ahora->modify('-3 hours');
        
        $colum = [];

        $perfil = $this->LoginController->perfil($request); //$this->perfil($request);

        if ($perfil == 'administ') {      //Si el usuario es admin, se asigna el sender_id que viene en la data, sino se asigna el id del cliente logueado
            $colum['sender_id'] = $data['sender_id'];
            $colum['admin_ingre'] = (int)Auth::id();
        }else {

            $colum['sender_id'] = 'G' . (int)Auth::id();
            $colum['admin_ingre'] = NULL;
        }
                
        
        $colum['date_in'] = $fecha_ahora->format('Y-m-d H:i:s');
        //$colum['date_in'] = $data[0][1]['date'];
        $colum['status'] = 'Pendiente';
        $colum['street_name'] = $data['street_name'];
        $colum['street_number'] = intval ($data['street_number']);
        $colum['comment'] = $data['comment'];
        $colum['zip_code'] = intval ($data['zip_code']);
        $colum['city'] = $data['city'];
        $colum['state'] = $data['state'];
        //$colum['Latit'] = $data['Latit'];
        //$colum['Longi'] = $data['Longi'];
        //$colum['last_geo'] = $data[1][9];
        $colum['country'] = 1;  //Gitt solo opera en Argentina, por lo que se asigna el valor 1 a country
        $colum['delivery_preference'] = $data['delivery_preference'];
        $colum['receiver_name'] = $data['receiver_name'];
        $colum['receiver_phone'] = $data['receiver_phone'];
        $colum['description'] = $data['description'];
        $colum['date_first_visit'] = $f_first_visita;
        $colum['date_delivered'] = $f_delivered;
        $colum['date_not_delivered'] = $f_not_delivered;
        
        if (isset($data['id_ship'])) {
            $colum['id_ship'] = $data['id_ship'];
            $colum['sticker'] = '{"id":"' . $colum['id_ship'] . '","sender_id":"' . $colum['sender_id'] . '","hash_code":"vacio","security_digit":"0"}';
            $colum['TN'] = 1;   
        } else {
            $ultimo_id = envios::latest('id_num')->first()->id_num; //latest('id_ship')->first()->id_num;
            $number = $ultimo_id + 1;
            $colum['id_ship'] = 'G' . $number;
            $colum['order_id'] = $colum['id_ship'];
            $colum['sticker'] = '{"id":"' . $colum['id_ship'] . '","sender_id":"' . $colum['sender_id'] . '","hash_code":"vacio","security_digit":"0"}';
            $colum['TN'] = 1;
        }
        

        //dd($colum);
        try {
            $this->MELIService->insert_pack($colum);

            $title='Ingreso exitoso';
            $mensaje = 'Envío ingresado exitosamente';

            if ($perfil == 'administ') {      //Si el usuario es admin, se muestra la vista de éxito para admin, sino se muestra la vista de éxito para clientes
                return view('include_pack_success', compact('title', 'mensaje'));
            }else {
                $dat_user = Auth::user();
                return view('include_pack_success', compact('title', 'mensaje', 'dat_user'));
            }
        
        } catch (\Throwable $th) {
            $this->MELIService->edit_envios('id_ship',$colum);

            $title='Edición exitosa';
            $mensaje = 'Envío editado correctamente';

            if ($perfil == 'administ') {      //Si el usuario es admin, se muestra la vista de éxito para admin, sino se muestra la vista de éxito para clientes
                return view('include_pack_success', compact('title', 'mensaje'));
            }else {
                $dat_user = Auth::user();
                return view('include_pack_success', compact('title', 'dat_user', 'mensaje'));
            }
        }
        
    }

    public function prueba (){
        
        //Función para probar el ingreso de envios a la BBDD
        //$data = request()->input();
        $data = '[[44411717502,{"date":"2025-01-29 02:40:07.015958","timezone_type":3,"timezone":"UTC"},"delivered",183074533,2000010583073126,"{\\"id\\":44411717502,\\"sender_id\\":183074533,\\"hash_code\\":\\"(vacio\\",\\"security_digit\\":\\")\\"}"],["Calle Francisco Hue","3449","Referencia: Casa de suegros","1653","Villa Ballester","Buenos Aires","Argentina",-34.565482,-58.551609,"2025-01-28T02:47:09.396Z","residential"],["Diego Martin Dalla Costa","XXXXXXX"],["Sombrilla Carpa Reforzada Waggs Somb04 Aluminizada 2.70m Color Rojo Lisa","8.0x12.0x101.0,2060.0"],["2025-01-28T17:51:16.000-04:00","2025-01-28T17:51:16.000-04:00",null]]';

        $data = json_decode($data,true);
        //print_r($data);

        $fields=[];
            
        switch ($data[1][6]) {
        case "Argentina":
            $pais = 1;
            break;
        case "Brasil":
            $pais = 2;
            break;
        case "Chile":
            $pais = 3;
            break;
        case "Perú":
            $pais = 4;
            break;
        case "Venezuela":
            $pais = 5;
            break;
        default:
            $pais = 1;
        }
        
        if ($data[4][0]==NULL) {
            $f_first_visita = NULL;
        } else {
            $f_first_visita = new \DateTime(substr($data[4][0], 0, 19));
            $f_first_visita->modify('-1 hours');
        }

        if ($data[4][1]==NULL) {
            $f_delivered = NULL;
        } else {
            $f_delivered = new \DateTime(substr($data[4][1], 0, 19));
            $f_delivered->modify('-1 hours');
        }

        if ($data[4][2]==NULL) {
            $f_not_delivered = NULL;
        } else {
            $f_not_delivered = new \DateTime(substr($data[4][2], 0, 19));
            $f_not_delivered->modify('-1 hours');
        }
        
        $colum = [];
        $colum['id_ship'] = $data[0][0];
        $colum['date_in'] = new \DateTime();
        //$colum['date_in'] = $data[0][1]['date'];
        $colum['status'] = $data[0][2];
        $colum['sender_id'] = $data[0][3];
        $colum['order_id'] = $data[0][4];
        $colum['street_name'] = $data[1][0];
        $colum['street_number'] = intval ($data[1][1]);
        $colum['comment'] = $data[1][2];
        $colum['zip_code'] = intval ($data[1][3]);
        $colum['city'] = $data[1][4];
        $colum['state'] = $data[1][5];
        $colum['Latit'] = $data[1][7];
        $colum['Longi'] = $data[1][8];
        //$colum['last_geo'] = $data[1][9];
        $colum['country'] = $pais;
        
        if ($data[1][10]=='business') {
            $colum['delivery_preference'] = 1;
        } else {
            $colum['delivery_preference'] = 0;
        }
        $colum['receiver_name'] = $data[2][0];
        $colum['receiver_phone'] = $data[2][1];
        $colum['description'] = $data[3][0];
        $colum['date_first_visit'] = $f_first_visita;
        $colum['date_delivered'] = $f_delivered;
        $colum['date_not_delivered'] = $f_not_delivered;
        
        $fields = $colum;
        
        $this->MELIService->insert_pack($fields);
        
        echo("Exito");
        return;
    }

    public function save_pack_actua ()
    {
        //El retraso se incluye dentro de la función update_by_lots de MELIServices
        $data = request()->input();

        $n_data = count($data);
    
        $fields=[];

        $Num_envio_ML = 0; 
        
        for ($i = 0; $i < $n_data; $i++) {
            

            try {

                switch ($data[$i][1][6]) {
                case "Argentina":
                    $pais = 1;
                    break;
                case "Brasil":
                    $pais = 2;
                    break;
                case "Chile":
                    $pais = 3;
                    break;
                case "Perú":
                    $pais = 4;
                    break;
                case "Venezuela":
                    $pais = 5;
                    break;
                default:
                    $pais = 1;
                }
                
                if ($data[$i][4][0]==NULL) {
                    $f_first_visita = NULL;
                } else {
                    $f_first_visita = new \DateTime(substr($data[$i][4][0], 0, 19));
                    $f_first_visita->modify('+1 hours');
                }

                if ($data[$i][4][1]==NULL) {
                    $f_delivered = NULL;
                } else {
                    $f_delivered = new \DateTime(substr($data[$i][4][1], 0, 19));
                    $f_delivered->modify('+1 hours');
                }

                if ($data[$i][4][2]==NULL) {
                    $f_not_delivered = NULL;
                } else {
                    $f_not_delivered = new \DateTime(substr($data[$i][4][2], 0, 19));
                    $f_not_delivered->modify('+1 hours');
                }
                
                $colum = [];
                $colum['id_ship'] = $data[$i][0][0];
                $colum['status'] = $data[$i][0][2];
                $colum['sender_id'] = $data[$i][0][3];
                $colum['order_id'] = $data[$i][0][4];
                $colum['street_name'] = $data[$i][1][0];
                $colum['street_number'] = intval ($data[$i][1][1]);
                $colum['comment'] = $data[$i][1][2];
                $colum['zip_code'] = intval ($data[$i][1][3]);
                $colum['city'] = $data[$i][1][4];
                $colum['state'] = $data[$i][1][5];
                $colum['Latit'] = $data[$i][1][7];
                $colum['Longi'] = $data[$i][1][8];
                //$colum['last_geo'] = $data[$i][1][9];
                $colum['country'] = $pais;
                
                if ($data[$i][1][10]=='business') {
                    $colum['delivery_preference'] = 1;
                } else {
                    $colum['delivery_preference'] = 0;
                }
                $colum['receiver_name'] = $data[$i][2][0];
                $colum['receiver_phone'] = $data[$i][2][1];
                $colum['description'] = $data[$i][3][0];
                $colum['date_first_visit'] = $f_first_visita;
                $colum['date_delivered'] = $f_delivered;
                $colum['date_not_delivered'] = $f_not_delivered;
                
                $fields[$Num_envio_ML] = $colum;

                $Num_envio_ML = $Num_envio_ML + 1;

            } catch (\Throwable $th) {
                //throw $th;
            }
            
        }

        /* print_r($fields);
        return; */
        $this->MELIService->update_by_lots ('id_ship', $fields);
        
        echo("Envíos actualizados con éxito");
        return;
    }

    public function cancel_client()
    {
        $id_ship = request()->input('id_ship');
        //$idship = json_decode($data,true);
        
        $dat_user = Auth::user();
        $client_Gitt = clientes::where('id', $dat_user->id)->first();


        //return $idship;
        $envio = envios::where('id_ship','=',rtrim($id_ship))->first();

        if ($client_Gitt->id_Gitt == $envio->sender_id) {
            
            $envio->status = 'cancelled';
            $envio->save();

            $title = 'Envío cancelado';
            $mensaje = 'El envío ha sido cancelado exitosamente';

            return view('include_pack_success', compact('title', 'mensaje', 'dat_user'));

        } else {
            $title = 'Error';
            $mensaje = 'No tienes permiso para cancelar este envío';

            return view('include_pack_success', compact('title', 'mensaje', 'dat_user'));
        }

        
    }

    public function delete_pack()
    {
        $id_ship = request()->input('id_ship');
        //$idship = json_decode($data,true);
        
        $dat_user = Auth::user();

        if (($dat_user->master == 1) || ($dat_user->admin == 1)) {
            
            $this->MELIService->delete_envios('id_ship', rtrim($id_ship));

            $title = 'Envío Borrado';
            $mensaje = 'El envío ha sido borrado exitosamente';

            return view('include_pack_success', compact('title', 'mensaje', 'dat_user'));

        } else {
            $title = 'Error';
            $mensaje = 'No tienes permiso para borrar este envío';

            return view('include_pack_success', compact('title', 'mensaje', 'dat_user'));
        }
                
    }
    
}
