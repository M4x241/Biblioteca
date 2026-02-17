<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca - Préstamos</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex text-gray-900 font-sans">

    <!-- Sidebar -->
    <aside class="w-64 bg-white border-r flex flex-col h-screen overflow-y-auto">

        <!-- Logo -->
        @include('components.sidebar-logo')

        <!-- Buscador -->
        <div class="p-4 border-b">
            <form method="GET" action="{{ route('dashboard.gestion-libros') }}">
                <input type="text" name="q" value="{{ isset($q) ? e($q) : '' }}"
                    placeholder="Buscar prestamos o autor"
                    class="w-full px-3 py-2 text-sm border rounded-md focus:outline-none focus:ring focus:ring-green-200">
            </form>
        </div>


        <!-- Navegación -->
       <nav class="flex-1 p-4 space-y-2 text-sm">
            <div class="text-gray-500 text-xs uppercase mb-2">MI BIBLIOTECA</div>

            <a href="{{ route('dashboard') }}"
                class="block px-3 py-2 rounded-md hover:bg-gray-200 {{ request()->routeIs('dashboard') ? 'font-semibold text-green-600 bg-green-50' : '' }}">
                Libros
            </a>

            <a href="{{ route('dashboard.todos-libros') }}"
                class="block px-3 py-2 rounded-md hover:bg-gray-200 {{ request()->routeIs('dashboard.todos-libros') ? 'font-semibold text-green-600 bg-green-50' : '' }}">
                Todos los libros
            </a>

            <a href="{{ route('dashboard.gestion-libros') }}"
                class="block px-3 py-2 rounded-md hover:bg-gray-200 {{ request()->routeIs('dashboard.gestion-libros') ? 'font-semibold text-green-600 bg-green-50' : '' }}">
                Gestión de Préstamos
            </a>

            <a href="{{ route('dashboard.usuarios') }}"
                class="block px-3 py-2 rounded-md hover:bg-gray-200 {{ request()->routeIs('dashboard.usuarios') ? 'font-semibold text-green-600 bg-green-50' : '' }}">
                Usuarios
            </a>

            <div class="mt-6 text-gray-500 text-xs uppercase mb-2">GESTIÓN</div>
            <a href="{{ route('libros.create') }}" class="block px-3 py-2 rounded-md hover:bg-gray-200">Agregar
                Libro</a>
            <a href="{{ route('prestamos.create') }}" class="block px-3 py-2 rounded-md hover:bg-gray-200">Nuevo
                Préstamo</a>
            <a href="{{ route('usuarios.create') }}" class="block px-3 py-2 rounded-md hover:bg-gray-200">Agregar
                Usuario</a>
        </nav>

        <!-- Perfil -->
        <div class="p-4 border-t flex items-center gap-3">
            <img src="../images/profile.png" alt="Perfil" class="w-8 h-8 rounded-full">
            <div>
                @auth
                    <p class="text-sm font-medium text-gray-800">
                        {{ $usuario->nombres }} {{ $usuario->apellidos }}
                    </p>
                    <p class="text-xs text-gray-500">{{ $usuario->correo }}</p>
                @else
                    <p class="text-sm text-gray-500 italic">Registrarse</p>
                @endauth
            </div>
        </div>
        <div class="p-4 border-t">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="w-full px-2 py-2 bg-gray-500 text-white rounded-md hover:bg-green-600 transition-colors text-sm">Cerrar
                    Sesión</button>
            </form>
        </div>
    </aside>

    <!-- Contenido principal -->
    <main class="flex-1 flex flex-col p-8 h-screen overflow-y-auto">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Préstamos</h2>
            <a href="{{ route('prestamos.create') }}"
                class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition-colors">
                Nuevo Préstamo
            </a>
        </div>

        <!-- Tabla de préstamos -->
        <div class="bg-white rounded-lg shadow overflow-hidden flex-1  min-h-0">
            <div class="overflow-y-auto max-h-full">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Libro</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Autor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Usuario</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Fecha Préstamo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Fecha Devolución</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($prestamos as $prestamo)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $prestamo->libro->titulo }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $prestamo->libro->autor }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $prestamo->usuario->nombres }} {{ $prestamo->usuario->apellidos }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($prestamo->fecha_prestamo)->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($prestamo->fecha_devolucion)->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                    @if ($prestamo->estado == 'disponible') bg-green-100 text-green-800
                                    @elseif($prestamo->estado == 'ocupado') bg-yellow-100 text-yellow-800
                                    @elseif($prestamo->estado == 'atrasado') bg-red-100 text-red-800 @endif">
                                        {{ ucfirst($prestamo->estado) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    <a href="{{ route('prestamos.show', $prestamo) }}"
                                        class="text-indigo-600 hover:text-indigo-900 inline-block">Ver</a>
                                    {{-- <a href="{{ route('prestamos.edit', $prestamo) }}"
                                        class="text-yellow-600 hover:text-yellow-900 inline-block">Editar</a>
                                    <form action="{{ route('prestamos.destroy', $prestamo) }}" method="POST"
                                        class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900"
                                            onclick="return confirm('¿Eliminar préstamo?')">
                                            Eliminar
                                        </button>
                                    </form> --}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        {{-- <--- Paginación --> --}}
        @if (isset($totalPages) && $totalPages > 1)
                <div class="mt-6 flex items-center justify-center space-x-4">
                    @if ($hasPrev)
                        <a href="{{ route('dashboard.prestamos', array_merge(['page' => $page - 1], isset($q) && $q !== '' ? ['q' => $q] : [])) }}"
                            class="px-4 py-2 bg-white border rounded text-sm text-gray-700 hover:bg-gray-50">Anterior</a>
                    @else
                        <span
                            class="px-4 py-2 bg-gray-100 border rounded text-sm text-gray-400 cursor-not-allowed">Anterior</span>
                    @endif

                    <span class="px-4 py-2 text-sm text-gray-600 bg-gray-50 rounded-md border">Página
                        {{ $page }} de {{ $totalPages }}</span>

                    @if ($hasNext)
                        <a href="{{ route('dashboard.prestamos', array_merge(['page' => $page + 1], isset($q) && $q !== '' ? ['q' => $q] : [])) }}"
                            class="px-4 py-2 bg-white border rounded text-sm text-gray-700 hover:bg-gray-50">Siguiente</a>
                    @else
                        <span
                            class="px-4 py-2 bg-gray-100 border rounded text-sm text-gray-400 cursor-not-allowed">Siguiente</span>
                    @endif
                </div>
            @endif

    </main>

</body>

</html>
