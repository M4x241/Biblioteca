<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Libro;
use App\Models\Prestamo;
use App\Models\Reserva;

class UsuarioDashboardController extends Controller
{
    /**
     * Muestra el catálogo de libros para el usuario
     */
    public function catalogo(Request $request)
    {
        $page = (int) $request->get('page', 1);
        $perPage = 8;

        $q = trim((string) $request->get('q', ''));
        $genero = trim((string) $request->get('genero', ''));

        $query = Libro::query();
        
        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('titulo', 'like', "%{$q}%")
                    ->orWhere('autor', 'like', "%{$q}%")
                    ->orWhere('categoria', 'like', "%{$q}%");
            });
        }

        if ($genero !== '') {
            $query->where('categoria', $genero);
        }

        $totalLibros = $query->count();
        $libros = $query->skip(($page - 1) * $perPage)->take($perPage)->get();
        
        $generos = Libro::select('categoria')
            ->distinct()
            ->whereNotNull('categoria')
            ->orderBy('categoria')
            ->pluck('categoria');

        $hasNext = ($page * $perPage) < $totalLibros;
        $hasPrev = $page > 1;
        $totalPages = ceil($totalLibros / $perPage);

        $usuario = Auth::user();

        // Obtener IDs de libros con reservas activas (activas o completadas)
        $librosReservados = Reserva::whereIn('estado', ['activa']) 
            ->pluck('libro_id')
            ->toArray();

        // Obtener IDs de libros con préstamos activos
        $librosPrestados = Prestamo::where('estado', Prestamo::STATUS_OCUPADO)
            ->pluck('libro_id')
            ->toArray();

        // Verificar si el usuario ha alcanzado el límite de 2 libros
        $reservasActivas = Reserva::where('usuario_id', $usuario->id)
            ->where('estado', 'activa')
            ->count();
        
        $prestamosActivos = Prestamo::where('usuario_id', $usuario->id)
            ->where('estado', Prestamo::STATUS_OCUPADO)
            ->count();
        
        $limiteAlcanzado = ($reservasActivas + $prestamosActivos) >= 2;



        

        return view('dashboards.usuario.catalogo', compact(
            'libros',
            'usuario',
            'hasNext',
            'hasPrev',
            'page',
            'totalPages',
            'librosReservados',
            'librosPrestados',
            'limiteAlcanzado',
            'q',
            'genero',
            'generos'
        ));


    }

    /**
     * Muestra los préstamos del usuario actual
     */
    public function misPrestamos()
    {
        $usuario = Auth::user();
        $prestamos = Prestamo::with('libro')
            ->where('usuario_id', $usuario->id)
            ->orderBy('fecha_prestamo', 'desc')
            ->get();

        return view('dashboards.usuario.mis-prestamos', compact('usuario', 'prestamos'));
    }

    /**
     * Muestra las reservas del usuario actual
     */
    public function misReservas()
    {
        $usuario = Auth::user();
        $reservas = Reserva::with('libro')
            ->where('usuario_id', $usuario->id)
            ->orderBy('fecha_reserva', 'desc')
            ->get();

        return view('dashboards.usuario.mis-reservas', compact('usuario', 'reservas'));
    }

    /**
     * Muestra el perfil del usuario con estadísticas
     */
    public function perfil()
    {
        $usuario = Auth::user();

        // Estadísticas del usuario
        $totalPrestamos = Prestamo::where('usuario_id', $usuario->id)->count();
        $prestamosActivos = Prestamo::where('usuario_id', $usuario->id)
            ->where('estado', 'ocupado')
            ->count();
        $prestamosDevueltos = Prestamo::where('usuario_id', $usuario->id)
            ->where('estado', 'disponible')
            ->count();

        return view('dashboards.usuario.perfil', compact(
            'usuario',
            'totalPrestamos',
            'prestamosActivos',
            'prestamosDevueltos'
        ));
    }
}
