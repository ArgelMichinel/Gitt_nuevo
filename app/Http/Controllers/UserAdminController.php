<?php

namespace App\Http\Controllers;

use App\Models\administ;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\MELIService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserAdminController extends Controller
{
    protected $MELIService;

    public function __construct(MELIService $MELIService)
    {
        $this->MELIService = $MELIService;
    }

    public function mostrarAdmin() {

        if (!$this->check_admin_master()) {
            return redirect()->route('mostrarenvios')->with('error', 'No tienes permisos para acceder a esta sección.');
        }

        $administra = administ::all('id','name', 'email')->toArray();

        $title='Usuarios Administradores';

        return view('userAdmin',compact('title', 'administra'));
    }

    public function borrarAdmin() {

        if (!$this->check_admin_master()) {
            return redirect()->route('mostrarenvios')->with('error', 'No tienes permisos para acceder a esta sección.');
        }

        $id_admin = request()->input('del_admin');

        $cadete_eliminado = administ::where('email','=',$id_admin);
        $cadete_eliminado->delete();
    
        //////////////////////////////////////
        $title='Administrador Eliminado';
        return view('deleted_admin', compact('title'));
    }

    public function formuAddAdmin() {

        if (!$this->check_admin_master()) {
            return redirect()->route('mostrarenvios')->with('error', 'No tienes permisos para acceder a esta sección.');
        }

        $request = request()->input('id');

        if (isset($request)) {
            $datos = administ::where('id', $request)->first();

            $title='Editar datos del administrador';

        } else {
            $datos = null;
            $title='Resgitro de administrador';
        }
    
        $num_errores = 0;
        $errores = [];

        return view('addAdmin',compact('title', 'num_errores', 'errores','datos'));
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
            if ((!isset($new_admin['id']) || $new_admin['id'] == '') && (count(administ::where("email","=",$new_admin['email'])->get()->toArray() ) > 0)) {  //Si no se está editando un usuario existente, se verifica que el email no exista en la base de datos
                $num_errores += 1;
                $errors[] = 'Este email ya ha sido registrado';
            }
        }
        
        if ($num_errores == 0) {
            if (!isset($new_admin['master'])) {
                $new_admin['master'] = 0;
            } else {
                if ($new_admin['master'] == 'on') {
                    $new_admin['master'] = 1;
                } else {
                    $new_admin['master'] = 0;
                }
            }
            

            if (isset($new_admin['id']) && $new_admin['id'] != '') {
                $admin_existente = administ::where('id', $new_admin['id'])->first();
                $admin_existente->name = $new_admin['nombre'];
                $admin_existente->email = $new_admin['email'];
                if (!empty($new_admin['password'])) {
                    $admin_existente->password = Hash::make($new_admin['password']);
                }
                $admin_existente->master = $new_admin['master'];
                $admin_existente->save();

                $title='Edición de administrador';
            } else {

                $nuevo_admin = new administ();
                $nuevo_admin -> name = $new_admin['nombre'];
                $nuevo_admin -> email = $new_admin['email'];
                $nuevo_admin -> email_verified_at = now();
                $nuevo_admin -> password = Hash::make($new_admin['password']);
                $nuevo_admin -> remember_token = Str::random(10);
                $nuevo_admin -> master = $new_admin['master'];
                $nuevo_admin -> save();
                //////////////////////////////////////
                $title='Registro de administrador';
            }

        return view('AddAdminSuccess',compact('title'));

        }
    }

    protected function check_admin_master(): bool
    {
        $dat_user = Auth::user();
        if ($dat_user && $dat_user->master === 1) {
            return true;
        }
        return false;
    }
}
