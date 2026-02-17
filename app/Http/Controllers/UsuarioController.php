<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Solo traer usuarios con rol 'usuario' (excluir bibliotecarios)
        $usuarios = Usuario::where('rol', 'usuario')->get();
        return view('usuarios.index', compact('usuarios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('usuarios.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombres' => 'required|string|max:255',
            'apellidos' => 'nullable|string|max:255',
            'correo' => 'required|email|unique:usuarios,correo',
            'password' => 'required|string|min:6|confirmed',
            'rol' => 'required|in:usuario,bibliotecario',
            'telefono' => 'nullable|string|max:50',
            'direccion' => 'nullable|string',
            'estado' => 'required|in:activo,bloqueado',
        ]);

        $data = $request->only(['nombres','apellidos','correo','rol','telefono','direccion','estado']);
        $data['password'] = Hash::make($request->password);

        Usuario::create($data);

        return redirect()->route('dashboard.usuarios')->with('success', 'Nuevo usuario creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Usuario $usuario)
    {
        return view('usuarios.show', compact('usuario'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Usuario $usuario)
    {
        return view('usuarios.edit', compact('usuario'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Usuario $usuario)
    {
        $request->validate([
            'nombres' => 'required|string|max:255',
            'apellidos' => 'nullable|string|max:255',
            'correo' => 'required|email|unique:usuarios,correo,'.$usuario->id.',id',
            'password' => 'nullable|string|min:6|confirmed',
            'rol' => 'required|in:usuario,bibliotecario',
            'telefono' => 'nullable|string|max:50',
            'direccion' => 'nullable|string',
            'estado' => 'required|in:activo,bloqueado',
        ]);

        $data = $request->only(['nombres','apellidos','correo','rol','telefono','direccion','estado']);

        // Si se envía contraseña, cifrarla. Si no, no modificarla.
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $usuario->update($data);

        return redirect()->route('dashboard.usuarios')->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Usuario $usuario)
    {
        $usuario->delete();
        return redirect()->route('dashboard.usuarios')->with('success', 'Usuario eliminado exitosamente.');
    }
}
