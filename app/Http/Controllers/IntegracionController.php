<?php

namespace App\Http\Controllers;

use App\Models\access_meli;
use App\Models\access_nube;
use App\Models\clientes;
use Illuminate\Http\Request;
use App\Services\MELIService;
use Throwable;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;

class IntegracionController extends Controller
{
    protected $MELIService;

    public function __construct(MELIService $MELIService)
    {
        $this->MELIService = $MELIService;
    }

    public function integracion () {

        return view('integracion');

    }

    public function integrarMELI () {
        $bina = openssl_random_pseudo_bytes ( 4, $crypto_strong);
        $randSecu = bin2hex($bina);

        $code = request()->input('code');

        if ($code) {
            $state = request()->input('state');
            $datos = $this -> MELIService -> request_tok($code,$state,env('APP_ID'),env('SECRET_KEY'),env('URL'));
            //dd($datos);

            if (auth('clientes')->check()) {        // Pasos para clientes ya registrados con Tienda Nube que están agregando a MELI

                $dat_user = auth('clientes')->user();

                //dd($dat_user);

                $usuario_meli = new access_meli();
                $usuario_meli-> id = $dat_user["id"];
                $usuario_meli-> user_id = $datos["user_id"];
                $usuario_meli-> access_tok = $datos["access_token"];
                $usuario_meli-> refresh_tok = $datos["refresh_token"];
                $usuario_meli-> fec_hora = Carbon::now();
                $usuario_meli-> Nombre = $dat_user["name"];

                $usuario_meli->save();

                $cliente = clientes::where('id','=',$dat_user["id"])->get()->first();

                $cliente-> id_MELI = $datos["user_id"];
                $cliente->save();

                $mensaje = 'Se actualizó usuario existente';
                $title='Actualización de usuario';

                return view('integracion_success',compact('title','mensaje','dat_user'));

            } else {        // Pasos para clientes que se estén registrando por primera vez
                
                $registro = access_meli::where('user_id','=',$datos["user_id"])->get();

                $title='Registro de usuario';

                return view('curl',compact('registro','title','datos'));
            }
            

        } else {

            //header('location: https://auth.mercadolibre.com.ar/authorization?response_type=code&client_id='.$APP_ID.'&state='.$randSecu.'&redirect_uri='.$URL);
            return redirect('https://auth.mercadolibre.com.ar/authorization?response_type=code&client_id='.env('APP_ID').'&state='.$randSecu.'&redirect_uri='.env('URL'));
            die();
            
        }
    }

    public function IntegrarMELI_respu () {
        
        $bina = openssl_random_pseudo_bytes ( 4, $crypto_strong);
        $randSecu = bin2hex($bina);

        $new_user = request()->input('new_user');
        $num_errores = 0;

        if (empty($new_user['email'])) {
            $num_errores += 1;
            $errores[] = 'El campo de email no puede quedar en blanco';
        }
        else {
            if (filter_var($new_user['email'], FILTER_VALIDATE_EMAIL) == false) {
                $num_errores += 1;
                $errores[] = 'Dirección inválida de email';
            }
            // convert the email to lowercase
            $new_user['email'] = strtolower($new_user['email']);
            // Search for the lowercase version of $author['email']
            if (count( $this -> MELIService ->findSeveral('clientes','id',$new_user['user_id'])) === 0) {
                if (count( $this -> MELIService ->findSeveral('clientes','email',$new_user['email'])) > 0) {
                    $num_errores += 1;
                    $errores[] = 'Este email ya ha sido registrado';
                }
            } 
        }
        
        if ($num_errores == 0) {

            $new_user['password'] = password_hash($new_user['password'], PASSWORD_DEFAULT);

            $usuario = new clientes();
            $usuario->name = $new_user["nombre"];
            $usuario->email = $new_user["email"];
            $usuario->password = $new_user["password"];
            $usuario->remember_token = Str::random(60);
            $usuario->created_at = new \DateTime();
            $usuario->updated_at = new \DateTime();
            $usuario->id_MELI = $new_user["user_id"];

            $usuario->save();

            $act_usuario = clientes::where('id','=',$usuario["id"])->get()->first();
            $act_usuario->id_Gitt = 'G'. $usuario["id"];
            $act_usuario->save();

            $existe = access_meli::where('user_id','=',$new_user["user_id"])->get()->toArray();

            if (count($existe) == 0) { //Condicional para saber si es un usuario nuevo o una actualización de access token
                $mensaje = 'Se registró un nuevo usuario';
                $title='Registro de nuevo usuario';
            } else {
                $mensaje = 'Se actualizó usuario existente';
                $title='Actualización de usuario';
            }

            $Nombre_meli = $new_user["nombre"]; 

            $usuario_meli = new access_meli();
            $usuario_meli-> id = $usuario["id"];
            $usuario_meli-> user_id = $new_user["user_id"];
            $usuario_meli-> access_tok = $new_user["access_tok"];
            $usuario_meli-> refresh_tok = $new_user["refresh_tok"];
            $usuario_meli-> fec_hora = Carbon::now();
            $usuario_meli-> Nombre = $Nombre_meli;

            $usuario_meli->save();

            if (isset($errores)) {
                return view('curl',compact('title','errores','mensaje','num_errores'));
            }

            return view('integracion_success',compact('title','mensaje','num_errores'));
            
        } else {
            //header('location: https://auth.mercadolibre.com.ar/authorization?response_type=code&client_id='.$APP_ID.'&state='.$randSecu.'&redirect_uri='.$URL);
            return redirect('https://auth.mercadolibre.com.ar/authorization?response_type=code&client_id='.env('APP_ID').'&state='.$randSecu.'&redirect_uri='.env('URL'));
            die();
        }
    }

