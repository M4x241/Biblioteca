<?php

namespace App\Http\Controllers;

use App\Models\Prestamo;
use App\Models\Reserva;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GestionLibrosController extends Controller
{
    public function index(Request $request)
    {
        $usuario = Auth::user();
        $q = $request->query('q', '');
        $page = max(1, (int) $request->query('page', 1));
        $perPage = 10;
        
        $prestamosQuery = Prestamo::with(['libro', 'usuario']);
        
        if ($q !== '') {
            $prestamosQuery->whereHas('libro', function ($query) use ($q) {
                $query->where('titulo', 'like', "%{$q}%")
                      ->orWhere('autor', 'like', "%{$q}%");
            })->orWhereHas('usuario', function ($query) use ($q) {
                $query->where('nombres', 'like', "%{$q}%")
                      ->orWhere('apellidos', 'like', "%{$q}%");
            });
        }
        
        $totalPrestamos = $prestamosQuery->count();
        $prestamos = $prestamosQuery->orderBy('created_at', 'desc')
                                    ->offset(($page - 1) * $perPage)
                                    ->limit($perPage)
                                    ->get();
        
        $reservasQuery = Reserva::with(['libro', 'usuario'])
                                ->whereIn('estado', ['activa', 'cancelada', 'vencida', 'completada']);
        
        if ($q !== '') {
            $reservasQuery->whereHas('libro', function ($query) use ($q) {
                $query->where('titulo', 'like', "%{$q}%")
                      ->orWhere('autor', 'like', "%{$q}%");
            })->orWhereHas('usuario', function ($query) use ($q) {
                $query->where('nombres', 'like', "%{$q}%")
                      ->orWhere('apellidos', 'like', "%{$q}%");
            });
        }
        
        $reservas = $reservasQuery->orderBy('created_at', 'desc')->get();
        
        $totalPages = ceil($totalPrestamos / $perPage);
        $hasPrev = $page > 1;
        $hasNext = $page < $totalPages;
        return view('dashboards.bibliotecario.gestion-libros', compact(
            'usuario',
            'prestamos',
            'reservas',
            'q',
            'page',
            'totalPages',
            'hasPrev',
            'hasNext'
        ));
    }
    
    public function convertirAPrestamo(Request $request, Reserva $reserva)
    {
        $request->validate([
            'fecha_devolucion' => 'required|date|after:today',
        ]);
        
        if ($reserva->estado !== 'activa') {
            return back()->with('error', 'Solo se pueden convertir reservas activas.');
        }
        
        if (Prestamo::hasActiveLoanForBook($reserva->libro_id)) {
            return back()->with('error', 'El libro ya no está disponible.');
        }
        
        try {
            // Crear el préstamo
            $prestamo = Prestamo::create([
                'usuario_id' => $reserva->usuario_id,
                'libro_id' => $reserva->libro_id,
                'fecha_prestamo' => now(),
                'reserva_id' => $reserva->id ?? null,
                'fecha_devolucion' => $request->fecha_devolucion,
                'estado' => 'ocupado',
            ]);
            
            // Marcar la reserva como completada (registro histórico)
            $reserva->markCompleted();
            return back()->with('success', 'Reserva convertida a préstamo exitosamente.');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Error al convertir la reserva: ' . $e->getMessage());
        }
    }
}