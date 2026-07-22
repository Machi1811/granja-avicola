<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProduccionDiaria extends Model
{
    use SoftDeletes;

    protected $table = 'produccion_diaria';

    protected $fillable = [
        'galpon_id',
        'fecha',
        'aves_activas',
        'produccion_teorica',
        'porcentaje_merma',
        'produccion_neta',
        'produccion_real',
        'observaciones'
    ];

    protected $casts = [
        'fecha' => 'date',
        'porcentaje_merma' => 'decimal:2',
    ];

    // Relaciones
    public function galpon()
    {
        return $this->belongsTo(Galpon::class);
    }

    // Métodos de negocio
    
    /**
     * RF-01: Cálculo Automático de Merma (10% fijo)
     * La producción neta es el 90% de la producción teórica
     */
    const PORCENTAJE_MERMA = 10;

    /**
     * Calcula automáticamente la producción neta (restando la merma del 10%)
     */
    public static function calcularProduccionNeta($produccionTeorica)
    {
        return round($produccionTeorica * (100 - self::PORCENTAJE_MERMA) / 100);
    }

    /**
     * Evento que se ejecuta antes de guardar
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($produccion) {
            // Si se establece la producción teórica, calcular automáticamente la neta
            if ($produccion->produccion_teorica) {
                $produccion->produccion_neta = self::calcularProduccionNeta($produccion->produccion_teorica);
            }
            
            // Asegurar que el porcentaje de merma siempre sea 10%
            $produccion->porcentaje_merma = self::PORCENTAJE_MERMA;
        });
    }

    /**
     * Obtiene la diferencia entre producción neta esperada y real
     */
    public function getDiferenciaProduccionAttribute()
    {
        if ($this->produccion_real === null) {
            return null;
        }
        return $this->produccion_real - $this->produccion_neta;
    }
}
