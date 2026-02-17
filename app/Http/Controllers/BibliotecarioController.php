<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Libro;
use App\Models\Usuario;
use App\Models\Prestamo;
use App\Models\Reserva;

class BibliotecarioController extends Controller
{
    /**
     * Muestra el dashboard principal con grid de libros paginado
     */
    public function index(Request $request)
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

        // Obtener IDs de libros con reservas activas (pendientes o confirmadas)
                $librosReservados = Reserva::whereIn('estado', [ Reserva::STATUS_CONFIRMED])
            ->pluck('libro_id')
            ->toArray();

        // Obtener IDs de libros con préstamos activos
        $librosPrestados = Prestamo::where('estado', Prestamo::STATUS_OCUPADO)
            ->pluck('libro_id')
            ->toArray();

        return view('dashboards.bibliotecario.index', compact(
            'libros',
            'usuario',
            'hasNext',
            'hasPrev',
            'page',
            'totalPages',
            'librosReservados',
            'librosPrestados',
            'q',
            'genero',
            'generos'
        ));
    }

    /**
     * Muestra el dashboard de gestión de usuarios
     */
    public function usuarios()
    {
        // Comentado: paginación de usuarios (page / perPage / skip / take / hasNext / hasPrev / totalPages)
        $page = (int) request()->get('page', 1);
        $perPage = 10;

        $q = trim((string) request()->get('q', ''));

        $query = Usuario::query();
        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('nombres', 'like', "%{$q}%")
                    ->orWhere('apellidos', 'like', "%{$q}%")
                    ->orWhere('correo', 'like', "%{$q}%")
                    ->orWhere('telefono', 'like', "%{$q}%");
            });
        }

        $total = $query->count();
        $usuarios = $query->skip(($page - 1) * $perPage)->take($perPage)->get();

        $hasNext = ($page * $perPage) < $total;
        $hasPrev = $page > 1;
        $totalPages = ceil($total / $perPage);

        // En lugar de paginar, obtener todos los usuarios filtrados para evitar referencias a variables de paginación
        $usuarios = $query->get();

        $usuario = Auth::user();

        // No se pasan variables de paginación al view ya que la paginación está comentada
        return view('dashboards.bibliotecario.usuarios', compact('usuarios', 'usuario', 'q', 'page', 'perPage', 'hasNext', 'hasPrev', 'totalPages'));
    }

    /**
     * Muestra el dashboard de gestión de préstamos
     */
    public function prestamos()
    {
        $page = (int) request()->get('page', 1);
        $perPage = 10;

        $q = trim((string) request()->get('q', ''));

        $query = Prestamo::with(['usuario', 'libro'])->orderBy('fecha_prestamo', 'desc');

        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('estado', 'like', "%{$q}%")
                    ->orWhereHas('libro', function ($qb) use ($q) {
                        $qb->where('titulo', 'like', "%{$q}%")
                           ->orWhere('autor', 'like', "%{$q}%");
                    })
                    ->orWhereHas('usuario', function ($qb) use ($q) {
                        $qb->where('nombres', 'like', "%{$q}%")
                           ->orWhere('apellidos', 'like', "%{$q}%");
                    });
            });
        }
        $total = $query->count();
        $prestamos = $query->skip(($page - 1) * $perPage)->take($perPage)->get();

        $hasNext = ($page * $perPage) < $total;
        $hasPrev = $page > 1;
        $totalPages = ceil($total / $perPage);

        $usuario = Auth::user();

        return view('dashboards.bibliotecario.prestamos', compact('prestamos', 'usuario', 'q', 'page', 'perPage', 'hasNext', 'hasPrev', 'totalPages'));
    }

    /**
     * Muestra el dashboard con todos los libros del sistema
     */
    public function libros()
    {
        $page = (int) request()->get('page', 1);
        $perPage = 8;

        $q = trim((string) request()->get('q', ''));
        $genero = trim((string) request()->get('genero', ''));

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

        return view('dashboards.bibliotecario.libros', compact('libros', 'usuario', 'page', 'perPage', 'hasNext', 'hasPrev', 'totalPages', 'q', 'genero', 'generos'));
    }

}
