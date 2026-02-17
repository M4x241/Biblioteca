<?php

namespace App\Http\Controllers;

use App\Models\Prestamo;
use App\Models\Libro;
use App\Models\Usuario;
use Illuminate\Http\Request;

class PrestamoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));

        $query = Prestamo::with(['libro', 'usuario'])->orderBy('fecha_prestamo', 'desc');

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

        $prestamos = $query->get();
        return view('dashboard.gestion-libros', compact('prestamos', 'q'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $prestamos = Prestamo::all();
        $libros = Libro::all();
        // Traer sólo usuarios con rol 'usuario' y estado 'activo' para seleccionar quien presta
        $usuarios = Usuario::where('rol', 'usuario')->where('estado', 'activo')->get();
        return view('prestamos.create', compact('prestamos', 'libros', 'usuarios'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'libro_id'=>'required',
            'usuario_id'=>'required',
            'fecha_prestamo'=>'required|date',
            'fecha_devolucion'=>'required|date|after_or_equal:fecha_prestamo',
            'estado'=>'required|in:devuelto,ocupado,atrasado',
        ]);

        Prestamo::create($request->all());

        return redirect()->route('dashboard.gestion-libros')
            ->with('success', 'Nuevo préstamo realizado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Prestamo $prestamo)
    {
        return view('prestamos.show', compact('prestamo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Prestamo $prestamo)
    {
        $libros = Libro::all();
        $usuarios = Usuario::where('rol', 'usuario')->get();
        return view('prestamos.edit', compact('prestamo', 'libros', 'usuarios'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Prestamo $prestamo)
    {
        $request->validate([
            'libro_id'=>'required',
            'usuario_id'=>'required',
            'fecha_prestamo'=>'required|date',
            'fecha_devolucion'=>'required|date|after_or_equal:fecha_prestamo',
            'estado'=>'required|in:devuelto,ocupado,atrasado',
        ]);

        $prestamo->update($request->all());

        return redirect()->route('dashboard.gestion-libros')
            ->with('success', 'Préstamo actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Prestamo $prestamo)
    {
        $prestamo->delete();
        return redirect()->route('dashboard.gestion-libros')
            ->with('success', 'Préstamo eliminado exitosamente.');
    }

    /**
     * Extender la fecha de devolución 2 días.
     */
    public function extend(Prestamo $prestamo)
    {
        // Verificar si ya fue extendido
        if ($prestamo->extendido) {
            return back()->with('error', 'Este préstamo ya fue extendido anteriormente.');
        }

        // Añadir 2 días a la fecha_devolucion
        $fecha = \Carbon\Carbon::parse($prestamo->fecha_devolucion ?? now())->addDays(2);
        $prestamo->fecha_devolucion = $fecha;
        $prestamo->extendido = true;
        $prestamo->save();

        return back()->with('success', 'Fecha de devolución extendida 2 días.');
    }
}
