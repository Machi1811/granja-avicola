<?php

namespace App\Http\Controllers;

use App\Models\ProduccionDiaria;
use App\Models\Galpon;
use App\Models\Alimento;
use App\Models\ConsumoAlimento;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ProduccionDiariaController extends Controller
{
    public function index()
    {
        $producciones = ProduccionDiaria::with('galpon')
            ->orderBy('fecha', 'desc')
            ->paginate(20);
        
        return view('produccion.index', compact('producciones'));
    }

    public function create()
    {
        $galpones = Galpon::where('estado', 'activo')
            ->with('ponedoras')
            ->get();
        
        return view('produccion.create', compact('galpones'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'galpon_id' => 'required|exists:galpones,id',
            'fecha' => 'required|date',
            'aves_activas' => 'required|integer|min:1',
            'produccion_teorica' => 'required|integer|min:0',
            'produccion_real' => 'nullable|integer|min:0',
            'observaciones' => 'nullable|string',
        ]);

        // El modelo calculará automáticamente la producción neta (90% de teórica)
        $produccion = ProduccionDiaria::create($validated);

        // Registrar consumo de alimento automáticamente
        $alimento = Alimento::where('tipo', 'postura')->first();
        
        if ($alimento) {
            ConsumoAlimento::create([
                'alimento_id' => $alimento->id,
                'fecha' => $validated['fecha'],
                'cantidad_aves' => $validated['aves_activas'],
                'consumo_por_ave_gramos' => 120, // RF-02: 120g por ave
                'tipo_ave' => 'ponedora',
                'referencia_id' => $produccion->galpon_id,
            ]);
        }

        return redirect()
            ->route('produccion.index')
            ->with('success', 'Producción registrada exitosamente. Merma del 10% aplicada automáticamente.');
    }

    public function show(ProduccionDiaria $produccion)
    {
        $produccion->load('galpon');
        return view('produccion.show', compact('produccion'));
    }

    public function edit(ProduccionDiaria $produccion)
    {
        $galpones = Galpon::where('estado', 'activo')->get();
        return view('produccion.edit', compact('produccion', 'galpones'));
    }

    public function update(Request $request, ProduccionDiaria $produccion)
    {
        $validated = $request->validate([
            'galpon_id' => 'required|exists:galpones,id',
            'fecha' => 'required|date',
            'aves_activas' => 'required|integer|min:1',
            'produccion_teorica' => 'required|integer|min:0',
            'produccion_real' => 'nullable|integer|min:0',
            'observaciones' => 'nullable|string',
        ]);

        $produccion->update($validated);

        return redirect()
            ->route('produccion.show', $produccion)
            ->with('success', 'Producción actualizada exitosamente');
    }

    public function destroy(ProduccionDiaria $produccion)
    {
        $produccion->delete();

        return redirect()
            ->route('produccion.index')
            ->with('success', 'Registro de producción eliminado exitosamente');
    }

    /**
     * Formulario rápido para registro diario desde móvil
     */
    public function registroRapido()
    {
        $galpones = Galpon::where('estado', 'activo')
            ->with('ponedoras')
            ->get();
        
        $fecha = Carbon::today()->format('Y-m-d');
        
        return view('produccion.registro-rapido', compact('galpones', 'fecha'));
    }
}
