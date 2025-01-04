<?php

namespace App\Http\Controllers;

use App\Models\access_meli;
use App\Models\clientes;
use Illuminate\Http\Request;
use App\Services\MELIService;
use Throwable;
use Carbon\Carbon;
use Illuminate\Support\Str;

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

            $registro = access_meli::where('user_id','=',$datos["user_id"])->get();
            
            $title='Registro de usuario';

            return view('curl',compact($registro,$title));

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
        $Nombre_meli = $this -> MELIService -> info_user($new_user['user_id']);

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

            $usuario = new clientes([
                'name' => $new_user["nombre"],
                'email' => $new_user["email"],
                'password' => $new_user["password"],
                'remember_token' => Str::random(60),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);

            $usuario->save();

            $existe = access_meli::where('user_id','=',$new_user["user_id"])->get()->toArray();

            if (count($existe) == 0) { //Condicional para saber si es un usuario nuevo o una actualización de access token
                $mensaje = 'Se registró un nuevo usuario';
                $title='Registro de nuevo usuario';
            } else {
                $mensaje = 'Se actualizó usuario existente';
                $title='Actualización de usuario';
            }

            $usuario_meli = new access_meli([
                'id' => $usuario->id,
                'user_id' => $new_user["user_id"],
                'access_tok' => $new_user["access_tok"],
                'refresh_tok' => $new_user["refresh_tok"],
                'fec_hora' => Carbon::now(),
                'Nombre' => $Nombre_meli
            ]);

            $usuario_meli->save();

            return view('integracion_success',compact($title,$errores,$mensaje));
            
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
}
