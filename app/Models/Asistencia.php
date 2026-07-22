<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asistencia extends Model
{
    use SoftDeletes;

    protected $table = 'asistencias';

    protected $fillable = [
        'operario_id',
        'fecha',
        'asistio',
        'pago_dia',
        'observaciones'
    ];

    protected $casts = [
        'fecha' => 'date',
        'asistio' => 'boolean',
        'pago_dia' => 'decimal:2',
    ];

    // Relaciones
    public function operario()
    {
        return $this->belongsTo(Operario::class);
    }

    // Métodos de negocio
    
    /**
     * Evento que se ejecuta antes de guardar
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($asistencia) {
            // Asignar automáticamente el pago del día si asistió
            if ($asistencia->asistio && !$asistencia->pago_dia) {
                $asistencia->pago_dia = Operario::PAGO_DIARIO;
            } elseif (!$asistencia->asistio) {
                $asistencia->pago_dia = 0;
            }
        });
    }
}
