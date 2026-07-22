<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Venta extends Model
{
    use SoftDeletes;

    protected $table = 'ventas';

    protected $fillable = [
        'fecha',
        'tipo_producto',
        'cantidad',
        'unidad_medida',
        'precio_unitario',
        'total',
        'referencia_id',
        'cliente',
        'observaciones'
    ];

    protected $casts = [
        'fecha' => 'date',
        'precio_unitario' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    // Métodos de negocio
    
    /**
     * RF-03: Tarificador de Venta de Aves Ponedoras
     * 1 a 12 gallinas: S/. 35.00 c/u
     * Más de 12 gallinas: S/. 30.00 c/u
     */
    const PRECIO_PONEDORA_HASTA_DOCENA = 35.00;
    const PRECIO_PONEDORA_MAS_DOCENA = 30.00;
    const LIMITE_DOCENA = 12;

    /**
     * Calcula el precio unitario según la cantidad de ponedoras
     */
    public static function calcularPrecioPonedora($cantidad)
    {
        if ($cantidad > self::LIMITE_DOCENA) {
            return self::PRECIO_PONEDORA_MAS_DOCENA;
        }
        return self::PRECIO_PONEDORA_HASTA_DOCENA;
    }

    /**
     * Calcula el total de la venta
     */
    public static function calcularTotal($cantidad, $precioUnitario)
    {
        return $cantidad * $precioUnitario;
    }

    /**
     * Evento que se ejecuta antes de guardar
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($venta) {
            // Si es venta de ponedora viva y no tiene precio, calcularlo automáticamente
            if ($venta->tipo_producto === 'ponedora_viva' && !$venta->precio_unitario) {
                $venta->precio_unitario = self::calcularPrecioPonedora($venta->cantidad);
            }
            
            // Calcular el total automáticamente
            if ($venta->cantidad && $venta->precio_unitario) {
                $venta->total = self::calcularTotal($venta->cantidad, $venta->precio_unitario);
            }
        });
    }

    /**
     * Obtiene la descripción legible del tipo de producto
     */
    public function getTipoProductoTextoAttribute()
    {
        $tipos = [
            'ponedora_viva' => 'Gallina Ponedora Viva',
            'huevos' => 'Huevos',
            'pollo_engorde' => 'Pollo de Engorde',
            'carne_gallina' => 'Carne de Gallina (Descarte)'
        ];
        
        return $tipos[$this->tipo_producto] ?? $this->tipo_producto;
    }
}
