<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\Operario;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AsistenciaController extends Controller
{
    public function index()
    {
        $asistencias = Asistencia::with('operario')
            ->orderBy('fecha', 'desc')
            ->paginate(30);
        
        return view('asistencias.index', compact('asistencias'));
    }

    public function create()
    {
        $operarios = Operario::where('estado', 'activo')->get();
        $fecha = Carbon::today()->format('Y-m-d');
        
        return view('asistencias.create', compact('operarios', 'fecha'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'operario_id' => 'required|exists:operarios,id',
            'fecha' => 'required|date',
            'asistio' => 'required|boolean',
            'observaciones' => 'nullable|string',
        ]);

        // Verificar si ya existe un registro para ese operario en esa fecha
        $existe = Asistencia::where('operario_id', $validated['operario_id'])
            ->where('fecha', $validated['fecha'])
            ->exists();
        
        if ($existe) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Ya existe un registro de asistencia para este operario en esta fecha');
        }

        $asistencia = Asistencia::create($validated);

        return redirect()
            ->route('asistencias.index')
            ->with('success', 'Asistencia registrada exitosamente');
    }

    public function show(Asistencia $asistencia)
    {
        $asistencia->load('operario');
        return view('asistencias.show', compact('asistencia'));
    }

    public function edit(Asistencia $asistencia)
    {
        $operarios = Operario::where('estado', 'activo')->get();
        return view('asistencias.edit', compact('asistencia', 'operarios'));
    }

    public function update(Request $request, Asistencia $asistencia)
    {
        $validated = $request->validate([
            'operario_id' => 'required|exists:operarios,id',
            'fecha' => 'required|date',
            'asistio' => 'required|boolean',
            'observaciones' => 'nullable|string',
        ]);

        $asistencia->update($validated);

        return redirect()
            ->route('asistencias.show', $asistencia)
            ->with('success', 'Asistencia actualizada exitosamente');
    }

    public function destroy(Asistencia $asistencia)
    {
        $asistencia->delete();

        return redirect()
            ->route('asistencias.index')
            ->with('success', 'Asistencia eliminada exitosamente');
    }

    /**
     * Registro rápido diario - Todos los operarios activos
     */
    public function registroDiario()
    {
        $fecha = request('fecha', Carbon::today()->format('Y-m-d'));
        $operarios = Operario::where('estado', 'activo')->get();
        
        // Verificar si ya hay registros para esta fecha
        $registrosExistentes = Asistencia::where('fecha', $fecha)
            ->pluck('operario_id')
            ->toArray();
        
        return view('asistencias.registro-diario', compact('operarios', 'fecha', 'registrosExistentes'));
    }

    /**
     * Guardar registro diario masivo
     */
    public function guardarRegistroDiario(Request $request)
    {
        $validated = $request->validate([
            'fecha' => 'required|date',
            'asistencias' => 'required|array',
            'asistencias.*' => 'required|boolean',
        ]);

        $fecha = $validated['fecha'];
        $contador = 0;

        foreach ($validated['asistencias'] as $operarioId => $asistio) {
            // Verificar si ya existe
            $existe = Asistencia::where('operario_id', $operarioId)
                ->where('fecha', $fecha)
                ->exists();
            
            if (!$existe) {
                Asistencia::create([
                    'operario_id' => $operarioId,
                    'fecha' => $fecha,
                    'asistio' => $asistio,
                ]);
                $contador++;
            }
        }

        return redirect()
            ->route('asistencias.index')
            ->with('success', "Registradas {$contador} asistencias para el día {$fecha}");
    }

    /**
     * Reporte de asistencia por operario
     */
    public function reporteOperario(Operario $operario, Request $request)
    {
        $fechaInicio = $request->input('fecha_inicio', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $fechaFin = $request->input('fecha_fin', Carbon::now()->endOfMonth()->format('Y-m-d'));
        
        $asistencias = Asistencia::where('operario_id', $operario->id)
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->orderBy('fecha')
            ->get();
        
        $diasTrabajados = $asistencias->where('asistio', true)->count();
        $diasFaltados = $asistencias->where('asistio', false)->count();
        $pagoTotal = $diasTrabajados * Operario::PAGO_DIARIO;
        
        return view('asistencias.reporte-operario', compact(
            'operario',
            'asistencias',
            'diasTrabajados',
            'diasFaltados',
            'pagoTotal',
            'fechaInicio',
            'fechaFin'
        ));
    }
}
