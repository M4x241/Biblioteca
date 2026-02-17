<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Biblioteca - Mis Préstamos</title>
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
  <main class="flex-1  flex-col p-8 h-screen overflow-y-auto">
    <div class="flex justify-between items-center mb-6">
      <div>
        <h2 class="text-2xl font-bold text-gray-800">Mis Préstamos</h2>
        <p class="text-sm text-gray-500">Gestiona tus libros prestados</p>
      </div>
      
      <!-- Resumen minimalista -->
      <div class="flex gap-6 text-sm">
        <div class="text-center">
          <p class="text-2xl font-bold text-gray-800">{{ $prestamos->count() }}</p>
          <p class="text-xs text-gray-500 uppercase tracking-wide">Total</p>
        </div>
        <div class="text-center">
          <p class="text-2xl font-bold text-green-600">{{ $prestamos->where('estado', 'ocupado')->count() }}</p>
          <p class="text-xs text-gray-500 uppercase tracking-wide">Activos</p>
        </div>
        <div class="text-center">
          <p class="text-2xl font-bold text-red-600">{{ $prestamos->where('estado', 'atrasado')->count() }}</p>
          <p class="text-xs text-gray-500 uppercase tracking-wide">Atrasados</p>
        </div>
      </div>
    </div>

    <!-- Lista de Préstamos -->
    @if($prestamos->isEmpty())
      <div class="bg-white rounded-lg shadow-md p-12 text-center ">
        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
          <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
          </svg>
        </div>
        <p class="text-gray-600 text-lg font-medium mb-2">No tienes préstamos registrados</p>
        <p class="text-gray-500">Cuando solicites un libro, aparecerá aquí</p>
      </div>
    @else
      <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5">
        @foreach($prestamos as $prestamo)
          <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 flex flex-col">
            <div class="h-64 overflow-hidden bg-gray-100">
              <img src="{{ asset($prestamo->libro->imagen ?? 'images/libro-default.png') }}" 
                   alt="{{ $prestamo->libro->titulo }}"
                   class="w-full h-full object-cover">
            </div>
            <div class="p-4 flex flex-col flex-grow">
              <h3 class="font-bold text-base text-gray-800 mb-1 line-clamp-2 min-h-[3rem]">{{ $prestamo->libro->titulo }}</h3>
              <p class="text-xs text-gray-600 mb-3 line-clamp-1">{{ $prestamo->libro->autor }}</p>
              
              <div class="space-y-1.5 mb-3">
                <div class="flex items-start text-xs text-gray-600">
                  <svg class="w-3.5 h-3.5 mr-1.5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                  </svg>
                  <span class="leading-tight">{{ \Carbon\Carbon::parse($prestamo->fecha_prestamo)->format('d/m/Y') }}</span>
                </div>
                <div class="flex items-start text-xs text-gray-600">
                  <svg class="w-3.5 h-3.5 mr-1.5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                  </svg>
                  <span class="leading-tight">{{ \Carbon\Carbon::parse($prestamo->fecha_devolucion)->format('d/m/Y') }}</span>
                </div>
              </div>

              @php
                $isMine = auth()->check() && auth()->id() === $prestamo->usuario_id;
                $now = \Carbon\Carbon::now();
              @endphp

              <div class=" flex-col gap-2 mt-auto">
                {{-- Estado: Devuelto --}}
                @if($prestamo->estado === \App\Models\Prestamo::STATUS_DEVUELTO)
                  <span class="inline-block px-2 py-1 text-xs font-semibold rounded-md bg-gray-700 text-white text-center">
                    Devuelto
                  </span>

                {{-- Estado: Atrasado --}}
                @elseif($prestamo->fecha_devolucion && $prestamo->fecha_devolucion->lt($now))
                  <span class="inline-block px-2 py-1 text-xs font-semibold rounded-md bg-red-700 text-white text-center">
                    Atrasado
                  </span>

                {{-- Estado: En préstamo --}}
                @elseif($prestamo->estado === \App\Models\Prestamo::STATUS_OCUPADO)
                  <span class="inline-block px-2 py-1 text-xs font-semibold rounded-md bg-green-700 text-white text-center">
                    En Préstamo
                  </span>

                  <!-- Botón para extender 2 días -->
                  @if(!$prestamo->extendido)
                    <form action="{{ route('prestamos.extender', $prestamo) }}" method="POST" class="mt-3">
                      @csrf
                      <button type="submit" class="w-full text-xs px-3 py-2 bg-indigo-700 text-white rounded-md hover:bg-indigo-800 font-medium transition-colors">
                        Extender 2 días
                      </button>
                    </form>
                  @else
                    <span class="inline-block px-2 py-1 text-xs font-medium text-gray-500 text-center bg-gray-50 rounded-md mt-3">Extensión usada</span>
                  @endif

                {{-- Por defecto --}}
                @else
                  <span class="inline-block px-2 py-1 text-xs font-semibold rounded-md bg-green-50 text-green-800 text-center">
                    Disponible
                  </span>
                @endif
              </div>
            </div>
          </div>
        @endforeach
      </div>
    @endif
  </main>

</body>
</html>