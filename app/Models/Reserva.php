<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    /** @use HasFactory<\Database\Factories\ReservaFactory> */
    use HasFactory;

    const STATUS_CONFIRMED = 'activa';
    const STATUS_EXPIRED = 'vencida';
    const STATUS_CANCELLED = 'cancelada';
    const STATUS_COMPLETED = 'completada';

    protected $table = 'reservas';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = [
        'usuario_id',
        'libro_id', 'fecha_reserva',
        'fecha_vencimiento',
        'estado'
    ];
    protected $dates = ['fecha_reserva','fecha_vencimiento'];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id', 'id');
    }

    public function libro()
    {
        return $this->belongsTo(Libro::class, 'libro_id', 'id');
    }

    public function markConfirmed()
    {
        $this->estado = self::STATUS_CONFIRMED;
        $this->save();
    }

    public function markExpired()
    {
        $this->estado = self::STATUS_EXPIRED;
        $this->save();
    }

    public function markCancelled()
    {
        $this->estado = self::STATUS_CANCELLED;
        $this->save();
    }

    public function markCompleted()
    {
        $this->estado = self::STATUS_COMPLETED;
        $this->save();
    }
}