    public function otorgarPermiso (Request $request) {
        
        $title = "Otorgar permisos";

        return view('grant_permission',compact('title'));

    }

    public function integrarTiendaNube () {
        $code = request()->input('code');

        if ($code) {
            $CLIENT_ID_TN = env('CLIENT_ID_TN');
            $client_secret = env('SECRET_KEY_TN');
            $URL_TN = env('URL_TN');
            
            $datos = $this -> MELIService -> integracion_Tiendanube($code,$CLIENT_ID_TN,$client_secret,$URL_TN);
            $datos = json_decode($datos, true);
            //var_dump($datos);

            if (auth('clientes')->check()) {        // Pasos para clientes ya registrados con MELI que están agregando a Tienda Nube

                $dat_user = auth('clientes')->user();

                $usuario_nube = new access_nube();
                $usuario_nube-> id = $dat_user["id"];
                $usuario_nube-> user_id = $datos["user_id"];
                $usuario_nube-> access_tok = $datos['access_token'];
                $usuario_nube-> fec_hora = new \DateTime();
                $usuario_nube-> fec_hora =$usuario_nube-> fec_hora->format('Y-m-d H:i:s');
                //$usuario_nube-> fec_hora = Carbon::now();
                $usuario_nube-> Nombre = $dat_user["name"];
                $usuario_nube-> alcance = $datos['scope'];

                $usuario_nube->save();

                $cliente = clientes::where('id','=',$dat_user["id"])->get()->first();

                $cliente-> id_TN = $datos["user_id"];
                $cliente->save();

                $mensaje = 'Se actualizó usuario existente';
                $title='Actualización de usuario';

                return view('integracion_success',compact('title','mensaje','dat_user'));

            } else {        // Pasos para clientes que se estén registrando por primera vez
                
                $registro = access_nube::where('user_id','=',$datos["user_id"])->get();

                $title='Registro de usuario';

                return view('curl_TN',compact('registro','title','datos'));
            }

        } else {
            return redirect(route('integrar_NUBE'));
            die();
            
        }
    }

