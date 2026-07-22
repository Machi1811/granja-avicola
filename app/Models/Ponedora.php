<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ponedora extends Model
{
    use SoftDeletes;

    protected $table = 'ponedoras';

    protected $fillable = [
        'galpon_id',
        'cantidad_inicial',
        'cantidad_actual',
        'fecha_ingreso',
        'estado'
    ];

    protected $casts = [
        'fecha_ingreso' => 'date',
    ];

    // Relaciones
    public function galpon()
    {
        return $this->belongsTo(Galpon::class);
    }

    // Métodos de negocio
    
    /**
     * RF-02: Consumo de alimento por ponedora = 120 gramos diarios
     */
    const CONSUMO_DIARIO_GRAMOS = 120;

    /**
     * Calcula el consumo diario total en kilogramos
     */
    public function getConsumoDiarioKgAttribute()
    {
        return ($this->cantidad_actual * self::CONSUMO_DIARIO_GRAMOS) / 1000;
    }

    /**
     * Calcula el consumo diario en quintales (1 quintal = 46 kg)
     */
    public function getConsumoDiarioQuintalesAttribute()
    {
        return $this->consumo_diario_kg / 46;
    }
}
