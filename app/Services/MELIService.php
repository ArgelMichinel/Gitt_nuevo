<?php

namespace App\Services;

use App\Models\access_meli;
use App\Models\clientes;
use App\Models\envios;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MELIService
{
    public function getLatestPackets($limit = 300)
    {
        return Envios::orderBy('date_in', 'desc')->take($limit)->get()->toArray();
    }

    public function info_shipping($shipnumb,$ACCESS_TOK) {

        $cliente = curl_init();
        curl_setopt($cliente, CURLOPT_URL, 'https://api.mercadolibre.com/shipments/'.$shipnumb);
        curl_setopt($cliente, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($cliente, CURLOPT_HEADER, false);
        curl_setopt($cliente, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$ACCESS_TOK));
        curl_setopt($cliente, CURLOPT_RETURNTRANSFER, true);
    
        $result = curl_exec($cliente);
        curl_close($cliente);
    
        //$datos=json_decode($result,true);
    
        return $result;
    }
    
    ////////////////////////////////////////////////////////////
    
    public function info_user($pdo,$id_client) {
        
        $client = $this ->checkValdTok($pdo,$id_client);
        
        $cliente = curl_init();
        curl_setopt($cliente, CURLOPT_URL, 'https://api.mercadolibre.com/users/'.$id_client);
        curl_setopt($cliente, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($cliente, CURLOPT_HEADER, false);
        curl_setopt($cliente, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$client['access_tok']));
        curl_setopt($cliente, CURLOPT_RETURNTRANSFER, true);
    
        $result = curl_exec($cliente);
        curl_close($cliente);
    
        $datos=json_decode($result,true);
    
        return $datos;
    }
    
    ///////////////////////////////////////////////////////////////
    
    public function refresh_tok($APP_ID,$SECRET_KEY,$REFRESH_TOK) {
    
        $body = array(
                        'grant_type' => 'refresh_token',
                        'client_id' => $APP_ID,
                        'client_secret' => $SECRET_KEY,
                        'refresh_token' => $REFRESH_TOK
                    );
    
        $headers_req =array(
                            'POST' => 'application/json',
                            'Content-type' => 'application/x-www-form-urlencoded'
                         );
    
        $cliente = curl_init();
        curl_setopt($cliente, CURLOPT_URL, 'https://api.mercadolibre.com/oauth/token');
        curl_setopt($cliente, CURLOPT_POST, TRUE);
        curl_setopt($cliente, CURLOPT_HEADER, false);
        curl_setopt($cliente, CURLOPT_HTTPHEADER, $headers_req);
        curl_setopt($cliente, CURLOPT_POSTFIELDS, $body);
        curl_setopt($cliente, CURLOPT_RETURNTRANSFER, true);
    
        $result = curl_exec($cliente);
        curl_close($cliente);
    
    
        //print_r($result);
    
        $datos=json_decode($result,true);
        
        return $datos;
        
    }
    
    ////////////////////////////////////////////////////////////////
    
    public function request_tok($code,$state,$APP_ID,$SECRET_KEY,$URL) {
    
        $body = array(
                        'grant_type' => 'authorization_code',
                        'client_id' => $APP_ID,
                        'client_secret' => $SECRET_KEY,
                        'code' => $code,
                        'redirect_uri' => $URL
                    );
    
        $headers_req =array(
                            'POST' => 'application/json',
                            'Content-Type' => 'application/x-www-form-urlencoded'
                         );
    
        $cliente = curl_init();
        curl_setopt($cliente, CURLOPT_URL, "https://api.mercadolibre.com/oauth/token");
        curl_setopt($cliente, CURLOPT_POST, TRUE);
        curl_setopt($cliente, CURLOPT_HEADER, false);
        curl_setopt($cliente, CURLOPT_HTTPHEADER, $headers_req);
        curl_setopt($cliente, CURLOPT_POSTFIELDS, $body);
        curl_setopt($cliente, CURLOPT_RETURNTRANSFER, true);
    
        $result = curl_exec($cliente);
        curl_close($cliente);
    
        //print_r($result);
    
        $datos=json_decode($result,true);
        
        return $datos;
    }
    
    ///////////////////////////////////////////////////////
    public function update_access($fields) {
        $access_meli_selecc = access_meli::where('user_id', $fields->user_id)->get();
        
        $access_meli_selecc =$fields;

        $access_meli_selecc->save();
    }
    
    //////////////////////////////////////////////////////// modificado
    
    public function update($table, $primaryKey, $fields) {
    
        $seleccionado = DB::table($table)->where($primaryKey, $fields[$primaryKey])->get(); 
        $seleccionado = $fields;
        $seleccionado -> save();
    }
    
    //////////////////////////////////////////////////////// modificada
    
    public function ask_client($id_client) {

        $dat_user = clientes::find($id_client);
        
        return $dat_user;
    }
    
    /////////////////////////////////////////////////////// modificada
    
    public function checkValdTok($pdo,$id_client) {
        $timeNow =  Carbon::now();
        
        $dat_user = $this-> ask_client($pdo,$id_client);
        $time_access = Carbon::parse($dat_user['fec_hora']);
        
        $interva = $time_access -> diff($timeNow);
        $interva = $this-> Diff_On_Sec ($interva);
        
        if ($interva > 648000) {
                $message = 'Usuario con credenciales expiradas';
        } elseif (($interva > 21600) and ($interva < 648000)) {
                include 'Datosprogram.php';
                $datos = $this-> refresh_tok($APP_ID,$SECRET_KEY,$dat_user['refresh_tok']);
                $datos -> fec_hora = Carbon::now();
                $access_meli_selecc = access_meli::where('user_id', $id_client)->get();
                
                foreach ($datos as $key => $value) {
                    $access_meli_selecc [$key] = $value;
                }
                $access_meli_selecc ->save();
        } 
        
        return $access_meli_selecc;
        
    }
    
    ///////////////////////////////////////////////////////
    
    public function Diff_On_Sec ($interva){
    $interva = ($interva -> format('%Y'))*365*24*60*60 +
               ($interva -> format('%m'))*30*24*60*60 +
               ($interva -> format('%d'))*24*60*60 +
               ($interva -> format('%H'))*60*60 +
               ($interva -> format('%i'))*60 +
               ($interva -> format('%s'));
        
    return $interva;
        }
    
    ///////////////////////////////////////////////////////
    
    public function insert_by_lots ($table, $fields) {
       
        DB::table($table)->insert($fields); 
        
    }
    
    ///////////////////////////////////////////////////////
    
    public function query_customized ($parameters) {
        
        $query='SELECT * FROM `envios` WHERE ';
        
        $datos = [];
        $envios = DB::table('envios');
            
        if (isset($parameters['incl_client'])) {
                $envios -> where('sender_id', $parameters['client']);
        }
        if (isset($parameters['incl_cadete'])) {
                if ($parameters['cadete'] == 'none') {
                    $envios -> where('cadete', null);
                } else {
                    $envios -> where('cadete', $parameters['cadete']);
                }
        }
        if (isset($parameters['incl_ayer'])) {
            $parameters['incl_date'] = false;
            $parameters['end_date'] = date("Y-m-d");
            date_default_timezone_set('America/Argentina/Buenos_Aires');
            $dia_ayer = strtotime('-1 day', strtotime($parameters['end_date']));
            $dia_ayer = date('Y-m-d', $dia_ayer);
            $parameters['begin_date'] = $dia_ayer;
                
            $envios -> where('date_in', '>=', $parameters['begin_date']) -> where('date_in', '<', $parameters['end_date']);

        }
        if (isset($parameters['incl_hoy'])) {
            $parameters['incl_date'] = false;
            $parameters['incl_ayer'] = false;
            date_default_timezone_set('America/Argentina/Buenos_Aires');
            $parameters['begin_date'] = date("Y-m-d");
            $dia_manana = strtotime('+1 day', strtotime($parameters['begin_date']));
            $dia_manana = date('Y-m-d', $dia_manana);
            $parameters['end_date'] = $dia_manana;
            
            $envios -> where('date_in', '>=', $parameters['begin_date']) -> where('date_in', '<', $parameters['end_date']);
                
        }
        if (isset($parameters['incl_date'])) {
                if (isset($parameters['begin_date']) && isset($parameters['end_date'])) {
                    $envios -> where('date_in', '>=', $parameters['begin_date']) -> where('date_in', '<', $parameters['end_date']);
                }
        }
        if (isset($parameters['incl_zona'])) {
            switch ($parameters['zona']) {
              case "Zona 1":
                $envios -> where('zip_code', '<=', 1500);
                break;

            case "Zona 2":
                $Auxiliar = [
                    1602, 1603, 1604, 1605, 1606, 1607, 1609, 1636, 1637, 1637, 1638, 1639, 1640, 1641, 1642, 1643, 1644, 1645, 1649, 
                    1650, 1651, 1652, 1653, 1654, 1655, 1657, 1672, 1674, 1675, 1676, 1678, 1682, 1683, 1684, 1685, 1686, 1687, 1688, 
                    1689, 1690, 1691, 1692, 1701, 1702, 1703, 1704, 1706, 1707, 1708, 1710, 1712, 1713, 1714, 1715, 1721, 1751, 1752, 
                    1753, 1754, 1766, 1768, 1770, 1771, 1772, 1773, 1774, 1785, 1809, 1821, 1822, 1823, 1824, 1825, 1826, 1827, 1828, 
                    1829, 1831, 1832, 1833, 1834, 1835, 1836, 1868, 1869, 1870, 1871, 1872, 1873, 1874, 1875
                    ];
                $envios -> whereIn('zip_code', $Auxiliar);
                break;

                case "Zona 3":
                $Auxiliar = [
                    1608, 1610, 1611, 1612, 1613, 1614, 1615, 1616, 1617, 1618, 1621, 1622, 1624, 1646, 1648, 1659, 1660, 1661, 1662, 
                    1663, 1664, 1665, 1666, 1670, 1716, 1718, 1722, 1723, 1724, 1736, 1738, 1740, 1742, 1743, 1744, 1745, 1746, 1750, 
                    1755, 1756, 1757, 1758, 1759, 1761, 1763, 1764, 1765, 1776, 1778, 1780, 1781, 1786, 1789, 1801, 1802, 1803, 1804, 
                    1805, 1806, 1807, 1812, 1813, 1837, 1838, 1839, 1840, 1841, 1842, 1843, 1844, 1845, 1846, 1847, 1848, 1849, 1850, 
                    1851, 1852, 1853, 1854, 1855, 1856, 1857, 1858, 1859, 1860, 1861, 1862, 1863, 1867, 1876, 1877, 1878, 1879, 1880, 
                    1881, 1882, 1883, 1884, 1885, 1886, 1887, 1888, 1889, 1890, 1891, 1892, 1893, 1916
                ];
                $envios -> whereIn('zip_code', $Auxiliar);
                break;
              default:
                
            }
        }
        if (isset($parameters['incl_comercial'])) {
                
            $envios -> where('delivery_preference', 1);
                
        }
        
        $result = $envios->get();

        $result = json_decode(json_encode($result), true);
        
        return $result;
    }
    
    /////////////////////////////////////////////////////// modificada
    
    public function update_by_lots ($table, $primaryKey, $fields) {
        
        $n_data = count($fields);
        for ($i = 0; $i < $n_data; $i++) {
            
            $colum = $fields[$i];
            /////////////////////////////////////////////////
            $this -> update($table, $primaryKey, $colum);
            
        }  
        
    }
    
    //////////////////////////////////////////////////////
    public function print_answer ($shipnum,$ACCESS_TOK,$sender_id,$sticker) {
        $shipping = $this -> info_shipping($shipnum,$ACCESS_TOK);
    
        $hash_code = $sticker;
    
        $matriz_QR = [];
        $matriz_QR['id']= $shipnum;
        $matriz_QR['sender_id']= $sender_id;   
        $matriz_QR['hash_code']= substr($hash_code, 0, -1);  
        $matriz_QR['security_digit']= substr($hash_code, -1);;
    
        $sticker = json_encode($matriz_QR);
    
        //////////////////////////////////////
        $shipping_res = json_decode($shipping,true);
        
        $shipping = [];
        $shipping[0] = $shipnum;                   //'id_ship'
        $shipping[1] = Carbon::now();             //'date_in'
        $shipping[2] = $shipping_res['status'];     //'status'
        $shipping[3] = $sender_id;                  //'sender_id'
        $shipping[4] = $shipping_res['order_id'];        //'order_id'
        $shipping[5] = $sticker;                      //'Etiqueta'
        
        $address = [];
        $address[0] = $shipping_res['receiver_address']['street_name'];        //'street_name'
        $address[1] = $shipping_res['receiver_address']['street_number'];      //'street_number'
        $address[2] = $shipping_res['receiver_address']['comment'];            //'comment'
        $address[3] = $shipping_res['receiver_address']['zip_code'];           //'zip_code'
        $address[4] = $shipping_res['receiver_address']['city']['name'];       //'city'
        $address[5] = $shipping_res['receiver_address']['state']['name'];      //'state'
        $address[6] = $shipping_res['receiver_address']['country']['name'];    //'country'
        $address[7] = $shipping_res['receiver_address']['latitude'];           //'latitude'
        $address[8] = $shipping_res['receiver_address']['longitude'];          //'longitude'
        $address[9] = $shipping_res['receiver_address']['geolocation_last_updated'];    //'geolocation_last_updated'
        $address[10] = $shipping_res['receiver_address']['delivery_preference'];        //'delivery_preference'
    
        $receiver_per = [];
        $receiver_per[0] = $shipping_res['receiver_address']['receiver_name'];      //'receiver_name'
        $receiver_per[1] = $shipping_res['receiver_address']['receiver_phone'];      //'receiver_phone'
        
        $shipping_items = [];
        $shipping_items[0] = $shipping_res['shipping_items'][0]['description'];          //'description'
        $shipping_items[1] = $shipping_res['shipping_items'][0]['dimensions'];            //'dimensions'
        
        $delivery = [];
        $delivery[0] = $shipping_res['status_history']['date_first_visit'];     //'date_first_visit'
        $delivery[1] = $shipping_res['status_history']['date_delivered'];       //'date_delivered'
        $delivery[2] = $shipping_res['status_history']['date_not_delivered'];    //'date_not_delivered'
    
        $ship_mat = [$shipping, $address, $receiver_per, $shipping_items, $delivery];
        
        //////////////////////////////////////
        
        return $ship_mat;
    }
    
    //////////////////////////////////////////////////////
    public function basico ($shipnum,$ACCESS_TOK,$sender_id) {
        $shipping = $this -> info_shipping($shipnum,$ACCESS_TOK);
    
        //////////////////////////////////////
        $shipping_res = json_decode($shipping,true);
        
        $shipping = [];
        $shipping[0] = $shipnum;                   //'id_ship'
        $shipping[1] = Carbon::now();             //'date_in'
        $shipping[2] = $shipping_res['status'];     //'status'
        $shipping[3] = $sender_id;                  //'sender_id'
        $shipping[4] = $shipping_res['order_id'];        //'order_id'
        
        $address = [];
        $address[0] = $shipping_res['receiver_address']['street_name'];        //'street_name'
        $address[1] = $shipping_res['receiver_address']['street_number'];      //'street_number'
        $address[2] = $shipping_res['receiver_address']['comment'];            //'comment'
        $address[3] = $shipping_res['receiver_address']['zip_code'];           //'zip_code'
        $address[4] = $shipping_res['receiver_address']['city']['name'];       //'city'
        $address[5] = $shipping_res['receiver_address']['state']['name'];      //'state'
        $address[6] = $shipping_res['receiver_address']['country']['name'];    //'country'
        $address[7] = $shipping_res['receiver_address']['latitude'];           //'latitude'
        $address[8] = $shipping_res['receiver_address']['longitude'];          //'longitude'
        $address[9] = $shipping_res['receiver_address']['geolocation_last_updated'];    //'geolocation_last_updated'
    
        $receiver_per = [];
        $receiver_per[0] = $shipping_res['receiver_address']['receiver_name'];      //'receiver_name'
        $receiver_per[1] = $shipping_res['receiver_address']['receiver_phone'];      //'receiver_phone'
        
        $shipping_items = [];
        $shipping_items[0] = $shipping_res['shipping_items'][0]['description'];          //'description'
        $shipping_items[1] = $shipping_res['shipping_items'][0]['dimensions'];            //'dimensions'
        
        $delivery = [];
        $delivery[0] = $shipping_res['status_history']['date_first_visit'];     //'date_first_visit'
        $delivery[1] = $shipping_res['status_history']['date_delivered'];       //'date_delivered'
        $delivery[2] = $shipping_res['status_history']['date_not_delivered'];    //'date_not_delivered'
    
        $ship_mat = [$shipping, $address, $receiver_per, $shipping_items, $delivery];
        
        //////////////////////////////////////
        
        return $ship_mat;
    }
}
