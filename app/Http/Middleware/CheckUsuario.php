<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUsuario
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar si el usuario está autenticado
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Debes iniciar sesión.');
        }

        // Verificar si el usuario tiene rol de usuario normal y está activo
        if (Auth::user()->rol !== 'usuario' || Auth::user()->estado !== 'activo') {
            return redirect('/dashboard')->with('error', 'Error al iniciar sesión.');
        }

        return $next($request);
    }
}
