<?php

namespace App\Http\Controllers;

use App\Models\Alimento;
use App\Models\ConsumoAlimento;
use Illuminate\Http\Request;

class AlimentoController extends Controller
{
    public function index()
    {
        $alimentos = Alimento::orderBy('tipo')->get();
        
        // Verificar alertas de stock bajo
        foreach ($alimentos as $alimento) {
            if ($alimento->tiene_alerta_stock) {
                session()->flash('warning', "¡ALERTA! El alimento de {$alimento->tipo} tiene menos de 2 quintales en stock.");
            }
        }
        
        return view('alimentos.index', compact('alimentos'));
    }

    public function create()
    {
        return view('alimentos.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipo' => 'required|in:postura,engorde',
            'quintales_stock' => 'required|numeric|min:0',
        ]);

        // Convertir automáticamente a kg
        $validated['kg_stock'] = Alimento::quintalesAKg($validated['quintales_stock']);

        $alimento = Alimento::create($validated);

        return redirect()
            ->route('alimentos.index')
            ->with('success', 'Alimento registrado exitosamente');
    }

    public function show(Alimento $alimento)
    {
        $alimento->load(['consumos' => function($query) {
            $query->orderBy('fecha', 'desc')->limit(30);
        }]);
        
        return view('alimentos.show', compact('alimento'));
    }

    public function edit(Alimento $alimento)
    {
        return view('alimentos.edit', compact('alimento'));
    }

    public function update(Request $request, Alimento $alimento)
    {
        $validated = $request->validate([
            'tipo' => 'required|in:postura,engorde',
            'quintales_stock' => 'required|numeric|min:0',
        ]);

        $alimento->update($validated);

        return redirect()
            ->route('alimentos.show', $alimento)
            ->with('success', 'Alimento actualizado exitosamente');
    }

    public function destroy(Alimento $alimento)
    {
        $alimento->delete();

        return redirect()
            ->route('alimentos.index')
            ->with('success', 'Alimento eliminado exitosamente');
    }

    /**
     * Registrar compra de alimento
     */
    public function registrarCompra(Request $request, Alimento $alimento)
    {
        $validated = $request->validate([
            'quintales' => 'required|numeric|min:0.01',
            'precio_quintal' => 'required|numeric|min:0',
        ]);

        $alimento->registrarCompra(
            $validated['quintales'],
            $validated['precio_quintal']
        );

        return redirect()
            ->route('alimentos.show', $alimento)
            ->with('success', "Compra registrada: {$validated['quintales']} quintales agregados al stock");
    }

    /**
     * Formulario de compra
     */
    public function formCompra(Alimento $alimento)
    {
        return view('alimentos.compra', compact('alimento'));
    }

    /**
     * Historial de consumo
     */
    public function historialConsumo(Alimento $alimento)
    {
        $consumos = ConsumoAlimento::where('alimento_id', $alimento->id)
            ->orderBy('fecha', 'desc')
            ->paginate(50);
        
        return view('alimentos.historial', compact('alimento', 'consumos'));
    }
}
