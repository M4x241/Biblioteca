<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Biblioteca')</title>
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
            <form method="GET" action="{{ route('dashboard.reservas') }}">
                <input type="text" name="q" value="{{ isset($q) ? e($q) : '' }}"
                    placeholder="Buscar por título o autor"
                    class="w-full px-3 py-2 text-sm border rounded-md focus:outline-none focus:ring focus:ring-green-200">
            </form>
        </div>

        
        <!-- Navegación -->
        <nav class="flex-1 p-4 space-y-2 text-sm">
            <div class="text-gray-500 text-xs uppercase mb-2">MI BIBLIOTECA</div>

            <a href="{{ route('dashboard') }}"
                class="block px-3 py-2 rounded-md hover:bg-gray-200 {{ request()->routeIs('dashboard') ? 'font-semibold text-green-600 bg-green-50' : '' }}">Libros</a>

            <a href="{{ route('dashboard.todos-libros') }}"
                class="block px-3 py-2 rounded-md hover:bg-gray-200 {{ request()->routeIs('dashboard.todos-libros') ? 'font-semibold text-green-600 bg-green-50' : '' }}">Todos
                los libros</a>

            <a href="{{ route('dashboard.prestamos') }}"
                class="block px-3 py-2 rounded-md hover:bg-gray-200 {{ request()->routeIs('dashboard.prestamos') || request()->routeIs('prestamos.*') ? 'font-semibold text-green-600 bg-green-50' : '' }}">Préstamos</a>

            <a href="{{ route('dashboard.usuarios') }}"
                class="block px-3 py-2 rounded-md hover:bg-gray-200 {{ request()->routeIs('dashboard.usuarios') || request()->routeIs('usuarios.*') ? 'font-semibold text-green-600 bg-green-50' : '' }}">Usuarios</a>
            <a href="{{ route('dashboard.reservas') }}"
                class="block px-3 py-2 rounded-md hover:bg-gray-200 {{ request()->routeIs('dashboard.reservas') || request()->routeIs('reservas.*') ? 'font-semibold text-green-600 bg-green-50' : '' }}">Reservas</a>
            <div class="mt-6 text-gray-500 text-xs uppercase mb-2">GESTIÓN</div>
            <a href="{{ route('libros.create') }}"
                class="block px-3 py-2 rounded-md hover:bg-gray-200 {{ request()->routeIs('libros.create') ? 'font-semibold text-green-600 bg-green-50' : '' }}">Agregar
                Libro</a>

            <a href="{{ route('prestamos.create') }}"
                class="block px-3 py-2 rounded-md hover:bg-gray-200 {{ request()->routeIs('prestamos.create') ? 'font-semibold text-green-600 bg-green-50' : '' }}">Nuevo
                Préstamo</a>

            <a href="{{ route('usuarios.create') }}"
                class="block px-3 py-2 rounded-md hover:bg-gray-200 {{ request()->routeIs('usuarios.create') ? 'font-semibold text-green-600 bg-green-50' : '' }}">Agregar
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
        @yield('content')
    </main>
    

</body>

</html>
