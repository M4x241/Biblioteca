<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombres');
            $table->string('apellidos')->nullable();
            $table->string('correo')->unique();
            $table->string('password');
            $table->enum('rol', ['usuario', 'bibliotecario'])->default('usuario');
            $table->string('telefono')->nullable();
            $table->text('direccion')->nullable();
            $table->enum('estado', ['bloqueado', 'activo'])->default('activo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
