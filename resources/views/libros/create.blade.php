<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca - Agregar Libro</title>
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
            <img src="../images/profile.png" alt="Perfil" class="w-8 h-8 rounded-full">
            <div>
                @auth
                    <p class="text-sm font-medium text-gray-800">
                        {{ Auth::user()->nombres }} {{ Auth::user()->apellidos }}
                    </p>
                    <p class="text-xs text-gray-500">{{ Auth::user()->correo }}</p>
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
            <h2 class="text-2xl font-bold text-gray-800">Agregar Nuevo Libro</h2>
            <a href="{{ route('dashboard.todos-libros') }}"
                class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition-colors">
                Volver a Libros
            </a>
        </div>

        <!-- Formulario CAMBIAR PREGUNTA-->
        <div class="bg-white rounded-lg shadow p-6 max-w-2xl">
            <form action="{{ route('libros.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Título -->
                <div>
                    <label for="titulo" class="block text-sm font-medium text-gray-700 mb-2">
                        Título del Libro
                    </label>
                    <input type="text" id="titulo" name="titulo" value="{{ old('titulo') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        placeholder="Ingresa el título del libro" required>
                    @error('titulo')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Autor -->
                <div>
                    <label for="autor" class="block text-sm font-medium text-gray-700 mb-2">
                        Autor
                    </label>
                    <input type="text" id="autor" name="autor" value="{{ old('autor') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        placeholder="Ingresa el nombre del autor" required>
                    @error('autor')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Categoría -->
                <div>
                    <label for="categoria" class="block text-sm font-medium text-gray-700 mb-2">
                        Categoría
                    </label>
                    <select id="categoria" name="categoria"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        required>
                        <option value="">Selecciona una categoría</option>
                        <option value="Ficción" {{ old('categoria') == 'Ficción' ? 'selected' : '' }}>Ficción</option>
                        <option value="No Ficción" {{ old('categoria') == 'No Ficción' ? 'selected' : '' }}>No Ficción
                        </option>
                        <option value="Ciencia" {{ old('categoria') == 'Ciencia' ? 'selected' : '' }}>Ciencia</option>
                        <option value="Historia" {{ old('categoria') == 'Historia' ? 'selected' : '' }}>Historia
                        </option>
                        <option value="Biografía" {{ old('categoria') == 'Biografía' ? 'selected' : '' }}>Biografía
                        </option>
                        <option value="Educación" {{ old('categoria') == 'Educación' ? 'selected' : '' }}>Educación
                        </option>
                        <option value="Tecnología" {{ old('categoria') == 'Tecnología' ? 'selected' : '' }}>Tecnología
                        </option>
                        <option value="Filosofía" {{ old('categoria') == 'Filosofía' ? 'selected' : '' }}>Filosofía</option>
                        <option value="Romance" {{ old('categoria') == 'Romance' ? 'selected' : '' }}>Romance</option>
                        <option value="Arte" {{ old('categoria') == 'Arte' ? 'selected' : '' }}>Arte</option>
                        <option value="Otro" {{ old('categoria') == 'Otro' ? 'selected' : '' }}>Otro</option>
                    </select>
                    @error('categoria')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>



                <!-- Sinopsis -->
                <div>
                    <label for="sinopsis" class="block text-sm font-medium text-gray-700 mb-2">
                        Sinopsis
                    </label>
                    <textarea id="sinopsis" name="sinopsis" rows="4"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        placeholder="Sinopsis del libro" required>{{ old('sinopsis') }}</textarea>
                    @error('sinopsis')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Imagen SUBIENDO IMAGEN -->
                <div>
                    <label for="imagen" class="block text-sm font-medium text-gray-700 mb-2">
                        Portada del Libro (Imagen)
                    </label>
                    <input type="file" id="imagen" name="imagen" accept="image/*"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        required>
                    <p class="mt-1 text-sm text-gray-500">Sube una imagen </p>
                    @error('imagen')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Botones -->
                <div class="flex items-center justify-end space-x-4 pt-4">
                    <a href="{{ route('dashboard.todos-libros') }}"
                        class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit"
                        class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                        Guardar Libro
                    </button>
                </div>
            </form>
        </div>

        <!-- Mensajes de éxito/error -->
        @if (session('success'))
            <div class="mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                {{ session('error') }}
            </div>
        @endif
    </main>

</body>

</html>
