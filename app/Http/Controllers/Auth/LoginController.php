<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
 
class LoginController extends Controller
{
    public function ingreso () {
        
        //Si el usuario está autentificado 
        if (Auth::check()) {
            return redirect('/desktop');
        }

        return view('auth.login_admin');

    }

    /**
     * Handle an authentication attempt.
     * 
     */
    public function authenticate(Request $request): RedirectResponse
    {

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
 
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
 
            return redirect('/desktop');
            //return redirect()->intended('dashboard');
        }
 
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
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
    
        return redirect('/');
    }

    /**
     * Get the path the user should be redirected to.
     */
    protected function redirectTo(Request $request): string
    {
        return route('login');
    }
}
