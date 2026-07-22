<?php

namespace App\Http\Controllers;

use App\Models\Galpon;
use Illuminate\Http\Request;

class GalponController extends Controller
{
    public function index()
    {
        $galpones = Galpon::with('ponedoras')->orderBy('created_at', 'desc')->get();
        
        // Actualizar estados automáticamente
        foreach ($galpones as $galpon) {
            $galpon->verificarEstado();
        }
        
        return view('galpones.index', compact('galpones'));
    }

    public function create()
    {
        return view('galpones.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'capacidad' => 'required|integer|min:1',
            'fecha_inicio' => 'required|date',
            'observaciones' => 'nullable|string',
        ]);

        $galpon = Galpon::create($validated);

        return redirect()
            ->route('galpones.index')
            ->with('success', 'Galpón creado exitosamente');
    }

    public function show(Galpon $galpone)
    {
        $galpone->load(['ponedoras', 'producciones' => function($query) {
            $query->orderBy('fecha', 'desc')->limit(30);
        }]);
        
        $galpone->verificarEstado();
        
        return view('galpones.show', ['galpon' => $galpone]);
    }

    public function edit(Galpon $galpone)
    {
        return view('galpones.edit', ['galpon' => $galpone]);
    }

    public function update(Request $request, Galpon $galpone)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'capacidad' => 'required|integer|min:1',
            'fecha_inicio' => 'required|date',
            'estado' => 'required|in:activo,descarte',
            'observaciones' => 'nullable|string',
        ]);

        $galpone->update($validated);

        return redirect()
            ->route('galpones.show', $galpone)
            ->with('success', 'Galpón actualizado exitosamente');
    }

    public function destroy(Galpon $galpone)
    {
        $galpone->delete();

        return redirect()
            ->route('galpones.index')
            ->with('success', 'Galpón eliminado exitosamente');
    }
}
