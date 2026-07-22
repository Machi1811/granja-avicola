<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Operario extends Model
{
    use SoftDeletes;

    protected $table = 'operarios';

    protected $fillable = [
        'nombre',
        'apellido',
        'dni',
        'telefono',
        'pago_diario',
        'estado',
        'fecha_ingreso'
    ];

    protected $casts = [
        'fecha_ingreso' => 'date',
        'pago_diario' => 'decimal:2',
    ];

    // Relaciones
    public function asistencias()
    {
        return $this->hasMany(Asistencia::class);
    }

    // Métodos de negocio
    
    /**
     * RF-06: Pago diario fijo de S/. 80.00
     */
    const PAGO_DIARIO = 80.00;

    /**
     * Obtiene el nombre completo del operario
     */
    public function getNombreCompletoAttribute()
    {
        return "{$this->nombre} {$this->apellido}";
    }

    /**
     * Calcula el pago total por período
     */
    public function calcularPagoTotal($fechaInicio, $fechaFin)
    {
        $diasTrabajados = $this->asistencias()
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->where('asistio', true)
            ->count();
            
        return $diasTrabajados * self::PAGO_DIARIO;
    }

    /**
     * Obtiene los días trabajados en un período
     */
    public function diasTrabajados($fechaInicio, $fechaFin)
    {
        return $this->asistencias()
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->where('asistio', true)
            ->count();
    }
}
