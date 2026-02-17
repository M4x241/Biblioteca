<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Usuario;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear usuario bibliotecario por defecto
        Usuario::create([
            'nombres' => 'Luca',
            'apellidos' => 'Bracci',
            'correo' => 'luca@gmail.com',
            'password' => bcrypt('luca'),
            'rol' => 'bibliotecario',
            'telefono' => '12345678',
            'direccion' => 'Oficina Central',
            'estado' => 'activo'
        ]);

        // Crear usuario
        Usuario::create([
            'nombres' => 'Luciana',
            'apellidos' => 'Salinas',
            'correo' => 'lu@gmail.com',
            'password' => bcrypt('lu'),
            'rol' => 'usuario',
            'telefono' => '87654321',
            'direccion' => 'Ciudad',
            'estado' => 'activo'
        ]);
        Usuario::create([
            'nombres' => 'adamcho',
            'apellidos' => 'adamcho',
            'correo' => 'admin@gmail.com',
            'password' => bcrypt('adamcho'),
            'rol' => 'bibliotecario',
            'telefono' => '12345678',
            'direccion' => 'adamcho home',
            'estado' => 'activo'
        ]);

        // Crear usuario
        Usuario::create([
            'nombres' => 'adamcho',
            'apellidos' => 'adamcho',
            'correo' => 'adamcho@gmail.com',
            'password' => bcrypt('adamcho'),
            'rol' => 'usuario',
            'telefono' => '876524321',
            'direccion' => 'adamcho home',
            'estado' => 'activo'
        ]);

        Usuario::create([
            'nombres' => 'agustin',
            'apellidos' => 'loaiza',
            'correo' => 'agustin@gmail.com',
            'password' => bcrypt('agustin'),
            'rol' => 'usuario',
            'telefono' => '87654321',
            'direccion' => 'Ciudad',
            'estado' => 'bloqueado'
        ]);
    }
}
