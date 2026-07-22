<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Galpon extends Model
{
    use SoftDeletes;

    protected $table = 'galpones';

    protected $fillable = [
        'nombre',
        'capacidad',
        'fecha_inicio',
        'estado',
        'observaciones'
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
    ];

    // Relaciones
    public function ponedoras()
    {
        return $this->hasMany(Ponedora::class);
    }

    public function producciones()
    {
        return $this->hasMany(ProduccionDiaria::class);
    }

    // Métodos de negocio
    
    /**
     * Verifica si el galpón ha superado los 36 meses de vida
     * RF-05: Alerta de Descarte
     */
    public function debeDescartarse()
    {
        $mesesTranscurridos = Carbon::parse($this->fecha_inicio)->diffInMonths(Carbon::now());
        return $mesesTranscurridos >= 36;
    }

    /**
     * Obtiene los meses transcurridos desde el inicio
     */
    public function getMesesTranscurridosAttribute()
    {
        return Carbon::parse($this->fecha_inicio)->diffInMonths(Carbon::now());
    }

    /**
     * Obtiene la cantidad total de ponedoras activas en el galpón
     */
    public function getPonedorasActivasAttribute()
    {
        return $this->ponedoras()
            ->where('estado', 'activo')
            ->sum('cantidad_actual');
    }

    /**
     * Verifica y actualiza el estado si es necesario
     */
    public function verificarEstado()
    {
        if ($this->debeDescartarse() && $this->estado !== 'descarte') {
            $this->estado = 'descarte';
            $this->save();
            
            // Marcar ponedoras como descarte
            $this->ponedoras()->where('estado', 'activo')->update(['estado' => 'descarte']);
            
            return true;
        }
        return false;
    }
}
