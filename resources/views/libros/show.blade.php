<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Libro</title>
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
                <a href="{{ route('dashboard.todos-libros') }}"
                    class="inline-flex items-center text-gray-600 hover:text-green-600 transition-colors mb-4">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Volver
                </a>
                <h1 class="text-2xl font-bold text-gray-800">Detalle del Libro</h1>
            </div>

            <!-- Card Principal -->
            <div class="bg-white rounded-lg shadow overflow-hidden border border-gray-200">

                <!-- Contenido -->
                <div class="p-6">
                    <div class="flex flex-col md:flex-row gap-6">

                        <!-- Imagen del libro -->
                        <div class="flex-shrink-0">
                            <img src="{{ asset($libro->imagen ?? 'images/libro-default.png') }}"
                                alt="{{ $libro->titulo }}"
                                class="w-48 h-64 object-cover rounded-lg border border-gray-200">
                        </div>

                        <!-- Información del libro -->
                        <div class="flex-1 space-y-4">

                            <!-- Título -->
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900">{{ $libro->titulo }}</h2>
                            </div>

                            <div class="border-t border-gray-200 pt-4 space-y-3">

                                <!-- Autor -->
                                <div>
                                    <p class="text-xs font-medium text-gray-500 uppercase mb-1">Autor</p>
                                    <p class="text-base text-gray-900">{{ $libro->autor ?? 'Sin autor' }}</p>
                                </div>

                                <!-- Categoría -->
                                <div>
                                    <p class="text-xs font-medium text-gray-500 uppercase mb-1">Categoría</p>
                                    <span
                                        class="inline-flex px-3 py-1 text-sm font-medium rounded bg-gray-100 text-gray-800">
                                        {{ $libro->categoria ?? 'Sin categoría' }}
                                    </span>
                                </div>

                                <!-- Sinopsis -->
                                <div>
                                    <p class="text-xs font-medium text-gray-500 uppercase mb-1">Sinopsis</p>
                                    <p class="text-sm text-gray-700 leading-relaxed">
                                        {{ $libro->sinopsis ?? 'Sin sinopsis disponible' }}
                                    </p>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <!-- Acciones -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex gap-3 justify-end">
                    <a href="{{ route('libros.edit', $libro) }}"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                        Editar
                    </a>
                    <form action="{{ route('libros.destroy', $libro) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700 transition-colors"
                            onclick="return confirm('¿Estás seguro de eliminar este libro?')">
                            Eliminar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>

</body>

</html>
