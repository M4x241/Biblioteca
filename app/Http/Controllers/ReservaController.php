<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Reserva;
use App\Models\Prestamo;
use App\Models\Libro;
use Carbon\Carbon;

use function Ramsey\Uuid\v1;

class ReservaController extends Controller
{
    public function index(Request $request)
    {
        // Search query
        $q = trim((string) $request->get('q', ''));

        // paginar para no traer todo en memoria
        $query = Reserva::with(['usuario', 'libro'])->orderBy('fecha_reserva', 'desc');

        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('estado', 'like', "%{$q}%")
                    ->orWhereHas('libro', function ($qb) use ($q) {
                        $qb->where('titulo', 'like', "%{$q}%")
                           ->orWhere('autor', 'like', "%{$q}%");
                    })
                    ->orWhereHas('usuario', function ($qb) use ($q) {
                        $qb->where('nombres', 'like', "%{$q}%")
                           ->orWhere('apellidos', 'like', "%{$q}%")
                           ->orWhere('correo', 'like', "%{$q}%");
                    });
            });
        }

        $page = (int) $request->get('page', 1);
        $perPage = 9;

        $total = $query->count();
        $reservas = $query->skip(($page - 1) * $perPage)->take($perPage)->get();

        $hasNext = ($page * $perPage) < $total;
        $hasPrev = $page > 1;
        $totalPages = (int) ceil($total / $perPage);

        // Obtener el usuario autenticado
        $usuario = Auth::user();

        // Calcular estadísticas de reservas por estado
        $stats = [
            'activa' => Reserva::where('estado', Reserva::STATUS_CONFIRMED)->count(),
            'cancelada' => Reserva::where('estado', Reserva::STATUS_CANCELLED)->count(),
            'vencida' => Reserva::where('estado', Reserva::STATUS_EXPIRED)->count(),
            'completada' => Reserva::where('estado', Reserva::STATUS_COMPLETED)->count(),
        ];

        // devolver la vista del dashboard de bibliotecario si existe
        if (view()->exists('dashboards.bibliotecario.reservas')) {
            return view('dashboards.bibliotecario.reservas', compact(
                'reservas',
                'stats',
                'q',
                'page',
                'hasNext',
                'hasPrev',
                'totalPages',
                'usuario'
            ));
        }

        return view('reservas.index', compact(
            'reservas',
            'stats',
            'q',
            'page',
            'hasNext',
            'hasPrev',
            'totalPages',
            'usuario'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'usuario_id' => 'required|integer|exists:usuarios,id',
            'libro_id' => 'required|integer|exists:libros,id',
        ]);

        $userId = $data['usuario_id'];
        $libroId = $data['libro_id'];

        return DB::transaction(function () use ($userId, $libroId) {
            Libro::where('id', $libroId)->lockForUpdate()->first();

            // Verificar límite de 2 libros activos (reservas activas + préstamos ocupados)
            $reservasActivas = Reserva::where('usuario_id', $userId)
                ->where('estado', 'activa')
                ->count();
            
            $prestamosActivos = Prestamo::where('usuario_id', $userId)
                ->where('estado', Prestamo::STATUS_OCUPADO)
                ->count();
            
            $totalActivos = $reservasActivas + $prestamosActivos;
            
            if ($totalActivos >= 2) {
                return back()->with('error', 'Has alcanzado el límite de 2 libros. Debes devolver un libro antes de reservar otro.');
            }

            if (Prestamo::hasActiveLoanForBook($libroId)) {
                return back()->with('error', 'El libro ya está prestado');
            }

            $exists = Reserva::where('usuario_id', $userId)
                ->where('libro_id', $libroId)
                ->whereIn('estado', ['activa'])
                ->lockForUpdate()
                ->exists();

            if ($exists) {
                return back()->with('error', 'Ya tienes una reserva activa para este libro');
            }

            $reserva = Reserva::create([
                'usuario_id' => $userId,
                'libro_id' => $libroId,
                'fecha_reserva' => now(),
                'fecha_vencimiento' => now()->addHours(48),
                'estado' => 'activa',
            ]);

            return back()->with('success', 'Reserva creada correctamente. Tienes 48h para recoger el libro.');
        });
    }

    public function confirmar($id)
    {
        return DB::transaction(function () use ($id) {
            $reserva = Reserva::with(['usuario', 'libro'])->lockForUpdate()->findOrFail($id);

            // Verificar que la reserva esté activa
            if ($reserva->estado !== 'activa') {
                return redirect()->route('dashboard.reservas')
                    ->with('error', 'La reserva no está en estado activa.');
            }

            // Verificar que el libro no esté ocupado
            if (Prestamo::hasActiveLoanForBook($reserva->libro_id)) {
                return redirect()->route('dashboard.reservas')
                    ->with('error', 'El libro ya tiene un préstamo activo.');
            }

            // Cambiar estado de reserva a completada
            $reserva->estado = 'completada';
            $reserva->save();

            // Crear préstamo automáticamente (por defecto 14 días)
            Prestamo::create([
                'usuario_id' => $reserva->usuario_id,
                'libro_id' => $reserva->libro_id,
                'fecha_prestamo' => now(),
                'fecha_devolucion' => now()->addDays(14), // 14 días por defecto
                'estado' => Prestamo::STATUS_OCUPADO,
            ]);

            return redirect()->route('dashboard.reservas')
                ->with('success', 'Reserva confirmada y préstamo creado exitosamente.');
        });
    }

    /**
     * Cancelar una reserva por parte del usuario
     */
    public function cancelar($id)
    {
        $reserva = Reserva::findOrFail($id);
        $usuario = Auth::user();

        // Verificar que la reserva pertenece al usuario autenticado
        if ($reserva->usuario_id !== $usuario->id) {
            return back()->with('error', 'No tienes permiso para cancelar esta reserva.');
        }

        // Solo se pueden cancelar reservas activas
        if ($reserva->estado !== 'activa') {
            return back()->with('error', 'Solo puedes cancelar reservas activas.');
        }

        // Cambiar estado a cancelada
        $reserva->estado = 'cancelada';
        $reserva->save();

        return back()->with('success', 'Reserva cancelada exitosamente.');
    }

    public function confirm(Reserva $reserva)
    {
        return DB::transaction(function () use ($reserva) {

            Reserva::where('id', $reserva->id)->lockForUpdate()->first();

            // if (!$reserva->isPending()) {
            //     return response()->json(['error' => 'La reserva no está pendiente'], 409);
            // }

            if (Prestamo::hasActiveLoanForBook($reserva->ID_Libro)) {
                return response()->json(['error' => 'No se puede confirmar: libro con préstamo activo'], 409);
            }

            // Crear préstamo
            $prestamo = Prestamo::create([
                'usuario_id' => $reserva->usuario_id,
                'libro_id' => $reserva->libro_id,
                'reserva_id' => $reserva->id,
                'fecha_prestamo' => now(),
                'estado' => Prestamo::STATUS_OCUPADO,
            ]);

            $reserva->markConfirmed();

            return response()->json($prestamo, 201);
        });
    }

    // public function cancel(Reserva $reserva, Request $request)
    // {
    //     // // comprobar permisos del usuario según tu lógica (omito auth)
    //     // if (!$reserva->isPending()) {
    //     //     return response()->json(['error' => 'Sólo reservas pendientes pueden cancelarse'], 409);
    //     // }
    //     $reserva->markCancelled();
    //     return response()->json(['success' => true], 200);
    // }

    public function updateEstado(Reserva $reserva, Request $request)
    {
        $data = $request->validate([
            'estado' => 'required|in:activa,cancelada,vencida,completada'
        ]);

        $nuevoEstado = $data['estado'];

        return DB::transaction(function () use ($reserva, $nuevoEstado) {
            Reserva::where('id', $reserva->id)->lockForUpdate()->first();

            // Si se cambia a confirmada, verificar que el libro no esté prestado
            if ($nuevoEstado === Reserva::STATUS_CONFIRMED && $reserva->estado !== Reserva::STATUS_CONFIRMED) {
                if (Prestamo::hasActiveLoanForBook($reserva->libro_id)) {
                    if (request()->wantsJson()) {
                        return response()->json(['error' => 'No se puede confirmar: libro con préstamo activo'], 409);
                    }
                    return back()->withErrors(['error' => 'No se puede confirmar: libro con préstamo activo']);
                }

                // Crear préstamo automáticamente
                Prestamo::create([
                    'usuario_id' => $reserva->usuario_id,
                    'libro_id' => $reserva->libro_id,
                    'reserva_id' => $reserva->id,
                    'fecha_prestamo' => now(),
                    'estado' => Prestamo::STATUS_OCUPADO,
                ]);
            }

            // Actualizar el estado
            $reserva->estado = $nuevoEstado;
            $reserva->save();

            // Calcular estadísticas actualizadas
            $stats = [
                'activa' => Reserva::where('estado', Reserva::STATUS_CONFIRMED)->count(),
                'cancelada' => Reserva::where('estado', Reserva::STATUS_CANCELLED)->count(),
                'vencida' => Reserva::where('estado', Reserva::STATUS_EXPIRED)->count(),
                'completada' => Reserva::where('estado', Reserva::STATUS_COMPLETED)->count(),
            ];

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'reserva' => $reserva,
                    'stats' => $stats
                ], 200);
            }

            return back()->with('status', 'Estado de reserva actualizado correctamente.');
        });
    }
}
