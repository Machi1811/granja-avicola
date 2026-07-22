<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class PolloEngorde extends Model
{
    use SoftDeletes;

    protected $table = 'pollos_engorde';

    protected $fillable = [
        'codigo_lote',
        'cantidad_inicial',
        'cantidad_actual',
        'fecha_ingreso',
        'dias_transcurridos',
        'estado',
        'consumo_total_kg',
        'peso_venta_kg',
        'fecha_venta',
        'observaciones'
    ];

    protected $casts = [
        'fecha_ingreso' => 'date',
        'fecha_venta' => 'date',
        'consumo_total_kg' => 'decimal:2',
        'peso_venta_kg' => 'decimal:2',
        'dias_transcurridos' => 'integer',
    ];

    // Métodos de negocio
    
    /**
     * RF-04: Ciclo de vida obligatorio de 120 días
     */
    const DIAS_CICLO_COMPLETO = 120;

    /**
     * Calcula los días transcurridos desde el ingreso
     */
    public function calcularDiasTranscurridos()
    {
        return Carbon::parse($this->fecha_ingreso)->diffInDays(Carbon::now());
    }

    /**
     * Actualiza los días transcurridos
     */
    public function actualizarDias()
    {
        $this->dias_transcurridos = (int) $this->calcularDiasTranscurridos();
        
        // Si alcanzó los 120 días, marcar como listo para venta
        if ($this->dias_transcurridos >= self::DIAS_CICLO_COMPLETO && $this->estado === 'crecimiento') {
            $this->estado = 'listo_venta';
        }
        
        $this->save();
    }

    /**
     * Verifica si el lote está listo para venta
     */
    public function getListoParaVentaAttribute()
    {
        return $this->dias_transcurridos >= self::DIAS_CICLO_COMPLETO;
    }

    /**
     * Obtiene los días restantes para completar el ciclo
     */
    public function getDiasRestantesAttribute()
    {
        $restantes = self::DIAS_CICLO_COMPLETO - $this->dias_transcurridos;
        return $restantes > 0 ? $restantes : 0;
    }

    /**
     * Registra la venta del lote
     */
    public function registrarVenta($pesoKg)
    {
        $this->peso_venta_kg = $pesoKg;
        $this->fecha_venta = now();
        $this->estado = 'vendido';
        $this->save();
    }

    /**
     * Registra consumo de alimento
     */
    public function registrarConsumo($kg)
    {
        $this->consumo_total_kg += $kg;
        $this->save();
    }
}
