<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConsumoAlimento extends Model
{
    use SoftDeletes;

    protected $table = 'consumo_alimento';

    protected $fillable = [
        'alimento_id',
        'fecha',
        'cantidad_aves',
        'consumo_por_ave_gramos',
        'consumo_total_kg',
        'consumo_quintales',
        'tipo_ave',
        'referencia_id'
    ];

    protected $casts = [
        'fecha' => 'date',
        'consumo_por_ave_gramos' => 'decimal:2',
        'consumo_total_kg' => 'decimal:2',
        'consumo_quintales' => 'decimal:2',
    ];

    // Relaciones
    public function alimento()
    {
        return $this->belongsTo(Alimento::class);
    }

    // Métodos de negocio
    
    /**
     * RF-02: Calcula el consumo total en kg
     * Fórmula: (Número de aves × consumo_por_ave_gramos) / 1000
     */
    public static function calcularConsumoKg($cantidadAves, $consumoPorAveGramos)
    {
        return ($cantidadAves * $consumoPorAveGramos) / 1000;
    }

    /**
     * Calcula el consumo en quintales
     * Fórmula: consumo_kg / 46
     */
    public static function calcularConsumoQuintales($consumoKg)
    {
        return $consumoKg / Alimento::KG_POR_QUINTAL;
    }

    /**
     * Evento que se ejecuta antes de guardar
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($consumo) {
            // Calcular automáticamente el consumo total
            if ($consumo->cantidad_aves && $consumo->consumo_por_ave_gramos) {
                $consumo->consumo_total_kg = self::calcularConsumoKg(
                    $consumo->cantidad_aves,
                    $consumo->consumo_por_ave_gramos
                );
                $consumo->consumo_quintales = self::calcularConsumoQuintales($consumo->consumo_total_kg);
            }
        });

        static::created(function ($consumo) {
            // Descontar automáticamente del stock de alimento
            $consumo->alimento->descontarConsumo($consumo->consumo_total_kg);
        });
    }
}