    public function IntegrarNube_respu () {

        $new_user = request()->input('new_user');
        $num_errores = 0;

        if (empty($new_user['email'])) {
            $num_errores += 1;
            $errores[] = 'El campo de email no puede quedar en blanco';
        }
        else {
            if (filter_var($new_user['email'], FILTER_VALIDATE_EMAIL) == false) {
                $num_errores += 1;
                $errores[] = 'Dirección inválida de email';
            }
            // convert the email to lowercase
            $new_user['email'] = strtolower($new_user['email']);
            // Search for the lowercase version of $author['email']
            if (count( $this -> MELIService ->findSeveral('clientes','id',$new_user['user_id'])) === 0) {
                if (count( $this -> MELIService ->findSeveral('clientes','email',$new_user['email'])) > 0) {
                    $num_errores += 1;
                    $errores[] = 'Este email ya ha sido registrado';
                }
            } 
        }
        
        if ($num_errores == 0) {

            $new_user['password'] = password_hash($new_user['password'], PASSWORD_DEFAULT);

            $usuario = new clientes();
            $usuario->name = $new_user["nombre"];
            $usuario->email = $new_user["email"];
            $usuario->password = $new_user["password"];
            $usuario->remember_token = Str::random(60);
            $usuario->created_at = new \DateTime();
            $usuario->created_at = $usuario->created_at->format('Y-m-d H:i:s');
            $usuario->updated_at = new \DateTime();
            $usuario->updated_at = $usuario->updated_at->format('Y-m-d H:i:s');
            $usuario->id_TN = $new_user["user_id"];

            $usuario->save();

            $act_usuario = clientes::where('id','=',$usuario["id"])->get()->first();
            $act_usuario->id_Gitt = 'G'. $usuario["id"];
            $act_usuario->save();

            $existe = access_nube::where('user_id','=',$new_user["user_id"])->get()->toArray();

            if (count($existe) == 0) { //Condicional para saber si es un usuario nuevo o una actualización de access token
                $mensaje = 'Se registró un nuevo usuario';
                $title='Registro de nuevo usuario';
            } else {
                $mensaje = 'Se actualizó usuario existente';
                $title='Actualización de usuario';
            }

            $Nombre_nube = $new_user["nombre"]; 
            //var_dump($new_user);

            $usuario_nube = new access_nube();
            $usuario_nube-> id = $usuario["id"];
            $usuario_nube-> user_id = $new_user["user_id"];
            $usuario_nube-> access_tok = $new_user["access_tok"];
            $usuario_nube-> fec_hora = new \DateTime();
            $usuario_nube-> fec_hora =$usuario_nube-> fec_hora->format('Y-m-d H:i:s');
            //$usuario_nube-> fec_hora = Carbon::now();
            $usuario_nube-> Nombre = $Nombre_nube;
            $usuario_nube-> alcance = $new_user["alcance"];

            $usuario_nube->save();
            //dd($usuario_nube);

            $NOMBRE_CARRIER_TN = env('NOMBRE_CARRIER_TN');
            $WEBHOOK_PRECIOS = env('WEBHOOK_PRECIOS');
            $CONTACT_APP_TN = env('CONTACT_APP_TN');

            $respu_crear_carrier = $this -> MELIService ->Crear_carrier_TN($usuario_nube-> user_id,$usuario_nube-> access_tok,$NOMBRE_CARRIER_TN,$WEBHOOK_PRECIOS,$CONTACT_APP_TN);
            $respu_crear_carrier = json_decode($respu_crear_carrier,true);
            
            if (!isset($respu_crear_carrier['name'])) {         //Se ejecuta si ocurre un error al crear el carrier
                var_dump("Ha ocurrido un problema");
                dd($respu_crear_carrier);
            }

            $id_carrier = $respu_crear_carrier['id'];
            //dd($respu_crear_carrier);
            $respu_opc_carrier = $this -> MELIService ->Crear_carrier_opt_TN($usuario_nube-> user_id,$usuario_nube-> access_tok,$NOMBRE_CARRIER_TN,$CONTACT_APP_TN,$id_carrier);
            $respu_opc_carrier = json_decode($respu_opc_carrier,true);

            if (!isset($respu_opc_carrier['name'])) {         //Se ejecuta si ocurre un error al crear la opción del carrier
                var_dump("Ha ocurrido un problema");
                dd($respu_opc_carrier);
            }
            //dd($respu_opc_carrier);

            if (isset($errores)) {
                return view('integracion_success',compact('title','errores','mensaje','num_errores'));
            }

            return view('integracion_success',compact('title','mensaje','num_errores'));
            
        }
    }

    public function descarga_manual() {
        $filePath = storage_path('app/public/Instalacion_app_Tienda_nube.pdf');
        return Response::download($filePath, 'Instalacion_app_Tienda_nube.pdf');
    } 

    /* public function hatty () {

        $usuario_nube = access_nube::where('user_id','=',5372388)->get()->first();
        
        if (0 == 0) {

            $usuario_nube = access_nube::where('user_id','=',5372388)->get()->first();
            //dd($usuario_nube);

            $NOMBRE_CARRIER_TN = env('NOMBRE_CARRIER_TN');
            $WEBHOOK_PRECIOS = env('WEBHOOK_PRECIOS');
            $CONTACT_APP_TN = env('CONTACT_APP_TN');

            $respu_crear_carrier = $this -> MELIService ->Crear_carrier_TN($usuario_nube-> user_id,$usuario_nube-> access_tok,$NOMBRE_CARRIER_TN,$WEBHOOK_PRECIOS,$CONTACT_APP_TN);
            $respu_crear_carrier = json_decode($respu_crear_carrier,true);
            
            if (!isset($respu_crear_carrier['name'])) {         //Se ejecuta si ocurre un error al crear el carrier
                var_dump("Ha ocurrido un problema");
                dd($respu_crear_carrier);
            }

            $id_carrier = $respu_crear_carrier['id'];
            //dd($respu_crear_carrier);
            $respu_opc_carrier = $this -> MELIService ->Crear_carrier_opt_TN($usuario_nube-> user_id,$usuario_nube-> access_tok,$NOMBRE_CARRIER_TN,$CONTACT_APP_TN,$id_carrier);
            $respu_opc_carrier = json_decode($respu_opc_carrier,true);

            if (!isset($respu_opc_carrier['name'])) {         //Se ejecuta si ocurre un error al crear la opción del carrier
                var_dump("Ha ocurrido un problema");
                dd($respu_opc_carrier);
            }
            //dd($respu_opc_carrier);
            $title='Hatty';
            $mensaje='Registrado Hatty';
            $num_errores=0;

            if (isset($errores)) {
                return view('integracion_success',compact('title','errores','mensaje','num_errores'));
            }

            return view('integracion_success',compact('title','mensaje','num_errores'));
            
        }
    } */

}
