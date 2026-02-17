<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Biblioteca - Mis Reservas</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <script src="https://cdn.tailwindcss.com"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gray-100 min-h-screen flex text-gray-900 font-sans" x-data="{ modalOpen: false, selectedReserva: {} }">

  <!-- Sidebar -->
  <aside class="w-64 bg-white border-r flex flex-col h-screen overflow-y-auto">
    
    <!-- Logo -->
    @include('components.sidebar-logo')

    <!-- Buscador -->
    <div class="p-4 border-b">
      <input type="text" placeholder="Buscar libros..." 
        class="w-full px-3 py-2 text-sm border rounded-md focus:outline-none focus:ring focus:ring-green-200 bg-white">
    </div>

    <!-- Navegación -->
    <nav class="flex-1 p-4 space-y-2 text-sm">
      <div class="text-gray-500 text-xs uppercase mb-2">MI BIBLIOTECA</div>

      <a href="{{ route('usuario.catalogo') }}" 
         class="block px-3 py-2 rounded-md hover:bg-gray-200 {{ request()->routeIs('usuario.catalogo') ? 'font-semibold text-green-600 bg-green-50' : 'text-gray-700' }}">Catálogo</a>

      <a href="{{ route('usuario.mis-prestamos') }}" 
         class="block px-3 py-2 rounded-md hover:bg-gray-200 {{ request()->routeIs('usuario.mis-prestamos') ? 'font-semibold text-green-600 bg-green-50' : 'text-gray-700' }}">Mis Préstamos</a>

      <a href="{{ route('usuario.mis-reservas') }}" 
         class="block px-3 py-2 rounded-md hover:bg-gray-200 {{ request()->routeIs('usuario.mis-reservas') ? 'font-semibold text-green-600 bg-green-50' : 'text-gray-700' }}">Mis Reservas</a>

      <a href="{{ route('usuario.perfil') }}" 
         class="block px-3 py-2 rounded-md hover:bg-gray-200 {{ request()->routeIs('usuario.perfil') ? 'font-semibold text-green-600 bg-green-50' : 'text-gray-700' }}">Mi Perfil</a> 
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
          <p class="text-sm text-gray-500 italic">Usuario</p>
        @endauth
      </div>
    </div>

    <!-- Botón de Cerrar Sesión -->
    <div class="p-4 border-t">
      <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="w-full px-2 py-2 bg-gray-500 text-white rounded-md hover:bg-green-600 text-sm">Cerrar Sesión</button>
      </form>
    </div>
  </aside>

  <!-- Contenido principal -->
  <main class="flex-1 flex flex-col p-8 h-screen overflow-y-auto">
    <div class="flex justify-between items-center mb-6">
      <div>
        <h2 class="text-2xl font-bold text-gray-800">Mis Reservas</h2>
        <p class="text-sm text-gray-500">Gestiona tus reservas de libros</p>
      </div>
      
      <!-- Resumen minimalista -->
      <div class="flex gap-6 text-sm">
        <div class="text-center">
          <p class="text-2xl font-bold text-gray-800">{{ $reservas->count() }}</p>
          <p class="text-xs text-gray-500 uppercase tracking-wide">Total</p>
        </div>
        <div class="text-center">
          <p class="text-2xl font-bold text-indigo-700">{{ $reservas->where('estado', 'activa')->count() }}</p>
          <p class="text-xs text-gray-500 uppercase tracking-wide">Activas</p>
        </div>
        <div class="text-center">
          <p class="text-2xl font-bold text-green-700">{{ $reservas->where('estado', 'completada')->count() }}</p>
          <p class="text-xs text-gray-500 uppercase tracking-wide">Completadas</p>
        </div>
      </div>
    </div>

    <!-- Mensajes de éxito/error -->
    @if(session('success'))
      <div class="mb-6 px-4 py-3 bg-green-100 border border-green-400 text-green-800 rounded-lg">
        {{ session('success') }}
      </div>
    @endif

    @if(session('error'))
      <div class="mb-6 px-4 py-3 bg-red-100 border border-red-400 text-red-800 rounded-lg">
        {{ session('error') }}
      </div>
    @endif

    <!-- Lista de Reservas -->
    @if($reservas->isEmpty())
      <div class="bg-white rounded-lg shadow-md p-12 text-center ">
        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
          <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
          </svg>
        </div>
        <p class="text-gray-600 text-lg font-medium mb-2">No tienes reservas registradas</p>
        <p class="text-gray-500">Cuando reserves un libro, aparecerá aquí</p>
      </div>
    @else
      <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5">
        @foreach($reservas as $reserva)
          <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 flex flex-col">
            <div class="h-64 overflow-hidden bg-gray-100">
              <img src="{{ asset($reserva->libro->imagen ?? 'images/libro-default.png') }}" 
                   alt="{{ $reserva->libro->titulo }}"
                   class="w-full h-full object-cover">
            </div>
            <div class="p-4 flex flex-col flex-grow">
              <h3 class="font-bold text-base text-gray-800 mb-1 line-clamp-2 min-h-[3rem]">{{ $reserva->libro->titulo }}</h3>
              <p class="text-xs text-gray-600 mb-3 line-clamp-1">{{ $reserva->libro->autor }}</p>
              
              <div class="space-y-1.5 mb-3">
                <div class="flex items-start text-xs text-gray-600">
                  <svg class="w-3.5 h-3.5 mr-1.5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                  </svg>
                  <span class="leading-tight">{{ \Carbon\Carbon::parse($reserva->fecha_reserva)->format('d/m/Y') }}</span>
                </div>
                <div class="flex items-start text-xs text-gray-600">
                  <svg class="w-3.5 h-3.5 mr-1.5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                  <span class="leading-tight">{{ \Carbon\Carbon::parse($reserva->fecha_expiracion)->format('d/m/Y') }}</span>
                </div>
              </div>

              <div class=" flex-col gap-2 mt-auto">
                @if($reserva->estado === 'activa')
                  <span class="inline-block px-2 py-1 text-xs font-semibold rounded-md bg-indigo-700 text-white text-center">
                    Activa
                  </span>
                  <button 
                    @click="modalOpen = true; selectedReserva = { id: {{ $reserva->id }}, titulo: '{{ addslashes($reserva->libro->titulo) }}', autor: '{{ addslashes($reserva->libro->autor) }}' }"
                    class="text-xs px-2 py-1.5 bg-gray-400 text-white rounded-md hover:bg-gray-600 font-medium">
                    Cancelar
                  </button>
                @elseif($reserva->estado === 'completada')
                  <span class="inline-block px-2 py-1 text-xs font-semibold rounded-md bg-gray-700 text-white text-center">
                    Completada
                  </span>
                @elseif($reserva->estado === 'cancelada')
                  <span class="inline-block px-2 py-1 text-xs font-semibold rounded-md bg-red-700 text-white text-center">
                    Cancelada
                  </span>
                @else
                  <span class="inline-block px-2 py-1 text-xs font-semibold rounded-md bg-yellow-500 text-white text-center">
                    Vencida
                  </span>
                @endif
              </div>
            </div>
          </div>
        @endforeach
      </div>
    @endif
  </main>

  <!-- Modal de Confirmación -->
  <div x-show="modalOpen" 
       x-cloak
       @click.away="modalOpen = false"
       class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4"
       style="display: none;">
    
    <div @click.stop
         class="bg-white rounded-lg shadow-2xl max-w-md w-full p-6 transform transition-all">
      
      <!-- Encabezado -->
      <div class="flex items-center justify-between mb-4">
        <h3 class="text-xl font-bold text-gray-800">Cancelar Reserva</h3>
        <button @click="modalOpen = false" 
                class="text-gray-400 hover:text-gray-600 ">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>
      </div>

      <!-- Icono de advertencia -->
      <div class="flex justify-center mb-4">
        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center">
          <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
          </svg>
        </div>
      </div>

      <!-- Contenido del modal -->
      <div class="mb-6 text-center">
        <p class="text-gray-700 mb-3">¿Estás seguro de cancelar la reserva del libro:</p>
        <p class="font-bold text-lg text-gray-900" x-text="selectedReserva.titulo"></p>
        <p class="text-sm text-gray-600" x-text="'por ' + selectedReserva.autor"></p>
        <p class="text-sm text-red-600 mt-3">Esta acción no se puede deshacer.</p>
      </div>

      <!-- Botones de acción -->
      <div class="flex gap-3">
        <button @click="modalOpen = false"
                class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 font-medium">
          No, mantener
        </button>
        <form :action="'/reservas/' + selectedReserva.id + '/cancelar'" method="POST" class="flex-1">
          @csrf
          <button type="submit"
                  class="w-full px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 font-medium">
            Sí, cancelar
          </button>
        </form>
      </div>
    </div>
  </div>

</body>
</html>