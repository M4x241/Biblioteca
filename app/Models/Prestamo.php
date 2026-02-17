<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prestamo extends Model
{
    /** @use HasFactory<\Database\Factories\PrestamoFactory> */
    use HasFactory;

    protected $table = 'prestamos';
    protected $primaryKey = 'id';
    public $timestamps = true;

        const STATUS_OCUPADO = 'ocupado';
        const STATUS_ATRASADO = 'atrasado';
        const STATUS_DEVUELTO = 'devuelto';

        protected $fillable = [
            'usuario_id','libro_id',
            'reserva_id',
            'fecha_prestamo',
            'fecha_devolucion',
            'estado',
            'extendido'
        ];

        public static function hasActiveLoanForBook($libroId)
        {
            return self::where('libro_id', $libroId)
                ->whereIn('estado', [self::STATUS_OCUPADO, self::STATUS_ATRASADO])
                ->exists();
        }

    protected $casts = [
        'fecha_prestamo' => 'datetime',
        'fecha_devolucion' => 'datetime',
        'extendido' => 'boolean',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id', 'id');
    }

    public function libro()
    {
        return $this->belongsTo(Libro::class, 'libro_id', 'id');
    }

    public function reserva()
    {
        return $this->belongsTo(Reserva::class, 'reserva_id', 'id');
    }
}
