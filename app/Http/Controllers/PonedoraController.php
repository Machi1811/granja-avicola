<?php

namespace App\Http\Controllers;

use App\Models\Ponedora;
use App\Models\Galpon;
use Illuminate\Http\Request;

class PonedoraController extends Controller
{
    public function index()
    {
        $ponedoras = Ponedora::with('galpon')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('ponedoras.index', compact('ponedoras'));
    }

    public function create()
    {
        $galpones = Galpon::where('estado', 'activo')->get();
        return view('ponedoras.create', compact('galpones'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'galpon_id' => 'required|exists:galpones,id',
            'cantidad_inicial' => 'required|integer|min:1',
            'fecha_ingreso' => 'required|date',
        ]);

        $validated['cantidad_actual'] = $validated['cantidad_inicial'];
        $validated['estado'] = 'activo';

        $ponedora = Ponedora::create($validated);

        return redirect()
            ->route('ponedoras.index')
            ->with('success', 'Lote de ponedoras registrado exitosamente');
    }

    public function show(Ponedora $ponedora)
    {
        $ponedora->load('galpon');
        return view('ponedoras.show', compact('ponedora'));
    }

    public function edit(Ponedora $ponedora)
    {
        $galpones = Galpon::where('estado', 'activo')->get();
        return view('ponedoras.edit', compact('ponedora', 'galpones'));
    }

    public function update(Request $request, Ponedora $ponedora)
    {
        $validated = $request->validate([
            'galpon_id' => 'required|exists:galpones,id',
            'cantidad_actual' => 'required|integer|min:0',
            'fecha_ingreso' => 'required|date',
            'estado' => 'required|in:activo,vendido,descarte',
        ]);

        $ponedora->update($validated);

        return redirect()
            ->route('ponedoras.show', $ponedora)
            ->with('success', 'Ponedora actualizada exitosamente');
    }

    public function destroy(Ponedora $ponedora)
    {
        $ponedora->delete();

        return redirect()
            ->route('ponedoras.index')
            ->with('success', 'Lote de ponedoras eliminado exitosamente');
    }
}
