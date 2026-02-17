<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registro - GreenBook</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen flex items-center justify-center bg-gray-100 backdrop-blur-sm">
  <div class="flex w-full max-w-5xl bg-white/30 backdrop-blur-sm border border-gray-200 rounded-2xl shadow-2xl overflow-hidden">

    {{-- Panel izquierdo --}}
    <div class="hidden md:flex w-1/2 bg-center bg-amber-50 relative" style="background-image: url('/images/logo_libro.svg');">
      <div class="absolute inset-0 bg-black/30"></div>
      <div class="relative z-10 flex flex-col justify-end text-white p-10">
        <h2 class="text-3xl text-center font-extrabold mb-4">GreenBook</h2>
        <p class="text-m font-bold text-white/90">Empieza tu descubrimiento y reservas desde aquí.</p>
      </div>
    </div>

    {{-- Panel derecho --}}
    <div class="w-full md:w-1/2 flex flex-col justify-center p-10 bg-white/70 backdrop-blur-sm text-white">
      <div class="max-w-sm w-full mx-auto">
        <img src="/images/logo_libro.svg" alt="Logo" class="mx-auto h-25 mb-10">
        <h2 class="text-2xl font-bold text-center text-black mb-8">Crear Cuenta</h2>

        <form action="{{ route('register') }}" method="POST" class="space-y-6">
          @csrf

          {{-- Correo electrónico --}}
          <div class="relative z-0 w-full mb-5 group">
            <input type="email" name="correo" id="correo"
              class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 
              border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-green-600 peer"
              placeholder="" required />
            <label for="correo"
              class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0]
              peer-focus:text-green-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
              Correo electrónico
            </label>
          </div>

          {{-- Contraseña --}}
          <div class="relative z-0 w-full mb-5 group">
            <input type="password" name="password" id="password"
              class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 
              border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-green-600 peer"
              placeholder="" required />
            <label for="password"
              class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0]
              peer-focus:text-green-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
              Contraseña
            </label>
          </div>

          {{-- Nombre y Apellido --}}
          <div class="grid md:grid-cols-2 md:gap-6">
            <div class="relative z-0 w-full mb-5 group">
              <input type="text" name="nombres" id="nombres"
                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 
                border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-green-600 peer"
                placeholder="" required />
              <label for="nombres"
                class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0]
                peer-focus:text-green-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                Nombre
              </label>
            </div>

            <div class="relative z-0 w-full mb-5 group">
              <input type="text" name="apellidos" id="apellidos"
                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 
                border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-green-600 peer"
                placeholder="" required />
              <label for="apellidos"
                class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0]
                peer-focus:text-green-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                Apellido
              </label>
            </div>
          </div>

          {{-- Teléfono y Dirección --}}
          <div class="grid md:grid-cols-2 md:gap-6">
            <div class="relative z-0 w-full mb-5 group">
              <input type="tel" name="telefono" id="telefono"
                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 
                border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-green-600 peer"
                placeholder="" />
              <label for="telefono"
                class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0]
                peer-focus:text-green-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                Teléfono
              </label>
            </div>

            <div class="relative z-0 w-full mb-5 group">
              <input type="text" name="direccion" id="direccion"
                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 
                border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-green-600 peer"
                placeholder="" />
              <label for="direccion"
                class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0]
                peer-focus:text-green-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                Dirección
              </label>
            </div>
          </div>

          {{-- Botón --}}
          <button type="submit"
            class="w-full py-2 bg-[#1F3F23] hover:bg-green-800 rounded-md font-semibold shadow-md transition-colors cursor-pointer">
            Registrarse
          </button>

          {{-- Errores --}}
          @if ($errors->any())
          <div class="opacity-0 translate-y-[-6px] animate-[fadeIn_0.4s_ease_forwards] text-red-400 flex items-center gap-2">
            <span>{{ $errors->first() }}</span>
          </div>
          @endif
        </form>

        {{-- Enlace a login --}}
        <p class="mt-6 text-center text-sm text-gray-400">
          ¿Ya tienes una cuenta?
          <a href="{{ route('login') }}" class="text-[#2F7139] hover:text-green-500 font-semibold">
            Inicia sesión
          </a>
        </p>
      </div>
    </div>
  </div>
</body>

</html>
