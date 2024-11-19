<?php

namespace App\Http\Controllers;

use App\Models\administ;
use App\Services\MELIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AddAdminController extends Controller
{
    protected $MELIService;

    public function __construct(MELIService $MELIService)
    {
        $this->MELIService = $MELIService;
    }

    public function formuAddAdmin() {
        $num_errores = 0;
        $errores = [];
        $title='Resgitro de administrador';

        return view('addAdmin',compact('title', 'num_errores', 'errores'));
    }

    public function someterAdmin() {
        $new_admin = request()->input();
        $new_admin = $new_admin['new_admin'];
        $num_errores = 0;
        
        if (empty($new_admin['email'])) {
            $num_errores += 1;
            $errores[] = 'El campo de email no puede quedar en blanco';
        }
        else {
            if (filter_var($new_admin['email'], FILTER_VALIDATE_EMAIL) == false) {
                $num_errores += 1;
                $errores[] = 'Dirección inválida de email';
            }
            // convert the email to lowercase
            $new_admin['email'] = strtolower($new_admin['email']);
            // Search for the lowercase version of $author['email']
            if (count(administ::where("email","=",$new_admin['email'])->get()->toArray() ) > 0) {
                $num_errores += 1;
                $errors[] = 'Este email ya ha sido registrado';
            }
        }
        
        if ($num_errores == 0) {
            $nuevo_admin = new administ();
            $nuevo_admin -> name = $new_admin['nombre'];
            $nuevo_admin -> email = $new_admin['email'];
            $nuevo_admin -> email_verified_at = now();
            $nuevo_admin -> password = Hash::make($new_admin['password']);
            $nuevo_admin -> remember_token = Str::random(10);
            $nuevo_admin -> save();
            //////////////////////////////////////
            $title='Registro de administrador';
        }

        return view('AddAdminSuccess',compact('title'));

    }

}