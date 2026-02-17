<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca - Nuevo Préstamo</title>
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
            <a href="{{ route('libros.create') }}" class="block px-3 py-2 rounded-md hover:bg-gray-200">Agregar
                Libro</a>
            <a href="{{ route('prestamos.create') }}"
                class="block px-3 py-2 rounded-md hover:bg-gray-200 font-semibold text-green-600 bg-green-50">Nuevo
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
            <h2 class="text-2xl font-bold text-gray-800">Crear Nuevo Préstamo</h2>
            <a href="{{ route('dashboard.gestion-libros') }}"
                class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition-colors">
                Volver a Préstamos
            </a>
        </div>

        <!-- Formulario -->
        <div class="bg-white rounded-lg shadow p-6 max-w-2xl">
            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 rounded-md p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Por favor corrige los siguientes errores:</h3>
                            <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('prestamos.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Selección de Libro y Usuario en una fila -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Libro -->
                    <div>
                        <label for="libro_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Libro a Prestar </span>
                        </label>
                        <select id="libro_id" name="libro_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('libro_id') border-red-500 @enderror"
                            required>
                            <option value="">Selecciona un libro...</option>
                            @foreach ($libros as $libro)
                                <option value="{{ $libro->id }}"
                                    {{ old('libro_id') == $libro->id ? 'selected' : '' }}>
                                    {{ $libro->titulo }} - {{ $libro->autor }}
                                </option>
                            @endforeach
                        </select>
                        @error('libro_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Usuario -->
                    <div>
                        <label for="usuario_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Usuario </span>
                        </label>
                        <select id="usuario_id" name="usuario_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('usuario_id') border-red-500 @enderror"
                            required>
                            <option value="">Selecciona un usuario...</option>
                            @foreach ($usuarios as $usuario)
                                <option value="{{ $usuario->id }}"
                                    {{ old('usuario_id') == $usuario->id ? 'selected' : '' }}>
                                    {{ $usuario->nombres }} {{ $usuario->apellidos }}
                                </option>
                            @endforeach
                        </select>
                        @error('usuario_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Fechas en una fila -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Fecha de Préstamo -->
                    <div>
                        <label for="fecha_prestamo" class="block text-sm font-medium text-gray-700 mb-2">
                            Fecha de Préstamo </span>
                        </label>
                        <input type="date" id="fecha_prestamo" name="fecha_prestamo"
                            value="{{ old('fecha_prestamo', date('Y-m-d')) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('fecha_prestamo') border-red-500 @enderror"
                            required>
                        @error('fecha_prestamo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Fecha de Devolución -->
                    <div>
                        <label for="fecha_devolucion" class="block text-sm font-medium text-gray-700 mb-2">
                            Fecha de Devolución </span>
                        </label>
                        <input type="date" id="fecha_devolucion" name="fecha_devolucion"
                            value="{{ old('fecha_devolucion', date('Y-m-d', strtotime('+15 days'))) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('fecha_devolucion') border-red-500 @enderror"
                            required>
                        @error('fecha_devolucion')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Estado -->
                <div>
                    <label for="estado" class="block text-sm font-medium text-gray-700 mb-2">
                        Estado del Préstamo </span>
                    </label>
                    <select id="estado" name="estado"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('estado') border-red-500 @enderror"
                        required>
                        <option value="">Selecciona un estado...</option>
                        <option value="ocupado" {{ old('estado', 'ocupado') == 'ocupado' ? 'selected' : '' }}>Ocupado
                            (Prestado)</option>
                        <option value="devuelto" {{ old('estado') == 'devuelto' ? 'selected' : '' }}>Devuelto
                        </option>
                        <option value="atrasado" {{ old('estado') == 'atrasado' ? 'selected' : '' }}>Atrasado</option>
                    </select>
                    @error('estado')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Botones -->
                <div class="flex items-center justify-end space-x-4 pt-4">
                    <a href="{{ route('dashboard.gestion-libros') }}"
                        class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit"
                        class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                        Crear Préstamo
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

    <!-- Script para validación de fechas -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fechaPrestamo = document.getElementById('fecha_prestamo');
            const fechaDevolucion = document.getElementById('fecha_devolucion');

            fechaPrestamo.addEventListener('change', function() {
                // Actualizar la fecha mínima de devolución
                fechaDevolucion.min = this.value;

                // Si la fecha de devolución es anterior a la de préstamo, actualizarla
                if (fechaDevolucion.value && fechaDevolucion.value < this.value) {
                    const nuevaFecha = new Date(this.value);
                    nuevaFecha.setDate(nuevaFecha.getDate() + 15);
                    fechaDevolucion.value = nuevaFecha.toISOString().split('T')[0];
                }
            });
        });
    </script>

</body>

</html>
