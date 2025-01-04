<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\administ;
use App\Models\cadetes;
use App\Models\clientes;

class LoginController extends Controller
{

    public function ingreso (Request $request) {
        //Guarda en la variable perfil el tipo de usuario que debe tener para la ruta solicitada
        $perfil = $this->perfil($request);
        
        //Si el usuario está autentificado 
        if (Auth::guard($perfil)->check()) {
            
            return $this->redirect_authenticated($perfil);

        }

        return view('auth.login');

    }

    /**
     * Handle an authentication attempt.
     * 
     */
    public function authenticate(Request $request): RedirectResponse
    {
        //Guarda en la variable perfil el tipo de usuario que debe tener para la ruta solicitada
        $perfil = $this->perfil($request);

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
 
        if (Auth::guard($perfil)->attempt($credentials)) {
            $request->session()->regenerate();

            return $this->redirect_authenticated($perfil);
        }
 
        return back()->withErrors([
            'email' => 'Las credenciales ingresadas no coinciden con nuestro registro',
        ])->onlyInput('email');
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
    
        $request->session()->invalidate();
    
        $request->session()->regenerateToken();
    
        return redirect() -> route('login');
    }

    /**
     * Get the path the user should be redirected to.
     * Redireccionamiento cuando el usuario ingresa la ruta de manera manual
     */
    protected function redirectTo(Request $request): string
    {
        return route('login');
    }

    protected function perfil(Request $request): string {
        //Guarda en la variable perfil el tipo de usuario que debe tener para la ruta solicitada
        if ($request->is('admin*')) {
            return 'administ';
        } elseif ($request->is('cadete*')) {
            return 'cadete';
        } else {
            return 'clientes';
        }
    }

    protected function redirect_authenticated($perfil)
    {
        //Guarda en la variable perfil el tipo de usuario que debe tener para la ruta solicitada
        switch ($perfil) {
            case 'administ':
                /* dd($perfil . " bandera 1"); */
                /* return redirect()->route('desk_admin'); */
                return redirect()->route('mostrarenvios');
                break;
            case 'cadete':
                return redirect()->route('desk_cadete');
                break;
            case 'clientes':
                return redirect()->route('mostrarenvios_cientes');
                break;
        }
    }
}
