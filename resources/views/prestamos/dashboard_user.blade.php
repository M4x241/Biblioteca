<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Biblioteca - Mis libros</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 h-screen flex text-gray-900 font-sans">

  <aside class="w-64 bg-white border-r flex flex-col">
    <!-- Logo / Encabezado -->
    @include('components.sidebar-logo')

    <!-- Buscador -->
    <div class="p-4 border-b">
      <input type="text" placeholder="Buscar" 
        class="w-full px-3 py-2 text-sm border rounded-md focus:outline-none focus:ring focus:ring-indigo-200">
    </div>

    <!-- Navegación -->
    <nav class="flex-1 p-4 space-y-2 text-sm">
      <div class="text-gray-500 text-xs uppercase mb-2">MI BIBLIOTECA</div>
      <a href="{{ route('dashboard.user') }}" class="block px-3 py-2 rounded-md hover:bg-gray-200 font-semibold text-indigo-600 bg-indigo-50">Todos los libros</a>
      <a href="#reservas" class="block px-3 py-2 rounded-md hover:bg-gray-200">Mis Reservas</a>

    </nav>

    <!-- Perfil -->
<div class="p-4 border-t flex items-center gap-3">
  <img src="../images/profile.png" alt="Perfil" class="w-8 h-8 rounded-full">
  <div>
@auth
  <p class="text-sm font-medium text-gray-800">
    {{ $usuario->nombres }} {{ $usuario->apellidos }}
  </p>
  <p class="text-xs text-gray-500">
    {{ $usuario->correo }}
  </p>
@else
  <p class="text-sm text-gray-500 italic">Registrarse</p>
@endauth
</div>

  </aside>

  <main class="flex-1 flex flex-col items-center justify-center p-12">
    <h2 class="text-2xl font-bold mb-12 text-center text-gray-800">Libros</h2>

    <!-- GRID DE LIBROS 4x2 CENTRADO -->
    <div class="w-full max-w-5xl px-8">
      <div class="grid grid-cols-4 gap-8">
        @forelse($libros as $libro)
        <!-- Tarjeta de libro -->
        <div class="group cursor-pointer transform hover:scale-105 transition-all duration-300">
          <div class="bg-white rounded-md shadow-md overflow-hidden h-full">
            <img src="{{ $libro->imagen ?? 'https://via.placeholder.com/150x200/e5e7eb/6b7280?text=Sin+Imagen' }}" 
                 alt="{{ $libro->titulo }}" 
                 class="w-full h-48 object-cover">
            <div class="p-3">
              <h3 class="font-medium text-sm mb-1 text-gray-800 truncate">{{ $libro->titulo }}</h3>
              <p class="text-xs text-gray-600 mb-1 truncate">{{ $libro->autor }}</p>
              <p class="text-xs text-gray-500 bg-gray-100 rounded-full px-2 py-1 inline-block">{{ $libro->categoria }}</p>
            </div>
          </div>
        </div>
        @empty
        <div class="col-span-4 text-center py-12">
          <div class="text-gray-500 text-lg">
            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
            <p>No hay libros disponibles en la biblioteca.</p>
          </div>
        </div>
        @endforelse
      </div>
    </div>

    <!-- NAVEGACIÓN DE PÁGINAS -->
    @if(isset($totalPages) && $totalPages > 1)
    <div class="mt-8 flex items-center justify-center space-x-4">
      
      <!-- Botón Anterior -->
      @if($hasPrev)
        <a href="{{ route('dashboard.user', ['page' => $page - 1]) }}" 
           class="flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 hover:border-gray-400 transition-colors duration-200">
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
          Anterior
        </a>
      @else
        <span class="flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md text-sm font-medium text-gray-400 cursor-not-allowed">
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
          Anterior
        </span>
      @endif

      <!-- Información de página -->
      <span class="px-4 py-2 text-sm text-gray-600 bg-gray-50 rounded-md border">
        Página {{ $page }} de {{ $totalPages }}
      </span>

      <!-- Botón Siguiente -->
      @if($hasNext)
        <a href="{{ route('dashboard.user', ['page' => $page + 1]) }}" 
           class="flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 hover:border-gray-400 transition-colors duration-200">
          Siguiente
          <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </a>
      @else
        <span class="flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md text-sm font-medium text-gray-400 cursor-not-allowed">
          Siguiente
          <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </span>
      @endif
      
    </div>
    @endif

    <!-- MIS RESERVAS -->
    <div id="reservas" class="mt-16 w-full max-w-5xl px-8">
      <h3 class="text-xl font-bold mb-6 text-gray-800">Mis Reservas Activas</h3>
      
      @if($reservas->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          @foreach($reservas as $reserva)
            <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-blue-500">
              <div class="flex items-start space-x-3">
                <img src="{{ $reserva->libro->imagen ?? 'https://via.placeholder.com/60x80/e5e7eb/6b7280?text=Sin+Imagen' }}" 
                     alt="{{ $reserva->libro->titulo }}" 
                     class="w-12 h-16 object-cover rounded">
                <div class="flex-1">
                  <h4 class="font-medium text-sm text-gray-900 truncate">{{ $reserva->libro->titulo }}</h4>
                  <p class="text-xs text-gray-600 truncate">{{ $reserva->libro->autor }}</p>
                  <div class="mt-2 flex items-center justify-between">
                    <span class="px-2 py-1 text-xs rounded-full
                      {{ $reserva->estado === 'pendiente' ? 'bg-yellow-100 text-yellow-800' : 
                         ($reserva->estado === 'confirmada' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                      {{ ucfirst($reserva->estado) }}
                    </span>
                    <span class="text-xs text-gray-500">{{ $reserva->fecha_reserva->format('d/m/Y') }}</span>
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      @else
        <div class="text-center py-8">
          <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
          </svg>
          <p class="text-gray-500">No tienes reservas activas.</p>
        </div>
      @endif
    </div>

  </main>

</body>
</html>