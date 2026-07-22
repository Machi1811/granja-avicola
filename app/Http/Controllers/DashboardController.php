<?php

namespace App\Http\Controllers;

use App\Models\Galpon;
use App\Models\Ponedora;
use App\Models\PolloEngorde;
use App\Models\Alimento;
use App\Models\Venta;
use App\Models\Operario;
use App\Models\ProduccionDiaria;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Alertas críticas
        $alertas = [
            'galpones_descarte' => Galpon::where('estado', 'activo')
                ->get()
                ->filter(fn($g) => $g->debeDescartarse())
                ->count(),
            'alimento_postura_bajo' => Alimento::where('tipo', 'postura')
                ->where('quintales_stock', '<', Alimento::ALERTA_MINIMA_QUINTALES)
                ->count(),
            'alimento_engorde_bajo' => Alimento::where('tipo', 'engorde')
                ->where('quintales_stock', '<', Alimento::ALERTA_MINIMA_QUINTALES)
                ->count(),
            'pollos_listos' => PolloEngorde::where('estado', 'listo_venta')->count(),
        ];

        // Estadísticas generales
        $estadisticas = [
            'ponedoras_activas' => Ponedora::where('estado', 'activo')->sum('cantidad_actual'),
            'pollos_crecimiento' => PolloEngorde::whereIn('estado', ['crecimiento', 'listo_venta'])
                ->sum('cantidad_actual'),
            'produccion_hoy' => ProduccionDiaria::whereDate('fecha', Carbon::today())
                ->sum('produccion_real'),
            'ventas_mes' => Venta::whereMonth('fecha', Carbon::now()->month)
                ->whereYear('fecha', Carbon::now()->year)
                ->sum('total'),
            'operarios_activos' => Operario::where('estado', 'activo')->count(),
        ];

        // Stock de alimentos
        $alimentos = Alimento::all();

        // Galpones con alertas
        $galponesAlerta = Galpon::where('estado', 'activo')
            ->get()
            ->filter(fn($g) => $g->meses_transcurridos >= 33); // Alerta 3 meses antes

        // Pollos próximos a completar ciclo (faltan 7 días o menos)
        $pollosProximos = PolloEngorde::where('estado', 'crecimiento')
            ->get()
            ->filter(fn($p) => $p->dias_restantes <= 7 && $p->dias_restantes > 0);

        return view('dashboard', compact(
            'alertas',
            'estadisticas',
            'alimentos',
            'galponesAlerta',
            'pollosProximos'
        ));
    }
}
