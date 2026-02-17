<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LibroController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\PrestamoController;
use App\Http\Controllers\BibliotecarioController;
use App\Http\Controllers\UsuarioDashboardController;
use App\Http\Controllers\GestionLibrosController;

/*
|--------------------------------------------------------------------------
| Rutas Web
|--------------------------------------------------------------------------
|
| Aquí se registran todas las rutas web del sistema. Estas rutas son
| cargadas por el RouteServiceProvider y contienen el middleware "web".
|
*/

//ruta directa a login
Route::get('/', function () {
    return redirect()->route('login.form');
});
use App\Http\Controllers\ReservaController;

// Route::get('/', function () {
//     return view('welcome');
// });

// (Login / Registro / Logout)

Route::get('/login', [LoginController::class, 'form'])->name('login.form');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [LoginController::class, 'registerForm'])->name('register.form');
Route::post('/register', [LoginController::class, 'register'])->name('register');


// Rutas principales del sistema
Route::resource('libros', LibroController::class);
Route::resource('usuarios', UsuarioController::class);
Route::resource('prestamos', PrestamoController::class);

// RUTAS PARA DASHBOARD DE BIBLIOTECARIO
Route::middleware(['auth', 'bibliotecario'])->group(function () {
    Route::get('/dashboard', [BibliotecarioController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/usuarios', [BibliotecarioController::class, 'usuarios'])->name('dashboard.usuarios');
    Route::get('/dashboard/prestamos', [BibliotecarioController::class, 'prestamos'])->name('dashboard.prestamos');
    Route::get('/dashboard/reservas', [App\Http\Controllers\ReservaController::class, 'index'])->name('dashboard.reservas');
    Route::get('/dashboard/todos-libros', [BibliotecarioController::class, 'libros'])->name('dashboard.todos-libros');
    Route::get('/dashboard/gestion-libros', [App\Http\Controllers\GestionLibrosController::class, 'index'])->name('dashboard.gestion-libros');
     Route::post('/reservas/{reserva}/convertir-prestamo', [GestionLibrosController::class, 'convertirAPrestamo'])->name('reservas.convertirAPrestamo');
});

// RUTAS PARA DASHBOARD DE USUARIO NORMAL
Route::middleware(['auth', 'usuario'])->group(function () {
    Route::get('/usuario/catalogo', [UsuarioDashboardController::class, 'catalogo'])->name('usuario.catalogo');
    Route::get('/usuario/mis-prestamos', [UsuarioDashboardController::class, 'misPrestamos'])->name('usuario.mis-prestamos');
    Route::get('/usuario/mis-reservas', [UsuarioDashboardController::class, 'misReservas'])->name('usuario.mis-reservas');
    Route::get('/usuario/perfil', [UsuarioDashboardController::class, 'perfil'])->name('usuario.perfil');
});




//rutas para reservas
Route::middleware('auth')->group(function () {
    Route::post('/reservas', [ReservaController::class, 'store'])->name('reservas.store');
    Route::post('/reservas/{id}/confirmar', [ReservaController::class, 'confirmar'])->name('reservas.confirmar');
    Route::post('/reservas/{reserva}/confirm', [ReservaController::class, 'confirm'])->name('reservas.confirm');
    Route::post('/reservas/{reserva}/cancelar', [ReservaController::class, 'cancelar'])->name('reservas.cancelar');
    Route::post('/reservas/{reserva}/update-estado', [ReservaController::class, 'updateEstado'])->name('reservas.updateEstado');

    // Ruta para extender préstamo 2 días
    Route::post('/prestamos/{prestamo}/extender', [App\Http\Controllers\PrestamoController::class, 'extend'])
        ->name('prestamos.extender');

});

// // Temporary helper route to fetch CSRF token for manual testing (remove in production)
// Route::get('/csrf-token', function () {
// 	return response()->json(['csrf_token' => csrf_token()]);
// });
