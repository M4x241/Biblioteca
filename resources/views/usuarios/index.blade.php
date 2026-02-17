@extends('layouts.app')

@section('title', 'Listado de Usuarios')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Usuarios</h1>
        <a href="{{ route('usuarios.create') }}" class="btn btn-primary">Nuevo Usuario</a>
    </div>

    @if($usuarios->isEmpty())
        <p>No hay usuarios con rol "usuario".</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombres</th>
                    <th>Apellidos</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($usuarios as $usuario)
                    <tr>
                        <td>{{ $usuario->id }}</td>
                        <td>{{ $usuario->nombres }}</td>
                        <td>{{ $usuario->apellidos }}</td>
                        <td>{{ $usuario->correo }}</td>
                        <td>{{ $usuario->telefono }}</td>
                        <td>
                            <a href="{{ route('usuarios.show', $usuario) }}" class="btn btn-sm btn-info">Ver</a>
                            <a href="{{ route('usuarios.edit', $usuario) }}" class="btn btn-sm btn-warning">Editar</a>
                            <form action="{{ route('usuarios.destroy', $usuario) }}" method="POST" style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar usuario?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
