<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca - Usuarios</title>
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
            <form method="GET" action="{{ route('dashboard.usuarios') }}" class="flex gap-2">
                <input type="text" name="q" value="{{ isset($q) ? e($q) : '' }}"
                    placeholder="Buscar por nombre, apellido o correo"
                    class="flex-1 px-3 py-2 text-sm border rounded-md focus:outline-none focus:ring focus:ring-green-200">
            </form>
        </div>


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
            <img src="../../images/profile.png" alt="Perfil" class="w-8 h-8 rounded-full">
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


        <!-- Paginación -->

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
            <h2 class="text-2xl font-bold text-gray-800">Usuarios</h2>
            <a href="{{ route('usuarios.create') }}"
                class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition-colors">
                Nuevo Usuario
            </a>
        </div>

        {{-- Mensajes de éxito --}}
        @if (session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                role="alert">
                <strong class="font-bold">¡Éxito!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                    <svg class="fill-current h-6 w-6 text-green-500" role="button"
                        onclick="this.parentElement.parentElement.style.display='none'"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <title>Cerrar</title>
                        <path
                            d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                    </svg>
                </span>
            </div>
        @endif

        {{-- Mensajes de error --}}
        @if (session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">¡Error!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                    <svg class="fill-current h-6 w-6 text-red-500" role="button"
                        onclick="this.parentElement.parentElement.style.display='none'"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <title>Cerrar</title>
                        <path
                            d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                    </svg>
                </span>
            </div>
        @endif

        <!-- Tabla de usuarios -->
        <div class="bg-white rounded-lg shadow overflow-hidden flex-1 min-h-0">
            <div class="overflow-y-auto max-h-full">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nombre Completo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Correo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Rol</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Teléfono</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Dirección</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($usuarios as $user)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $user->nombres }}
                                        {{ $user->apellidos }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $user->correo }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded
                  {{ $user->rol === 'bibliotecario' ? 'bg-green-700 text-white' : 'bg-indigo-700 text-white' }}">
                                        {{ ucfirst($user->rol) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded
                  {{ $user->estado === 'activo' ? 'bg-green-700 text-white' : 'bg-red-700 text-white' }}">
                                        {{ ucfirst($user->estado) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $user->telefono ?? 'Sin teléfono' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 max-w-xs">
                                    <div class="truncate" title="{{ $user->direccion }}">
                                        {{ $user->direccion ?? 'Sin dirección' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <!-- Botón Ver que redirige -->
                                    <a href="{{ route('usuarios.show', $user) }}"
                                       class="px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 transition-colors inline-block">
                                        Ver
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                    No hay usuarios registrados
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Paginacion -->
        @if (isset($totalPages) && $totalPages > 1)
            <div class="mt-6 flex items-center justify-center space-x-4">
                @if ($hasPrev)
                    <a href="{{ route('dashboard.usuarios', array_merge(['page' => $page - 1], isset($q) && $q !== '' ? ['q' => $q] : [])) }}"
                        class="px-4 py-2 bg-white border rounded text-sm text-gray-700 hover:bg-gray-50">Anterior</a>
                @else
                    <span
                        class="px-4 py-2 bg-gray-100 border rounded text-sm text-gray-400 cursor-not-allowed">Anterior</span>
                @endif

                <span class="px-4 py-2 text-sm text-gray-600 bg-gray-50 rounded-md border">Página {{ $page }}
                    de {{ $totalPages }}</span>

                @if ($hasNext)
                    <a href="{{ route('dashboard.usuarios', array_merge(['page' => $page + 1], isset($q) && $q !== '' ? ['q' => $q] : [])) }}"
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
