<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Ponedora;
use App\Models\Galpon;
use Illuminate\Http\Request;

class VentaController extends Controller
{
    public function index()
    {
        $ventas = Venta::orderBy('fecha', 'desc')->paginate(20);
        
        // Estadísticas del mes actual
        $ventasMes = Venta::whereMonth('fecha', now()->month)
            ->whereYear('fecha', now()->year)
            ->sum('total');
        
        return view('ventas.index', compact('ventas', 'ventasMes'));
    }

    public function create()
    {
        $ponedoras = Ponedora::where('estado', 'activo')
            ->with('galpon')
            ->get();
        
        $galponesDescarte = Galpon::where('estado', 'descarte')
            ->with('ponedoras')
            ->get();
        
        return view('ventas.create', compact('ponedoras', 'galponesDescarte'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'fecha' => 'required|date',
            'tipo_producto' => 'required|in:ponedora_viva,huevos,pollo_engorde,carne_gallina',
            'cantidad' => 'required|numeric|min:0.01',
            'unidad_medida' => 'required|string',
            'precio_unitario' => 'nullable|numeric|min:0',
            'cliente' => 'nullable|string|max:255',
            'referencia_id' => 'nullable|integer',
            'observaciones' => 'nullable|string',
        ]);

        // Si es venta de ponedora y no hay precio, el modelo lo calculará automáticamente
        $venta = Venta::create($validated);

        // Si se vendieron ponedoras, actualizar el inventario
        if ($request->tipo_producto === 'ponedora_viva' && $request->ponedora_id) {
            $ponedora = Ponedora::find($request->ponedora_id);
            if ($ponedora) {
                $ponedora->cantidad_actual -= $request->cantidad;
                if ($ponedora->cantidad_actual <= 0) {
                    $ponedora->estado = 'vendido';
                }
                $ponedora->save();
            }
        }

        return redirect()
            ->route('ventas.index')
            ->with('success', 'Venta registrada exitosamente. Total: S/. ' . number_format($venta->total, 2));
    }

    public function show(Venta $venta)
    {
        return view('ventas.show', compact('venta'));
    }

    public function edit(Venta $venta)
    {
        return view('ventas.edit', compact('venta'));
    }

    public function update(Request $request, Venta $venta)
    {
        $validated = $request->validate([
            'fecha' => 'required|date',
            'tipo_producto' => 'required|in:ponedora_viva,huevos,pollo_engorde,carne_gallina',
            'cantidad' => 'required|numeric|min:0.01',
            'unidad_medida' => 'required|string',
            'precio_unitario' => 'required|numeric|min:0',
            'cliente' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string',
        ]);

        $venta->update($validated);

        return redirect()
            ->route('ventas.show', $venta)
            ->with('success', 'Venta actualizada exitosamente');
    }

    public function destroy(Venta $venta)
    {
        $venta->delete();

        return redirect()
            ->route('ventas.index')
            ->with('success', 'Venta eliminada exitosamente');
    }

    /**
     * Formulario rápido para venta de ponedoras (RF-03: Tarificador)
     */
    public function ventaPonedoras()
    {
        $ponedoras = Ponedora::where('estado', 'activo')
            ->with('galpon')
            ->get();
        
        return view('ventas.ponedoras', compact('ponedoras'));
    }

    /**
     * Calcular precio automáticamente según cantidad
     */
    public function calcularPrecio(Request $request)
    {
        $cantidad = $request->input('cantidad', 0);
        $precio = Venta::calcularPrecioPonedora($cantidad);
        $total = Venta::calcularTotal($cantidad, $precio);
        
        return response()->json([
            'precio_unitario' => $precio,
            'total' => $total,
            'tarifa' => $cantidad > Venta::LIMITE_DOCENA ? 'mayor_docena' : 'hasta_docena'
        ]);
    }
}
