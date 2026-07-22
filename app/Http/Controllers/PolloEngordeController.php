<?php

namespace App\Http\Controllers;

use App\Models\PolloEngorde;
use App\Models\Alimento;
use App\Models\ConsumoAlimento;
use Illuminate\Http\Request;

class PolloEngordeController extends Controller
{
    public function index()
    {
        $pollos = PolloEngorde::orderBy('fecha_ingreso', 'desc')->get();
        
        // Actualizar días transcurridos de todos los lotes
        foreach ($pollos as $pollo) {
            $pollo->actualizarDias();
        }
        
        return view('pollos.index', compact('pollos'));
    }

    public function create()
    {
        // Generar código de lote automático
        $ultimoLote = PolloEngorde::orderBy('id', 'desc')->first();
        $numeroLote = $ultimoLote ? ($ultimoLote->id + 1) : 1;
        $codigoSugerido = 'LOTE-' . str_pad($numeroLote, 4, '0', STR_PAD_LEFT);
        
        return view('pollos.create', compact('codigoSugerido'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'codigo_lote' => 'required|string|unique:pollos_engorde,codigo_lote|max:255',
            'cantidad_inicial' => 'required|integer|min:1',
            'fecha_ingreso' => 'required|date',
            'observaciones' => 'nullable|string',
        ]);

        $validated['cantidad_actual'] = $validated['cantidad_inicial'];
        $validated['estado'] = 'crecimiento';
        $validated['dias_transcurridos'] = 0;

        $pollo = PolloEngorde::create($validated);

        return redirect()
            ->route('pollos.index')
            ->with('success', "Lote {$pollo->codigo_lote} registrado. Ciclo de 120 días iniciado.");
    }

    public function show(PolloEngorde $pollo)
    {
        $pollo->actualizarDias();
        
        // Consumos del lote
        $consumos = ConsumoAlimento::where('tipo_ave', 'engorde')
            ->where('referencia_id', $pollo->id)
            ->orderBy('fecha', 'desc')
            ->get();
        
        return view('pollos.show', compact('pollo', 'consumos'));
    }

    public function edit(PolloEngorde $pollo)
    {
        return view('pollos.edit', compact('pollo'));
    }

    public function update(Request $request, PolloEngorde $pollo)
    {
        $validated = $request->validate([
            'codigo_lote' => 'required|string|max:255|unique:pollos_engorde,codigo_lote,' . $pollo->id,
            'cantidad_actual' => 'required|integer|min:0',
            'fecha_ingreso' => 'required|date',
            'estado' => 'required|in:crecimiento,listo_venta,vendido',
            'observaciones' => 'nullable|string',
        ]);

        $pollo->update($validated);
        $pollo->actualizarDias();

        return redirect()
            ->route('pollos.show', $pollo)
            ->with('success', 'Lote actualizado exitosamente');
    }

    public function destroy(PolloEngorde $pollo)
    {
        $pollo->delete();

        return redirect()
            ->route('pollos.index')
            ->with('success', 'Lote eliminado exitosamente');
    }

    /**
     * Registrar consumo de alimento
     */
    public function registrarConsumo(Request $request, PolloEngorde $pollo)
    {
        $validated = $request->validate([
            'fecha' => 'required|date',
            'consumo_kg' => 'required|numeric|min:0.01',
            'consumo_por_ave_gramos' => 'required|numeric|min:1',
        ]);

        $alimento = Alimento::where('tipo', 'engorde')->first();
        
        if (!$alimento) {
            return redirect()
                ->back()
                ->with('error', 'No hay alimento de engorde registrado en el sistema');
        }

        ConsumoAlimento::create([
            'alimento_id' => $alimento->id,
            'fecha' => $validated['fecha'],
            'cantidad_aves' => $pollo->cantidad_actual,
            'consumo_por_ave_gramos' => $validated['consumo_por_ave_gramos'],
            'tipo_ave' => 'engorde',
            'referencia_id' => $pollo->id,
        ]);

        $pollo->registrarConsumo($validated['consumo_kg']);

        return redirect()
            ->route('pollos.show', $pollo)
            ->with('success', 'Consumo registrado exitosamente');
    }

    /**
     * Formulario para registrar venta
     */
    public function formVenta(PolloEngorde $pollo)
    {
        if (!$pollo->listo_para_venta) {
            return redirect()
                ->route('pollos.show', $pollo)
                ->with('error', 'El lote aún no ha completado los 120 días de ciclo');
        }
        
        return view('pollos.venta', compact('pollo'));
    }

    /**
     * Registrar venta del lote
     */
    public function registrarVenta(Request $request, PolloEngorde $pollo)
    {
        $validated = $request->validate([
            'peso_kg' => 'required|numeric|min:0.01',
            'precio_kg' => 'required|numeric|min:0',
            'cliente' => 'nullable|string|max:255',
        ]);

        $pollo->registrarVenta($validated['peso_kg']);

        // Crear registro de venta
        \App\Models\Venta::create([
            'fecha' => now(),
            'tipo_producto' => 'pollo_engorde',
            'cantidad' => $validated['peso_kg'],
            'unidad_medida' => 'kg',
            'precio_unitario' => $validated['precio_kg'],
            'total' => $validated['peso_kg'] * $validated['precio_kg'],
            'referencia_id' => $pollo->id,
            'cliente' => $validated['cliente'],
        ]);

        return redirect()
            ->route('pollos.index')
            ->with('success', "Venta del lote {$pollo->codigo_lote} registrada exitosamente");
    }
}
