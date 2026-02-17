<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckBibliotecario
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

        // Verificar si el usuario tiene rol de bibliotecario
        if (Auth::user()->rol !== 'bibliotecario') {
            return redirect('/usuario/catalogo')->with('error', 'No tienes permisos para acceder a esta sección.');
        }

        return $next($request);
    }
}
