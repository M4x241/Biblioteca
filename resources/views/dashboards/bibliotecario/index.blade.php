<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca - Mis libros</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gray-100 min-h-screen flex text-gray-900 font-sans" x-data="{ modalOpen: false, selectedBook: {} }">

  <!-- Sidebar -->
  <aside class="w-64 bg-white border-r flex flex-col h-screen overflow-y-auto">
    
    <!-- Logo -->
    @include('components.sidebar-logo')

        <!-- Buscador -->
        <div class="p-4 border-b">
            <form method="GET" action="{{ route('dashboard') }}" class="space-y-2">
                <input type="text" name="q" value="{{ isset($q) ? e($q) : '' }}"
                    placeholder="Buscar por título o autor"
                    class="w-full px-3 py-2 text-sm border rounded-md focus:outline-none focus:ring focus:ring-green-200">

                <select name="genero" onchange="this.form.submit()"
                    class="w-full px-3 py-2 text-sm border rounded-md focus:outline-none focus:ring focus:ring-green-200 bg-white">
                    <option value="">Filtrar por genero</option>
                    @foreach ($generos as $g)
                        <option value="{{ $g }}" @selected($genero === $g)>{{ $g }}</option>
                    @endforeach
                </select>
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
    <main class="flex-1 flex flex-col items-center p-5 h-screen overflow-y-auto">
        <!-- RESUMEN DE RESERVAS PENDIENTES -->
        <div class="w-full max-w-5xl px-8 mb-8">
            <div class="bg-white rounded shadow p-4 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold">Reservas pendientes</h3>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('dashboard.gestion-libros') }}"
                        class="px-3 py-1.5 bg-green-600 text-white rounded text-sm">Ver todas</a>
                </div>
            </div>
        </div>

        <!-- GRID DE LIBROS 4x2 -->
        <div class="w-full max-w-5xl px-8">
            <div class="grid grid-cols-4 gap-8">
                @forelse($libros->take(8) as $libro)
                    <!-- Tarjeta de libro minimalista -->
                    <div class="group cursor-pointer transform hover:scale-105 transition-all duration-300"
                        @click="modalOpen = true; selectedBook = {
                        titulo: '{{ addslashes($libro->titulo) }}',
                        autor: '{{ addslashes($libro->autor ?? 'Sin autor') }}',
                        categoria: '{{ addslashes($libro->categoria ?? 'Sin categoría') }}',
                        sinopsis: '{{ addslashes($libro->sinopsis ?? 'Sin sinopsis disponible') }}',
                        imagen: '{{ asset($libro->imagen ?? 'images/libro-default.png') }}',
                        id: {{ $libro->id }},
                        ocupado: {{ in_array($libro->id, $librosPrestados) ? 'true' : 'false' }},
                        reservado: {{ in_array($libro->id, $librosReservados) ? 'true' : 'false' }}
                      }">
                        <div class="bg-white rounded-sm shadow-md overflow-hidden h-full hover:shadow-xl transition-all duration-300">
                            <!-- Contenedor de imagen con estado superpuesto -->
                            <div class="relative">
                                <img src="{{ asset($libro->imagen ?? 'images/libro-default.png') }}"
                                    alt="{{ $libro->titulo }}" class="w-full h-64 object-cover">

                                {{-- Estado del libro - Solo visible en hover --}}
                                @if (in_array($libro->id, $librosPrestados))
                                    <div
                                        class="absolute top-2 right-2 text-xs font-semibold text-white bg-red-700 rounded-sm px-3 py-1 shadow-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        Ocupado
                                    </div>
                                @elseif(in_array($libro->id, $librosReservados))
                                    <div
                                        class="absolute top-2 right-2 text-xs font-semibold text-white bg-yellow-700 rounded-sm px-3 py-1 shadow-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        Reservado
                                    </div>
                                @else
                                    <div
                                        class="absolute top-2 right-2 text-xs font-semibold text-white bg-green-700 rounded-sm px-3 py-1 shadow-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        Disponible
                                    </div>
                                @endif
                            </div>

                            <!-- Solo título -->
                            <div class="p-4">
                                <h3 class="font-semibold text-sm text-center text-gray-800 line-clamp-2">
                                    {{ $libro->titulo }}
                                </h3>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-4 text-center py-12">
                        <div class="text-gray-500 text-lg">
                            <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477
                  5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5
                  c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18
                      c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                <p>No hay libros disponibles en la biblioteca.</p>
                            </div>
                        </div>
                @endforelse
            </div>
        </div>


        <!-- Paginación -->
        @if (isset($totalPages) && $totalPages > 1)
            <div class="mt-8 flex items-center justify-center space-x-4">

                <!-- Botón Anterior -->
                @if ($hasPrev)
                    <a href="{{ route('dashboard', array_merge(['page' => $page - 1], isset($q) && $q !== '' ? ['q' => $q] : [])) }}"
                        class="flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm 
                    text-sm font-medium text-gray-700 hover:bg-gray-50 hover:border-gray-400 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Anterior
                    </a>
                @else
                    <span
                        class="flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md text-sm 
                        font-medium text-gray-400 cursor-not-allowed">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 19l-7-7 7-7" />
                        </svg>
                        Anterior
                    </span>
                @endif

                <!-- Info de página -->
                <span class="px-4 py-2 text-sm text-gray-600 bg-gray-50 rounded-md border">
                    Página {{ $page }} de {{ $totalPages }}
                </span>

                <!-- Botón Siguiente -->
                @if ($hasNext)
                    <a href="{{ route('dashboard', array_merge(['page' => $page + 1], isset($q) && $q !== '' ? ['q' => $q] : [])) }}"
                        class="flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm 
                    text-sm font-medium text-gray-700 hover:bg-gray-50 hover:border-gray-400 transition-colors duration-200">
                        Siguiente
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                @else
                    <span
                        class="flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md 
                        text-sm font-medium text-gray-400 cursor-not-allowed">
                        Siguiente
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </span>
                @endif
            </div>
        @endif
    </main>

    <!-- Modal -->
    <div x-show="modalOpen" x-cloak @click.self="modalOpen = false"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm"
        style="display: none;">

        <div @click.stop class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 overflow-hidden">

            <!-- Header del Modal -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                <h3 class="text-xl font-semibold text-gray-900" x-text="selectedBook.titulo"></h3>
                <button @click="modalOpen = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Contenido del Modal -->
            <div class="p-6">
                <div class="flex gap-6">
                    <!-- Imagen del libro -->
                    <div class="flex-shrink-0">
                        <img :src="selectedBook.imagen" :alt="selectedBook.titulo"
                            class="w-40 h-56 object-cover rounded-lg border border-gray-200">
                    </div>

                    <!-- Información del libro -->
                    <div class="flex-1 space-y-4">

                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase mb-1">Autor</p>
                            <p class="text-base text-gray-900" x-text="selectedBook.autor"></p>
                        </div>

                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase mb-1">Categoría</p>
                            <span class="inline-flex px-3 py-1 text-sm rounded bg-gray-100 text-gray-800"
                                x-text="selectedBook.categoria"></span>
                        </div>

                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase mb-1">Sinopsis</p>
                            <p class="text-sm text-gray-700 leading-relaxed" x-text="selectedBook.sinopsis"></p>
                        </div>

                        <!-- Estado del libro -->
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase mb-1">Estado</p>
                            <template x-if="selectedBook.ocupado">
                                <span class="inline-flex px-3 py-1 text-sm rounded bg-red-100 text-red-800">
                                    Ocupado
                                </span>
                            </template>
                            <template x-if="selectedBook.reservado && !selectedBook.ocupado">
                                <span class="inline-flex px-3 py-1 text-sm rounded bg-yellow-100 text-yellow-800">
                                    Reservado
                                </span>
                            </template>
                            <template x-if="!selectedBook.ocupado && !selectedBook.reservado">
                                <span class="inline-flex px-3 py-1 text-sm rounded bg-green-100 text-green-800">
                                    Disponible
                                </span>
                            </template>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Footer del Modal -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex gap-3 justify-end">
                <button @click="modalOpen = false"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                    Cerrar
                </button>

                <!-- Botón para editar (solo para bibliotecarios) -->
                <a :href="`{{ url('libros') }}/${selectedBook.id}/edit`"
                    class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700 transition-colors">
                    Editar Libro
                </a>
            </div>

        </div>
    </div>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

</body>

</html>
