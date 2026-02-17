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
        Schema::create('prestamos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('usuario_id');
            $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
            $table->unsignedBigInteger('libro_id');
            $table->foreign('libro_id')->references('id')->on('libros')->onDelete('cascade');
            $table->unsignedBigInteger('reserva_id')->nullable();
            $table->foreign('reserva_id')->references('id')->on('reservas')->nullOnDelete();
            $table->timestamp('fecha_prestamo')->useCurrent();
            $table->timestamp('fecha_devolucion')->nullable();
            $table->enum('estado', ['devuelto','ocupado','atrasado'])->default('devuelto');
            $table->boolean('extendido')->default(false);
            $table->timestamps();
        });
    }

    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestamos');
    }
};
