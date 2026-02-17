<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Libro extends Model
{
    /** @use HasFactory<\Database\Factories\LibroFactory> */
    use HasFactory;

    protected $table = 'libros';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = ['titulo','imagen','autor','categoria','sinopsis'];

    public function prestamos()
    {
        return $this->hasMany(Prestamo::class, 'libro_id', 'id');
    }

    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'libro_id', 'id');
    }
}
