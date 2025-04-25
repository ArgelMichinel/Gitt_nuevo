<?php

namespace App\Http\Controllers;

use App\Services\MELIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class savePackController extends Controller
{
    protected $MELIService;

    public function __construct(MELIService $MELIService)
    {
        $this->MELIService = $MELIService;
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
        $colum['admin_ingre'] = (int)Auth::id();
        $colum['sticker'] = $data[0][5];
        
        $fields = $colum;
        
        $this->MELIService->insert_pack($fields);
        
        echo("Exito");
        return;
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
        
        for ($i = 0; $i < $n_data; $i++) {
            
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
            
            $fields[$i] = $colum;
        }

        /* print_r($fields);
        return; */
        $this->MELIService->update_by_lots ('id_ship', $fields);
        
        echo("Envíos actualizados con éxito");
        return;
    }

    public function prueba_actua () {
        $data = '[[[44409045336,{"date":"2025-02-09 00:41:04.207355","timezone_type":3,"timezone":"UTC"},"ready_to_ship",2234407520,2000010577105718,"{\"id\":44409045336,\"sender_id\":2234407520,\"hash_code\":\"(vacio\",\"security_digit\":\")\"}"],["Avenida Corrientes","825","2A","1043","San Nicolás","Capital Federal","Argentina",-34.6033949,-58.37857149999999,"2025-01-26T05:29:26.017Z","residential"],["Juan Miguel","XXXXXXX"],["Item De Prueba 17- Por Favor, No Ofertar","8.0x14.0x19.0,220.0"],[null,null,null]],[[44462482137,{"date":"2025-02-09 00:41:04.209034","timezone_type":3,"timezone":"UTC"},"ready_to_ship",2234407520,2000010697749494,"{\"id\":44462482137,\"sender_id\":2234407520,\"hash_code\":\"(vacio\",\"security_digit\":\")\"}"],["Calle Peru","1317","5C Referencia: Tocar el timbre","1141","San Telmo","Capital Federal","Argentina",-34.623919349295164,-58.37419114603273,"2025-02-08T22:33:56.024Z","residential"],["Eloy Sanchez","XXXXXXX"],["Item De Prueba 17- Por Favor, No Ofertar","8.0x14.0x19.0,220.0"],[null,null,null]]]';
        
        $data = json_decode($data,true);
        $n_data = count($data);
        //print_r($data);

        $fields=[];
        
        for ($i = 0; $i < $n_data; $i++) {
            
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
            
            $fields[$i] = $colum;
        }
        
        $this->MELIService->update_by_lots ('id_ship', $fields);
        
        echo("Envíos actualizados con éxito");
        return;
    }
    
}
