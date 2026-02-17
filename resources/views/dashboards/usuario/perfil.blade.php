<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Biblioteca - Mi Perfil</title>
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
        class="block px-3 py-2 rounded-md hover:bg-gray-200 {{ request()->routeIs('usuario.mis-prestamos') ? 'font-semibold text-green-600 bg-green-50' : 'text-gray-700' }}">Mis
        Préstamos</a>

      <a href="{{ route('usuario.mis-reservas') }}"
        class="block px-3 py-2 rounded-md hover:bg-gray-200 {{ request()->routeIs('usuario.mis-reservas') ? 'font-semibold text-green-600 bg-green-50' : 'text-gray-700' }}">Mis
        Reservas</a>

      <a href="{{ route('usuario.perfil') }}"
        class="block px-3 py-2 rounded-md hover:bg-gray-200 {{ request()->routeIs('usuario.perfil') ? 'font-semibold text-green-600 bg-green-50' : 'text-gray-700' }}">Mi
        Perfil</a>
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
        <button type="submit"
          class="w-full px-2 py-2 bg-gray-500 text-white rounded-md hover:bg-green-600 text-sm">Cerrar Sesión</button>
      </form>
    </div>
  </aside>

  <!-- Contenido principal -->
  <main class="flex-1 flex flex-col p-8 h-screen overflow-y-auto">
    <div class="mb-6">
      <h2 class="text-2xl font-bold text-gray-800">Mi Perfil</h2>
      <p class="text-sm text-gray-500">Información personal y configuración de cuenta</p>
    </div>

    <!-- Carnet de Usuario -->
    <div class="bg-white rounded-md shadow-lg max-w-3xl border-2 border-gray-300">

      <!-- Header del Carnet -->
      <div class="px-8 py-5 border-b-2 border-gray-300">
        <h3 class="text-gray-900 font-bold text-lg tracking-wide">CREDENCIAL DE USUARIO</h3>
        <p class="text-green-800 text-sm font-medium">GreenBook - Sistema de Biblioteca</p>
      </div>

      <!-- Contenido del Carnet -->
      <div class="p-8 flex gap-8">

        <!-- Foto del Usuario -->
        <div class="flex-shrink-0">
          <div class="w-40 h-48 rounded-md overflow-hidden border-2 border-gray-300">
            <img src="{{ asset('images/profile.png') }}" alt="Foto de perfil" class="w-full h-full object-cover">
          </div>
          <!-- Rol Badge -->
          {{-- <div class="mt-3 text-center">
            <span
              class="inline-block px-4 py-1 bg-gray-100 text-gray-800 text-xs font-semibold rounded-md uppercase tracking-wide border border-gray-300">
              {{ ucfirst($usuario->rol ?? 'Usuario') }}
            </span>
          </div> --}}
        </div>

        <!-- Datos del Usuario -->
        <div class="flex-1 grid grid-cols-1 gap-5 content-center">

          <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Nombre Completo</label>
            <p class="text-xl font-bold text-gray-900">{{ $usuario->nombres }} {{ $usuario->apellidos }}</p>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Correo</label>
              <p class="text-sm font-medium text-gray-800">{{ $usuario->correo }}</p>
            </div>

            <div>
              <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Teléfono</label>
              <p class="text-sm font-medium text-gray-800">{{ $usuario->telefono ?? 'No registrado' }}</p>
            </div>
          </div>

          <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Dirección</label>
            <p class="text-sm font-medium text-gray-800">{{ $usuario->direccion ?? 'No registrada' }}</p>
          </div>

          <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Miembro Desde</label>
            <p class="text-sm font-medium text-gray-800">
              {{ $usuario->created_at ? $usuario->created_at->format('d/m/Y') : '—' }}</p>
          </div>

        </div>
      </div>
    </div>
  </main>
</body>

</html>