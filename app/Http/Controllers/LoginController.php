<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;


class LoginController extends Controller
{
    public function registerForm()
    {
        return view('register');
    }
    public function form()
    {
        return view('login');
    }
    public function register(Request $request)
    {
        $usuario = new Usuario();

        $usuario->correo = $request->input('correo');
        $usuario->password = Hash::make($request->input('password'));
        $usuario->nombres = $request->input('nombres');
        $usuario->apellidos = $request->input('apellidos');
        $usuario->rol = 'usuario';
        $usuario->telefono = $request->input('telefono');
        $usuario->direccion = $request->input('direccion');
        $usuario->estado = 'activo';
        $usuario->save();

        Auth::login($usuario);

        // Los nuevos usuarios siempre tienen rol 'usuario' y con estado 'activo', redirigir al dashboard de usuario
        return redirect('/usuario/catalogo')->with('success', 'Usted fue registrado exitosamente.');
    }
    public function login(Request $request)
    {
        $request->validate([
            'correo' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Buscar usuario manualmente
        $usuario = Usuario::where('correo', $request->correo)->first();

        if ($usuario) {
            // Si el usuario está bloqueado, mostrar mensaje específico
            if (isset($usuario->estado) && $usuario->estado === 'bloqueado') {
                return back()->withErrors([
                    'correo' => 'Usted está bloqueado. Por favor contacte al administrador.',
                ]);
            }

            if (Hash::check($request->password, $usuario->password)) {
                // Login manual exitoso
                Auth::login($usuario);
                $request->session()->regenerate();

                // Redirigir según el rol del usuario
                if ($usuario->rol === 'bibliotecario') {
                    return redirect('/dashboard'); // ->with('success', 'Bienvenido, ' . $usuario->nombres);
                } else {
                    return redirect('/usuario/catalogo'); // ->with('success', 'Bienvenido, ' . $usuario->nombres);
                }
            }
        }

        return back()->withErrors([
            'correo' => 'Sus credenciales son incorrectas, vuelva a intentar.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login')->with('success', 'Has cerrado sesión exitosamente.');
    }
}
