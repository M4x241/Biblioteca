<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UsuarioFactory> */
    use HasFactory, Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'id';
    public $incrementing = true; // true si bigIncrements
    protected $keyType = 'int';
    public $timestamps = true; // según tu migration

    protected $fillable = [
        'nombres',
        'apellidos',
        'correo',
        'password',
        'rol',
        'estado',
        'telefono',
        'direccion',
        'estado'
    ];

    protected $hidden = ['password', 'remember_token'];

    public function prestamos()
    {
        return $this->hasMany(Prestamo::class, 'usuario_id', 'id');
    }

    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'usuario_id', 'id');
    }
    public function getAuthIdentifierName()
    {
        return 'id';
    }

    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function getRememberToken()
    {
        return $this->remember_token;
    }

    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    public function username()
    {
        return 'correo';
    }
    /**
     * Scope para filtrar solo usuarios con rol 'usuario'
     */

    public function scopeIsUsuario($query)
    {
        return $query->where('rol', 'usuario');
    }
}
