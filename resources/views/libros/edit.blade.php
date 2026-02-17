<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Libro</title>
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
                class="w-full px-3 py-2 text-sm border rounded-md focus:outline-none focus:ring focus:ring-green-200">
        </div>

       <!-- Navegación -->
        <nav class="flex-1 p-4 space-y-2 text-sm">
            <div class="text-gray-500 text-xs uppercase mb-2">MI BIBLIOTECA</div>

            <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-md hover:bg-gray-200">Libros</a>

            <a href="{{ route('dashboard.todos-libros') }}" class="block px-3 py-2 rounded-md hover:bg-gray-200">Todos
                los libros</a>
            {{-- <a href="{{ route('dashboard.prestamos') }}" class="block px-3 py-2 rounded-md hover:bg-gray-200">Préstamos</a> --}}
            {{-- <a href="{{ route('dashboard.reservas') }}" class="block px-3 py-2 rounded-md hover:bg-gray-200">Reservas</a> --}}
            <a href="{{ route('dashboard.gestion-libros') }}"
                class="block px-3 py-2 rounded-md hover:bg-gray-200 {{ request()->routeIs('dashboard.gestion-libros') ? 'font-semibold text-green-600 bg-green-50' : '' }}">
                Gestión de Préstamos
            </a>

            <a href="{{ route('dashboard.usuarios') }}"
                class="block px-3 py-2 rounded-md hover:bg-gray-200">Usuarios</a>

            <div class="mt-6 text-gray-500 text-xs uppercase mb-2">GESTIÓN</div>
            <a href="{{ route('libros.create') }}"
                class="block px-3 py-2 rounded-md hover:bg-gray-200 font-semibold text-green-600 bg-green-50">Agregar
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
                <h1 class="text-2xl font-bold text-gray-800">Editar Libro</h1>
            </div>

            <!-- Mensajes de Error -->
            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    <p class="font-medium mb-2">Por favor corrige los siguientes errores:</p>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li class="text-sm">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Formulario -->
            <form action="{{ route('libros.update', $libro) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="bg-white rounded-lg shadow overflow-hidden border border-gray-200">
                    <div class="p-6 space-y-6">

                        <!-- ID Libro (Hidden pero requerido según validación) -->
                        <input type="hidden" name="id" value="{{ $libro->id }}">

                        <!-- Título -->
                        <div>
                            <label for="titulo" class="block text-sm font-medium text-gray-700 mb-2">
                                Título *
                            </label>
                            <input type="text" name="titulo" id="titulo"
                                value="{{ old('titulo', $libro->titulo) }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>

                        <!-- Autor -->
                        <div>
                            <label for="autor" class="block text-sm font-medium text-gray-700 mb-2">
                                Autor *
                            </label>
                            <input type="text" name="autor" id="autor"
                                value="{{ old('autor', $libro->autor) }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>

                        <!-- Categoría -->
                        <div>
                            <label for="categoria" class="block text-sm font-medium text-gray-700 mb-2">
                                Categoría *
                            </label>
                            <input type="text" name="categoria" id="categoria"
                                value="{{ old('categoria', $libro->categoria) }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>

                        <!-- Imagen de Portada (subida opcional) -->
                        <div>
                            <label for="imagen" class="block text-sm font-medium text-gray-700 mb-2">
                                Portada del Libro (Imagen)
                            </label>

                            @if($libro->imagen)
                                <div class="mb-3">
                                    <p class="text-sm text-gray-600 mb-2">Imagen actual:</p>
                                    <img src="{{ asset($libro->imagen) }}" alt="{{ $libro->titulo }}"
                                         class="w-32 h-48 object-cover rounded border border-gray-300">
                                </div>
                            @endif
                            <input type="file" id="imagen" name="imagen" accept="image/*"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            </p>
                        </div>

                        <!-- Sinopsis -->
                        <div>
                            <label for="sinopsis" class="block text-sm font-medium text-gray-700 mb-2">
                                Sinopsis *
                            </label>
                            <textarea name="sinopsis" id="sinopsis" rows="5" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">{{ old('sinopsis', $libro->sinopsis) }}</textarea>
                        </div>

                    </div>

                    <!-- Botones de Acción -->
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex gap-3 justify-end">
                        <a href="{{ route('dashboard.todos-libros') }}"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                            Cancelar
                        </a>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700 transition-colors">
                            Actualizar Libro
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </main>

</body>

</html>
