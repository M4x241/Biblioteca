<?php

namespace App\Http\Controllers;

use App\Models\Libro;
use App\Models\Prestamo;
use Illuminate\Http\Request;

class LibroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $libros = Libro::all();
        return view('libros.index', compact('libros'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $libros = Libro::all();
        $prestamos = Prestamo::all();
        return view('libros.create', compact('libros','prestamos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'titulo'   => 'required|string|max:255',
            'imagen'   => 'required|file|image|max:5120', // max 5MB
            'autor'    => 'required|string|max:255',
            'categoria'=> 'required|string|max:255',
            'sinopsis' => 'required|string',
        ]);

        // Manejo de la imagen subida
        $imagePath = null;
        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $destination = public_path('images-libros');
            if (!is_dir($destination)) {
                mkdir($destination, 0775, true);
            }
            $file->move($destination, $filename);
            $imagePath = 'images-libros/' . $filename;
        }

        // Crear el libro con la ruta de imagen almacenada
        Libro::create([
            'titulo'    => $request->input('titulo'),
            'imagen'    => $imagePath,
            'autor'     => $request->input('autor'),
            'categoria' => $request->input('categoria'),
            'sinopsis'  => $request->input('sinopsis'),
        ]);

        return redirect()->route('dashboard.todos-libros')->with('success', 'Nuevo libro agregado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Libro $libro)
    {
        return view('libros.show', compact('libro'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Libro $libro)
    {
        // editar libro y su disponibilidad
        $libros = Libro::all();
        $prestamos = Prestamo::all();
        return view('libros.edit', compact('libro','prestamos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Libro $libro)
    {
        $request->validate([
            'id'=>'required',
            'titulo'=>'required',
            'imagen'=>'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'autor'=>'required',
            'categoria'=>'required',
            'sinopsis'=>'required',
        ]);

        $data = [
            'titulo'    => $request->input('titulo'),
            'autor'     => $request->input('autor'),
            'categoria' => $request->input('categoria'),
            'sinopsis'  => $request->input('sinopsis'),
        ];

        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $destination = public_path('images-libros');
            if (!is_dir($destination)) {
                mkdir($destination, 0775, true);
            }
            $file->move($destination, $filename);
            $data['imagen'] = 'images-libros/' . $filename;
        }

        $libro->update($data);

        return redirect()->route('dashboard.todos-libros')->with('success', 'Libro actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Libro $libro)
    {
        $libro->delete();
        return redirect()->route('dashboard.todos-libros')->with('success', 'Libro eliminado exitosamente.');
    }
}
