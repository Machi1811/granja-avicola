<?php

namespace App\Http\Controllers;

use App\Models\Operario;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OperarioController extends Controller
{
    public function index()
    {
        $operarios = Operario::orderBy('estado', 'desc')
            ->orderBy('nombre')
            ->get();
        
        return view('operarios.index', compact('operarios'));
    }

    public function create()
    {
        return view('operarios.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'dni' => 'required|string|size:8|unique:operarios,dni',
            'telefono' => 'nullable|string|max:20',
            'fecha_ingreso' => 'required|date',
        ]);

        $validated['pago_diario'] = Operario::PAGO_DIARIO;
        $validated['estado'] = 'activo';

        $operario = Operario::create($validated);

        return redirect()
            ->route('operarios.index')
            ->with('success', 'Operario registrado exitosamente');
    }

    public function show(Operario $operario)
    {
        $operario->load(['asistencias' => function($query) {
            $query->orderBy('fecha', 'desc')->limit(30);
        }]);
        
        // Calcular pagos del mes actual
        $mesActual = Carbon::now();
        $pagoMesActual = $operario->calcularPagoTotal(
            $mesActual->startOfMonth()->format('Y-m-d'),
            $mesActual->endOfMonth()->format('Y-m-d')
        );
        
        $diasMesActual = $operario->diasTrabajados(
            $mesActual->copy()->startOfMonth()->format('Y-m-d'),
            $mesActual->copy()->endOfMonth()->format('Y-m-d')
        );
        
        return view('operarios.show', compact('operario', 'pagoMesActual', 'diasMesActual'));
    }

    public function edit(Operario $operario)
    {
        return view('operarios.edit', compact('operario'));
    }

    public function update(Request $request, Operario $operario)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'dni' => 'required|string|size:8|unique:operarios,dni,' . $operario->id,
            'telefono' => 'nullable|string|max:20',
            'estado' => 'required|in:activo,inactivo',
            'fecha_ingreso' => 'required|date',
        ]);

        $operario->update($validated);

        return redirect()
            ->route('operarios.show', $operario)
            ->with('success', 'Operario actualizado exitosamente');
    }

    public function destroy(Operario $operario)
    {
        $operario->delete();

        return redirect()
            ->route('operarios.index')
            ->with('success', 'Operario eliminado exitosamente');
    }

    /**
     * Reporte de pagos por período
     */
    public function reportePagos(Request $request)
    {
        $fechaInicio = $request->input('fecha_inicio', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $fechaFin = $request->input('fecha_fin', Carbon::now()->endOfMonth()->format('Y-m-d'));
        
        $operarios = Operario::where('estado', 'activo')->get();
        
        $reporte = [];
        $totalGeneral = 0;
        
        foreach ($operarios as $operario) {
            $diasTrabajados = $operario->diasTrabajados($fechaInicio, $fechaFin);
            $pagoTotal = $operario->calcularPagoTotal($fechaInicio, $fechaFin);
            
            $reporte[] = [
                'operario' => $operario,
                'dias_trabajados' => $diasTrabajados,
                'pago_total' => $pagoTotal
            ];
            
            $totalGeneral += $pagoTotal;
        }
        
        return view('operarios.reporte-pagos', compact('reporte', 'totalGeneral', 'fechaInicio', 'fechaFin'));
    }
}
