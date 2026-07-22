<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Alimento extends Model
{
    use SoftDeletes;

    protected $table = 'alimentos';

    protected $fillable = [
        'tipo',
        'quintales_stock',
        'kg_stock',
        'fecha_ultima_compra',
        'quintales_ultima_compra',
        'precio_quintal'
    ];

    protected $casts = [
        'fecha_ultima_compra' => 'date',
        'quintales_stock' => 'decimal:2',
        'kg_stock' => 'decimal:2',
        'quintales_ultima_compra' => 'decimal:2',
        'precio_quintal' => 'decimal:2',
    ];

    // Relaciones
    public function consumos()
    {
        return $this->hasMany(ConsumoAlimento::class);
    }

    // Métodos de negocio
    
    /**
     * RF-02: Conversión de quintales a kilogramos
     * 1 quintal = 46 kg
     */
    const KG_POR_QUINTAL = 46;

    /**
     * Alerta cuando quedan menos de 2 quintales
     */
    const ALERTA_MINIMA_QUINTALES = 2;

    /**
     * Convierte quintales a kilogramos
     */
    public static function quintalesAKg($quintales)
    {
        return $quintales * self::KG_POR_QUINTAL;
    }

    /**
     * Convierte kilogramos a quintales
     */
    public static function kgAQuintales($kg)
    {
        return $kg / self::KG_POR_QUINTAL;
    }

    /**
     * Verifica si el stock está por debajo del nivel de alerta
     */
    public function getTieneAlertaStockAttribute()
    {
        return $this->quintales_stock < self::ALERTA_MINIMA_QUINTALES;
    }

    /**
     * Registra una compra de alimento
     */
    public function registrarCompra($quintales, $precioQuintal)
    {
        $this->quintales_stock += $quintales;
        $this->kg_stock = self::quintalesAKg($this->quintales_stock);
        $this->fecha_ultima_compra = now();
        $this->quintales_ultima_compra = $quintales;
        $this->precio_quintal = $precioQuintal;
        $this->save();
    }

    /**
     * Descuenta consumo del stock
     */
    public function descontarConsumo($kg)
    {
        $this->kg_stock -= $kg;
        $this->quintales_stock = self::kgAQuintales($this->kg_stock);
        $this->save();
    }

    /**
     * Evento que se ejecuta antes de guardar
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($alimento) {
            // Sincronizar quintales y kg
            if ($alimento->isDirty('quintales_stock')) {
                $alimento->kg_stock = self::quintalesAKg($alimento->quintales_stock);
            } elseif ($alimento->isDirty('kg_stock')) {
                $alimento->quintales_stock = self::kgAQuintales($alimento->kg_stock);
            }
        });
    }
}
