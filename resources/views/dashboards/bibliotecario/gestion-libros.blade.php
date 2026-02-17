<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca - Gestión de Libros</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gray-100 min-h-screen flex text-gray-900 font-sans" x-data="{ activeTab: 'prestamos', modalConvertir: false, selectedReserva: null }">

    <!-- Sidebar -->
    <aside class="fixed md:static inset-y-0 left-0 z-40 w-64 bg-white border-r flex flex-col h-screen overflow-y-auto">

        <!-- Logo -->
        @include('components.sidebar-logo')

        <!-- Buscador -->
        <div class="p-4 border-b">
            <form method="GET" action="{{ route('dashboard.gestion-libros') }}">
                <input type="text" name="q" value="{{ isset($q) ? e($q) : '' }}"
                    placeholder="Buscar libros o usuarios"
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
                    class="w-full px-2 py-2 bg-gray-500 text-white rounded-md hover:bg-green-600 transition-colors text-sm">
                    Cerrar Sesión
                </button>
            </form>
        </div>
    </aside>

    <!-- Contenido principal -->
    <main class="flex-1 flex flex-col p-4 md:p-8 h-screen overflow-y-auto">

        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <h2 class="text-2xl font-bold text-gray-800">Gestión de Libros</h2>
            <a href="{{ route('prestamos.create') }}"
                class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition-colors text-sm">
                Nuevo Préstamo
            </a>
        </div>

        <!-- Mensajes de éxito/error -->
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <!-- Tabs -->
        <div class="mb-6">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8">
                    <button @click="activeTab = 'prestamos'"
                        :class="activeTab === 'prestamos' ? 'border-green-500 text-green-600' :
                            'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                        Préstamos ({{ $prestamos->count() }})
                    </button>
                    <button @click="activeTab = 'reservas'"
                        :class="activeTab === 'reservas' ? 'border-green-500 text-green-600' :
                            'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                        Reservas ({{ $reservas->count() }})
                    </button>
                </nav>
            </div>
        </div>

        <!-- Contenido de Préstamos -->
        <div x-show="activeTab === 'prestamos'" class="bg-white rounded-lg shadow overflow-hidden flex-1 min-h-0">
            <div class="overflow-y-auto max-h-full">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 sticky top-0">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Libro</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Usuario</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha Préstamo
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha Devolución
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($prestamos as $prestamo)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <img src="{{ asset($prestamo->libro->imagen ?? 'images/libro-default.png') }}"
                                            alt="{{ $prestamo->libro->titulo }}"
                                            class="w-10 h-14 object-cover rounded mr-3">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $prestamo->libro->titulo }}</div>
                                            <div class="text-sm text-gray-500">{{ $prestamo->libro->autor }}</div>
                                        </div>
                                    </div>
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
                                {{-- class="absolute top-2 right-2 text-xs font-semibold text-white bg-red-700 rounded-sm px-3 py-1 shadow-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300"> --}}

                                <td class="px-6 py-4 whitespace-nowrap ">
                                    <span
                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded 
                                        @if ($prestamo->estado == 'devuelto') bg-gray-700 text-white
                                        @elseif($prestamo->estado == 'ocupado') bg-yellow-500 text-white
                                        @elseif($prestamo->estado == 'atrasado') bg-red-700 text-white @endif">
                                        {{ ucfirst($prestamo->estado) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('prestamos.show', $prestamo) }}"
                                        class="inline-flex items-center px-3 py-1 bg-indigo-700 text-white hover:bg-indigo-200 rounded ">Ver</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="font-medium">No hay préstamos registrados</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación de Préstamos -->
            @if (isset($totalPages) && $totalPages > 1)
                <div class="px-6 py-4 bg-gray-50 border-t flex items-center justify-between">
                    @if ($hasPrev)
                        <a href="{{ route('dashboard.gestion-libros', array_merge(['page' => $page - 1], isset($q) && $q !== '' ? ['q' => $q] : [])) }}"
                            class="px-4 py-2 bg-white border rounded text-sm text-gray-700 hover:bg-gray-50">Anterior</a>
                    @else
                        <span
                            class="px-4 py-2 bg-gray-100 border rounded text-sm text-gray-400 cursor-not-allowed">Anterior</span>
                    @endif

                    <span class="text-sm text-gray-600">Página {{ $page }} de {{ $totalPages }}</span>

                    @if ($hasNext)
                        <a href="{{ route('dashboard.gestion-libros', array_merge(['page' => $page + 1], isset($q) && $q !== '' ? ['q' => $q] : [])) }}"
                            class="px-4 py-2 bg-white border rounded text-sm text-gray-700 hover:bg-gray-50">Siguiente</a>
                    @else
                        <span
                            class="px-4 py-2 bg-gray-100 border rounded text-sm text-gray-400 cursor-not-allowed">Siguiente</span>
                    @endif
                </div>
            @endif
        </div>

        <!-- Contenido de Reservas -->
        <div x-show="activeTab === 'reservas'" x-cloak
            class="bg-white rounded-lg shadow overflow-hidden flex-1 min-h-0">
            <div class="overflow-y-auto max-h-full">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 sticky top-0">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Libro</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Usuario</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha Reserva
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($reservas as $reserva)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <img src="{{ asset($reserva->libro->imagen ?? 'images/libro-default.png') }}"
                                            alt="{{ $reserva->libro->titulo }}"
                                            class="w-10 h-14 object-cover rounded mr-3">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $reserva->libro->titulo }}</div>
                                            <div class="text-sm text-gray-500">{{ $reserva->libro->autor }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $reserva->usuario->nombres }} {{ $reserva->usuario->apellidos }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($reserva->created_at)->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($reserva->estado == 'activa')
                                        <form action="{{ route('reservas.updateEstado', $reserva) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            <select name="estado" onchange="this.form.submit()"
                                                class="px-2 py-1 border border-gray-300 rounded text-sm bg-blue-50">
                                                <option value="activa" selected>Activa</option>
                                                <option value="cancelada">Cancelada</option>
                                            </select>
                                        </form>
                                    @elseif($reserva->estado == 'cancelada')
                                        <span
                                            class="inline-flex items-center px-3 py-1 bg-red-700 text-white  rounded text-xs font-medium">
                                            Cancelada
                                        </span>
                                    @elseif($reserva->estado == 'vencida')
                                        <span
                                            class="inline-flex items-center px-3 py-1 bg-yellow-500 text-white  rounded text-xs font-medium">
                                            Vencida
                                        </span>
                                    @elseif($reserva->estado == 'completada')
                                        <span
                                            class="inline-flex items-center px-3 py-1 bg-gray-700 text-white rounded text-xs font-medium">
                                            Completada
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    @if ($reserva->estado == 'activa')
                                        <button @click="modalConvertir = true; selectedReserva = {{ $reserva->id }}"
                                            class="inline-flex items-center px-3 py-2 bg-indigo-700 text-white hover:bg-indigo-200 rounded transition-colors text-xs font-md">
                                            Convertir

                                        </button>
                                    @elseif($reserva->estado == 'completada')
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    <p class="font-medium">No hay reservas registradas</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    <!-- Modal Convertir a Préstamo -->
    <div x-show="modalConvertir" x-cloak @click.self="modalConvertir = false"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm p-4">

        <div @click.stop class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold">Convertir Reserva a Préstamo</h3>
                <button @click="modalConvertir = false" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <p class="text-sm text-gray-600 mb-4">Define la fecha de devolución del préstamo. La reserva se marcará
                como completada.</p>

            <form :action="`/reservas/${selectedReserva}/convertir-prestamo`" method="POST">
                @csrf

                <div class="mb-6">
                    <label for="fecha_devolucion" class="block text-sm font-medium text-gray-700 mb-2">
                        Fecha de Devolución
                    </label>
                    <input type="date" id="fecha_devolucion" name="fecha_devolucion"
                        min="{{ date('Y-m-d', strtotime('+1 day')) }}" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>

                <div class="flex gap-3 justify-end">
                    <button type="button" @click="modalConvertir = false"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 transition-colors">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700 transition-colors flex items-center">
                        Convertir
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

</body>

</html>
