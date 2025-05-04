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

    public function info_shipping_TN($user_id,$access_tok,$NOMBRE_CARRIER_TN,$CONTACT_APP_TN,$id_order) {

        $URL_order_TN = "https://api.tiendanube.com/2025-03/". $user_id . "/orders/". $id_order;
        
        $headers_req =array(
            'Authentication: bearer ' . trim($access_tok),
            'Content-Type: application/json',
            'User-Agent: ' . $NOMBRE_CARRIER_TN . ' (' . $CONTACT_APP_TN . ')'
         );

        $cliente = curl_init();
        curl_setopt($cliente, CURLOPT_URL, $URL_order_TN);
        curl_setopt($cliente, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($cliente, CURLOPT_HEADER, false);
        curl_setopt($cliente, CURLOPT_HTTPHEADER, $headers_req);
        curl_setopt($cliente, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($cliente);
        
        curl_close($cliente);
    
        //print_r($result);
    
        $datos = $result; //json_decode($result,true);
        if ($result === false) {
            $datos = curl_error($cliente);
        }
        
        return $datos;
    }
    
    //////////////////////////////////////////////////////////// modificada
    
    public function info_user($id_client,$APP_ID,$SECRET_KEY) {
        
        $client = $this ->checkValdTok($id_client,$APP_ID,$SECRET_KEY);
        
        $cliente = curl_init();
        $ACCESS_TOK = $client['access_tok'];
        curl_setopt($cliente, CURLOPT_URL, 'https://api.mercadolibre.com/users/'.$id_client);
        curl_setopt($cliente, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($cliente, CURLOPT_HEADER, false);
        curl_setopt($cliente, CURLOPT_HTTPHEADER, array('Authorization: Bearer '. $ACCESS_TOK));
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
    ////////////////////////////////////////////////////////////////
    
    public function integracion_Tiendanube($code,$CLIENT_ID_TN,$client_secret,$URL_TN) {

        $body = array(
                        'grant_type' => 'authorization_code',
                        'client_id' => $CLIENT_ID_TN,
                        'client_secret' => $client_secret,
                        'code' => $code
                    );
    
        $headers_req =array(
                            'Content-Type' => 'application/x-www-form-urlencoded'
                         );
    
        $cliente = curl_init();
        curl_setopt($cliente, CURLOPT_URL, $URL_TN);
        curl_setopt($cliente, CURLOPT_POST, TRUE);
        curl_setopt($cliente, CURLOPT_HTTPHEADER, $headers_req);
        curl_setopt($cliente, CURLOPT_POSTFIELDS, http_build_query($body));
        curl_setopt($cliente, CURLOPT_RETURNTRANSFER, true);
    
        $result = curl_exec($cliente);
        curl_close($cliente);
    
        //print_r($result);
    
        $datos = $result; //json_decode($result,true);
        if ($result === false) {
            $datos = curl_error($cliente);
        }
        
        return $datos;
    }
    
    ////////////////////////////////////////////////////////////////
    
    public function Crear_carrier_TN($user_id,$access_tok,$NOMBRE_CARRIER_TN,$WEBHOOK_PRECIOS,$CONTACT_APP_TN) {
    
        $URL_TN = "https://api.tiendanube.com/2025-03/". $user_id . "/shipping_carriers";

        $body = array(
                        'name' => $NOMBRE_CARRIER_TN,
                        'callback_url'  => $WEBHOOK_PRECIOS,
                        'types'  => 'ship'
                    );
    
        $headers_req =array(
                            'Authentication: bearer ' . trim($access_tok),
                            'Content-Type: application/json',
                            'User-Agent: ' . $NOMBRE_CARRIER_TN . ' (' . $CONTACT_APP_TN . ')'
                         );
    
        $cliente = curl_init();
        curl_setopt($cliente, CURLOPT_URL, $URL_TN);
        curl_setopt($cliente, CURLOPT_POST, TRUE);
        curl_setopt($cliente, CURLOPT_HTTPHEADER, $headers_req);
        curl_setopt($cliente, CURLOPT_POSTFIELDS, json_encode($body));
        curl_setopt($cliente, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($cliente, CURLOPT_RETURNTRANSFER, true);
    
        $result = curl_exec($cliente);
        
        curl_close($cliente);
    
        //print_r($result);
    
        $datos = $result; //json_decode($result,true);
        if ($result === false) {
            $datos = curl_error($cliente);
        }
        
        return $datos;
    }
    ////////////////////////////////////////////////////////////////
    
    public function Crear_carrier_opt_TN($user_id,$access_tok,$NOMBRE_CARRIER_TN,$CONTACT_APP_TN,$id_carrier) {
    
        $URL_TN = "https://api.tiendanube.com/2025-03/". $user_id . "/shipping_carriers/". $id_carrier . "/options";

        $body = array(
                        'code' => 'standard',
                        'name'  => 'Servicio de envío Estándar'
                    );
    
        $headers_req =array(
                            'Authentication: bearer ' . trim($access_tok),
                            'Content-Type: application/json',
                            'User-Agent: ' . $NOMBRE_CARRIER_TN . ' (' . $CONTACT_APP_TN . ')'
                         );
    
        $cliente = curl_init();
        curl_setopt($cliente, CURLOPT_URL, $URL_TN);
        curl_setopt($cliente, CURLOPT_POST, TRUE);
        curl_setopt($cliente, CURLOPT_HTTPHEADER, $headers_req);
        curl_setopt($cliente, CURLOPT_POSTFIELDS, json_encode($body));
        curl_setopt($cliente, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($cliente, CURLOPT_RETURNTRANSFER, true);
    
        $result = curl_exec($cliente);
        
        curl_close($cliente);
    
        //print_r($result);
    
        $datos = $result; //json_decode($result,true);
        if ($result === false) {
            $datos = curl_error($cliente);
        }
        
        return $datos;
    }
    
    ///////////////////////////////////////////////////////
    public function update_access($fields) {
        $access_meli_selecc = access_meli::where('user_id','=',$fields->user_id)->get();
        
        $access_meli_selecc =$fields;

        $access_meli_selecc->save();
    }
    
    //////////////////////////////////////////////////////// modificado
    
    public function update_envios($primaryKey, $fields) {
    
        $seleccionado = envios::where($primaryKey, '=', $fields[$primaryKey])->first(); 

        $seleccionado->status = $fields['status'];
        $seleccionado->street_name = $fields['street_name'];
        $seleccionado->date_first_visit = $fields['date_first_visit'];
        $seleccionado->date_delivered = $fields['date_delivered'];
        $seleccionado->date_not_delivered = $fields['date_not_delivered'];
        
        $seleccionado -> save();
    }
    
    //////////////////////////////////////////////////////// modificada
    
    public function ask_client($id_client) {

        $dat_user = clientes::find($id_client);
        
        return $dat_user;
    }
    
    /////////////////////////////////////////////////////// modificada Sirve solo para MELI
    
    public function checkValdTok($id_client,$APP_ID,$SECRET_KEY) {
        $timeNow =  new \DateTime();
        
        $dat_user = access_meli::where('user_id','=',$id_client)->first();
        $time_access = new \DateTime($dat_user['fec_hora']);
        
        $interva = $time_access -> diff($timeNow);
        $interva = $this-> Diff_On_Sec ($interva);
        //dd($interva);
        
        if ($interva > 15552000) { 
                $message = 'Usuario con credenciales expiradas';
                dd($message);
        } elseif (($interva > 21600) and ($interva < 15552000)) {
                $datos = $this-> refresh_tok($APP_ID,$SECRET_KEY,$dat_user['refresh_tok']);
                //dd($datos);
                
                $dat_user ['fec_hora'] = new \DateTime();
                $dat_user ['access_tok'] = $datos['access_token'];
                $dat_user ['refresh_tok'] = $datos['refresh_token'];
                //dd($access_meli_selecc);
                $dat_user ->save();

                
        } 
        
        return $dat_user;
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
    
    /////////////////////////////////////////////////////// modificada
    
    public function insert_by_lots ($fields) {
        
        $n_pack = count($fields);

        for ($i=0; $i < $n_pack; $i++) { 
            $this->insert_pack($fields[$i]);
        }
        
    }
    
    ///////////////////////////////////////////////////////
    
    public function query_customized ($parameters) {
        
        $query='SELECT * FROM `envios` WHERE ';
        
        $datos = [];
        $envios = DB::table('envios');
            
        if (isset($parameters['incl_cadete'])) {
                if ($parameters['cadete'] == 'none') {
                    $envios -> where('cadete1', null);
                } else {
                    $envios -> where('cadete1', $parameters['cadete']);
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

                
            case "Zona 4":
                $Auxiliar = [
                    1619, 1620, 1623, 1625, 1625, 1625, 1625, 1626, 1627, 1627, 1628, 1629, 1630, 1631, 1632, 1633, 1633, 1633, 1633, 
                    1634, 1635, 1635, 1647, 1664, 1664, 1667, 1667, 1669, 1717, 1727, 1747, 1748, 1748, 1749, 1787, 1788, 1790, 1791, 
                    1793, 1808, 1809, 1811, 1812, 1812, 1814, 1815, 1816, 1816, 1816, 1816, 1816, 1856, 1858, 1862, 1864, 1865, 1866, 
                    1894, 1894, 1895, 1896, 1897, 1897, 1898, 1898, 1900, 1900, 1900, 1901, 1901, 1901, 1902, 1903, 1903, 1903, 1903, 
                    1904, 1905, 1906, 1907, 1907, 1908, 1909, 1910, 1912, 1914, 1916, 1923, 1924, 1924, 1925, 1925, 1926, 1927, 1929, 
                    1931, 1933, 2800, 2800, 2800, 2801, 2801, 2802, 2804, 2804, 2804, 2804, 2805, 2805, 2805, 2805, 2805, 2805, 2805, 
                    2805, 2805, 2805, 2805, 2805, 2805, 2805, 2805, 2805, 2805, 2805, 2805, 2805, 2805, 2805, 2805, 2805, 2805, 2805, 
                    2805, 2806, 2806, 2808, 2812, 2812, 2812, 2812, 2814, 2816, 6700, 6700, 6700, 6700, 6701, 6702, 6706, 6706, 6706, 
                    6708, 6712, 6712, 6717
                ];
                $envios -> whereIn('zip_code', $Auxiliar);
            break;
              
                default:
                    $datos['zip_max'] = 1500;
                    $Auxiliar = [
                                1602, 1603, 1604, 1605, 1606, 1607, 1609, 1636, 1637, 1637, 1638, 1639, 1640, 1641, 1642, 1643, 1644, 1645, 1649, 
                                1650, 1651, 1652, 1653, 1654, 1655, 1657, 1672, 1674, 1675, 1676, 1678, 1682, 1683, 1684, 1685, 1686, 1687, 1688, 
                                1689, 1690, 1691, 1692, 1701, 1702, 1703, 1704, 1706, 1707, 1708, 1710, 1712, 1713, 1714, 1715, 1721, 1751, 1752, 
                                1753, 1754, 1766, 1768, 1770, 1771, 1772, 1773, 1774, 1785, 1809, 1821, 1822, 1823, 1824, 1825, 1826, 1827, 1828, 
                                1829, 1831, 1832, 1833, 1834, 1835, 1836, 1868, 1869, 1870, 1871, 1872, 1873, 1874, 1875, 1608, 1610, 1611, 1612, 
                                1613, 1614, 1615, 1616, 1617, 1618, 1621, 1622, 1624, 1646, 1648, 1659, 1660, 1661, 1662, 
                                1663, 1664, 1665, 1666, 1670, 1716, 1718, 1722, 1723, 1724, 1736, 1738, 1740, 1742, 1743, 1744, 1745, 1746, 1750, 
                                1755, 1756, 1757, 1758, 1759, 1761, 1763, 1764, 1765, 1776, 1778, 1780, 1781, 1786, 1789, 1801, 1802, 1803, 1804, 
                                1805, 1806, 1807, 1812, 1813, 1837, 1838, 1839, 1840, 1841, 1842, 1843, 1844, 1845, 1846, 1847, 1848, 1849, 1850, 
                                1851, 1852, 1853, 1854, 1855, 1856, 1857, 1858, 1859, 1860, 1861, 1862, 1863, 1867, 1876, 1877, 1878, 1879, 1880, 
                                1881, 1882, 1883, 1884, 1885, 1886, 1887, 1888, 1889, 1890, 1891, 1892, 1893, 1916,
                                1619, 1620, 1623, 1625, 1625, 1625, 1625, 1626, 1627, 1627, 1628, 1629, 1630, 1631, 1632, 1633, 1633, 1633, 1633, 
                                1634, 1635, 1635, 1647, 1664, 1664, 1667, 1667, 1669, 1717, 1727, 1747, 1748, 1748, 1749, 1787, 1788, 1790, 1791, 
                                1793, 1808, 1809, 1811, 1812, 1812, 1814, 1815, 1816, 1816, 1816, 1816, 1816, 1856, 1858, 1862, 1864, 1865, 1866, 
                                1894, 1894, 1895, 1896, 1897, 1897, 1898, 1898, 1900, 1900, 1900, 1901, 1901, 1901, 1902, 1903, 1903, 1903, 1903, 
                                1904, 1905, 1906, 1907, 1907, 1908, 1909, 1910, 1912, 1914, 1916, 1923, 1924, 1924, 1925, 1925, 1926, 1927, 1929, 
                                1931, 1933, 2800, 2800, 2800, 2801, 2801, 2802, 2804, 2804, 2804, 2804, 2805, 2805, 2805, 2805, 2805, 2805, 2805, 
                                2805, 2805, 2805, 2805, 2805, 2805, 2805, 2805, 2805, 2805, 2805, 2805, 2805, 2805, 2805, 2805, 2805, 2805, 2805, 
                                2805, 2806, 2806, 2808, 2812, 2812, 2812, 2812, 2814, 2816, 6700, 6700, 6700, 6700, 6701, 6702, 6706, 6706, 6706, 
                                6708, 6712, 6712, 6717
                            ];
                            $envios -> where('zip_code', '>', 1500) -> whereNotIn('zip_code', $Auxiliar);
            }
        }
        if (isset($parameters['incl_comercial'])) {
                
            $envios -> where('delivery_preference', 1);
                
        }
        if (isset($parameters['incl_client'])) {
            $envios2 = clone $envios;
            $id_MELI = clientes::where('id','=',$parameters['client'])->first()->id_MELI;
            $id_TN = clientes::where('id','=',$parameters['client'])->first()->id_TN;
            if ($id_MELI) {
                $envios -> where('sender_id', '=', $id_MELI);
                //dd('Paso por envios MELI '. $id_MELI. ' ' . $id_TN);
            } else {
                $envios -> where('sender_id', '=', 'XXXXXXXXX'); // Para que no arroje resultados porque el usuario no tiene integración con MELI
            }
            
            if ($id_TN) {
                $envios2 -> where('sender_id', '=', $id_TN);
                //dd('Paso por envios TN');
            } else {
                $envios2 -> where('sender_id', '=', 'XXXXXXXXX'); // Para que no arroje resultados porque el usuario no tiene integración con TN
            }

            $result1 = $envios->get();
            $result2 = $envios2->get();
            
            $result = $result1 -> merge($result2);

        } else {
            $result = $envios->get();
        }
        

        $result = json_decode(json_encode($result), true);
        
        return $result;
    }
    
    /////////////////////////////////////////////////////// modificada
    
    public function update_by_lots ($primaryKey, $fields) {
        
        $n_data = count($fields);
        for ($i = 0; $i < $n_data; $i++) {
            
            $fila = $fields[$i];
            /////////////////////////////////////////////////
            $this -> update_envios($primaryKey, $fila);
            
        }  
        
    }

    /////////////////////////////////////////////////////// modificada

    public function assign_packets ($fields) {
    
        $n_data = count($fields);
        for ($i = 0; $i < $n_data; $i++) {
            
            $selec = envios::where('id_ship','=',$fields[$i]['id_ship'])->first();
            $selec-> cadete3 = $selec-> cadete2;
            $selec-> time_cad3 = $selec-> time_cad2;
            $selec-> admin_cad3 = $selec-> admin_cad2;
            $selec-> cadete2 = $selec-> cadete1;
            $selec-> time_cad2 = $selec-> time_cad1;
            $selec-> admin_cad2 = $selec-> admin_cad1;
            $selec-> cadete1 = $fields[$i]['cadete1'];
            $selec-> time_cad1 = now();
            $selec-> admin_cad1 = $fields[$i]['admin_cad1'];
            /////////////////////////////////////////////////
            $selec -> save();
            
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
        //dd($shipping_res);
        
        $shipping = [];
        $shipping[0] = $shipnum;                   //'id_ship'
        $shipping[1] = new \DateTime();             //'date_in'
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
        //dd($ship_mat);
        return $ship_mat;
    }

    //////////////////////////////////////////////////////
    public function print_answer_TN ($user_id,$access_tok,$NOMBRE_CARRIER_TN,$CONTACT_APP_TN,$id_order) {
        $shipping = $this -> info_shipping_TN($user_id,$access_tok,$NOMBRE_CARRIER_TN,$CONTACT_APP_TN,$id_order);
    
        $hash_code = 'vacio';
    
        $matriz_QR = [];
        $matriz_QR['id']= $id_order;
        $matriz_QR['sender_id']= $user_id;   
        $matriz_QR['hash_code']= $hash_code;  
        $matriz_QR['security_digit']= 0;
    
        $sticker = json_encode($matriz_QR);
    
        //////////////////////////////////////
        $shipping_res = json_decode($shipping,true);
        //dd($shipping_res);
        
        $shipping = [];
        $shipping[0] = $id_order;                   //'id_ship'
        $shipping[1] = new \DateTime();             //'date_in'
        $shipping[2] = $shipping_res['shipping_status'];     //'status'
        $shipping[3] = $user_id;                    //'sender_id'
        $shipping[4] = $id_order;                   //'order_id'
        $shipping[5] = $sticker;                      //'Etiqueta'
        
        $address = [];
        $address[0] = $shipping_res['shipping_address']['name'];               //'street_name'
        $address[1] = $shipping_res['shipping_address']['number'];             //'street_number'
        $address[2] = $shipping_res['shipping_address']['customs'];            //'comment'
        $address[3] = $shipping_res['shipping_address']['zipcode'];            //'zip_code'
        $address[4] = $shipping_res['shipping_address']['city'];               //'city'
        $address[5] = $shipping_res['shipping_address']['province'];           //'state'
        $address[6] = $shipping_res['shipping_address']['country'];            //'country'
        $address[7] = null;                                                    //'latitude'
        $address[8] = null;                                                    //'longitude'
        $address[9] = null;    //'geolocation_last_updated'
        $address[10] = $shipping_res['shipping_address']['address'];        //'delivery_preference'
    
        $receiver_per = [];
        $receiver_per[0] = $shipping_res['customer']['name'];      //'receiver_name'
        $receiver_per[1] = $shipping_res['customer']['phone'];      //'receiver_phone'
        
        $shipping_items = [];
        $shipping_items[0] = $shipping_res['products'][0]['name'];          //'description'
        $shipping_items[1] = $shipping_res['products'][0]['weight'];            //'dimensions' Se sustituyó por el peso
        
        $delivery = [];
        $delivery[0] = null;     //'date_first_visit'
        $delivery[1] = null;       //'date_delivered'
        $delivery[2] = null;    //'date_not_delivered'
    
        $ship_mat = [$shipping, $address, $receiver_per, $shipping_items, $delivery];
        
        //////////////////////////////////////
        //dd($ship_mat);
        return $ship_mat;
    }
    
    //////////////////////////////////////////////////////
    public function basico ($shipnum,$ACCESS_TOK,$sender_id) {
        $shipping = $this -> info_shipping($shipnum,$ACCESS_TOK);
    
        //////////////////////////////////////
        $shipping_res = json_decode($shipping,true);
        
        $shipping = [];
        $shipping[0] = $shipnum;                   //'id_ship'
        $shipping[1] = new \DateTime();            //'date_in'
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

    
    //////////////////////////////////////////////////////////

    function findSeveral($table,$primaryKey,$id) {

        return DB::table($table)->where($primaryKey, '=', $id)->get()->toArray();
        
    }

    ///////////////////////////////////////////////////////////

    public function insert_pack ($field) {
        $envio = new envios();

        foreach ($field as $key => $value) {
            $envio->$key = $value;
        }

        $envio->save();
    }
}
