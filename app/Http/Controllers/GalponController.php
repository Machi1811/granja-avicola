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

    public function show(Galpon $galpon)
    {
        $galpon->load(['ponedoras', 'producciones' => function($query) {
            $query->orderBy('fecha', 'desc')->limit(30);
        }]);
        
        $galpon->verificarEstado();
        
        return view('galpones.show', compact('galpon'));
    }

    public function edit(Galpon $galpon)
    {
        return view('galpones.edit', compact('galpon'));
    }

    public function update(Request $request, Galpon $galpon)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'capacidad' => 'required|integer|min:1',
            'fecha_inicio' => 'required|date',
            'estado' => 'required|in:activo,descarte',
            'observaciones' => 'nullable|string',
        ]);

        $galpon->update($validated);

        return redirect()
            ->route('galpones.show', $galpon)
            ->with('success', 'Galpón actualizado exitosamente');
    }

    public function destroy(Galpon $galpon)
    {
        $galpon->delete();

        return redirect()
            ->route('galpones.index')
            ->with('success', 'Galpón eliminado exitosamente');
    }
}
