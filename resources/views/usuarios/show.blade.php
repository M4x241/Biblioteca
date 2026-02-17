<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Usuario</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gray-100 min-h-screen flex text-gray-900 font-sans" x-data="{ deleteModal: false }">

    <!-- Sidebar -->
    <aside class="w-64 bg-white border-r flex flex-col h-screen overflow-y-auto">

        <!-- Logo -->
        @include('components.sidebar-logo')

        <!-- Buscador -->
        <div class="p-4 border-b">
            <input type="text" placeholder="Buscar"
                class="w-full px-3 py-2 text-sm border rounded-md focus:outline-none focus:ring focus:ring-indigo-200">
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
            <img src="{{ asset('images/profile.png') }}" alt="Perfil" class="w-8 h-8 rounded-full">
            <div>
                @auth
                    <p class="text-sm font-medium text-gray-800">
                        {{ auth()->user()->nombres }} {{ auth()->user()->apellidos }}
                    </p>
                    <p class="text-xs text-gray-500">{{ auth()->user()->correo }}</p>
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
    <main class="flex-1 p-8 overflow-y-auto">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="mb-6">
                <a href="{{ route('dashboard.usuarios') }}"
                   class="inline-flex items-center text-gray-600 hover:text-green-600 transition-colors mb-4">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Volver
                </a>
                <h1 class="text-2xl font-bold text-gray-800">Detalle del Usuario</h1>
            </div>

            <!-- Card Principal -->
            <div class="bg-white rounded-lg shadow overflow-hidden border border-gray-200">

                <!-- Información del Usuario -->
                <div class="p-6 space-y-4">

                    <!-- Nombre completo -->
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase mb-1">Nombres</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $usuario->nombres }}</p>
                        </div>

                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase mb-1">Apellidos</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $usuario->apellidos }}</p>
                        </div>
                    </div>

                    <div class="border-t border-gray-200"></div>

                    <!-- Contacto -->
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase mb-1">Correo Electrónico</p>
                            <p class="text-base text-gray-900">{{ $usuario->correo }}</p>
                        </div>

                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase mb-1">Teléfono</p>
                            <p class="text-base text-gray-900">{{ $usuario->telefono ?? 'No registrado' }}</p>
                        </div>
                    </div>

                    <div class="border-t border-gray-200"></div>

                    <!-- Dirección -->
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase mb-1">Dirección</p>
                        <p class="text-base text-gray-900">{{ $usuario->direccion ?? 'No registrada' }}</p>
                    </div>

                    <div class="border-t border-gray-200"></div>

                    <!-- Rol y Estado -->
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase mb-2">Rol</p>
                            <span class="inline-flex px-3 py-1 text-sm font-medium rounded bg-indigo-100 text-indigo-800">
                                {{ ucfirst($usuario->rol) }}
                            </span>
                        </div>

                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase mb-2">Estado</p>
                            <span class="inline-flex px-3 py-1 text-sm font-medium rounded
                                @if($usuario->estado == 'activo') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($usuario->estado) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Acciones -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex gap-3 justify-end">
                    <a href="{{ route('usuarios.edit', $usuario) }}"
                       class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                        Editar
                    </a>
                    <button @click="deleteModal = true"
                            class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 transition-colors">
                        Eliminar
                    </button>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal Eliminar Usuario -->
    <div x-show="deleteModal" 
         x-cloak
         @click.away="deleteModal = false"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm"
         style="display: none;">
        
        <div @click.stop
             class="bg-white rounded-lg shadow-2xl max-w-md w-full mx-4 p-6">
            
            <!-- Icono de advertencia -->
            <div class="flex justify-center mb-4">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
            </div>

            <!-- Contenido -->
            <div class="text-center mb-6">
                <h3 class="text-xl font-bold text-gray-900 mb-2">¿Eliminar Usuario?</h3>
                <p class="text-gray-700">¿Estás seguro de eliminar al usuario:</p>
                <p class="font-bold text-lg text-gray-900 mt-2">
                    {{ $usuario->nombres }} {{ $usuario->apellidos }}
                </p>
                <p class="text-red-600 text-sm mt-3">Esta acción no se puede deshacer.</p>
            </div>

            <!-- Botones -->
            <div class="flex gap-3">
                <button @click="deleteModal = false"
                        class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 font-medium transition-colors">
                    Cancelar
                </button>
                <form action="{{ route('usuarios.destroy', $usuario) }}" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="w-full px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 font-medium transition-colors">
                        Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
    </style>

</body>

</html>
