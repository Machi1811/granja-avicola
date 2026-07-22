@extends('layouts.app')
@section('title', 'Editar Lote')
@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h1 class="text-2xl font-bold">✏️ Editar Lote de Pollos de Engorde</h1>
    </div>
    
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <p class="font-bold">⚠ Errores de validación:</p>
            <ul class="list-disc list-inside mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form action="{{ route('pollos.update', $pollo) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')
        <div class="bg-white rounded-lg shadow p-6 space-y-4">
            <div>
                <label class="block font-bold mb-2">Código Lote *</label>
                <input type="text" name="codigo_lote" value="{{ old('codigo_lote', $pollo->codigo_lote) }}" 
                    class="w-full p-4 border-2 rounded-lg focus:ring-2 focus:ring-blue-500" required>
            </div>
            
            <div>
                <label class="block font-bold mb-2">Cantidad Inicial</label>
                <input type="number" value="{{ $pollo->cantidad_inicial }}" disabled
                    class="w-full p-4 border-2 rounded-lg bg-gray-100">
                <p class="text-sm text-gray-500 mt-1">Este valor no se puede editar</p>
            </div>
            
            <div>
                <label class="block font-bold mb-2">Cantidad Actual *</label>
                <input type="number" name="cantidad_actual" value="{{ old('cantidad_actual', $pollo->cantidad_actual) }}" min="0"
                    class="w-full p-4 border-2 rounded-lg focus:ring-2 focus:ring-blue-500" required>
            </div>
            
            <div>
                <label class="block font-bold mb-2">Fecha de Ingreso *</label>
                <input type="date" name="fecha_ingreso" value="{{ old('fecha_ingreso', $pollo->fecha_ingreso->format('Y-m-d')) }}" 
                    class="w-full p-4 border-2 rounded-lg focus:ring-2 focus:ring-blue-500" required>
            </div>
            
            <div>
                <label class="block font-bold mb-2">Estado *</label>
                <select name="estado" class="w-full p-4 border-2 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                    <option value="crecimiento" {{ old('estado', $pollo->estado) == 'crecimiento' ? 'selected' : '' }}>
                        🐣 Crecimiento (menos de 120 días)
                    </option>
                    <option value="listo_venta" {{ old('estado', $pollo->estado) == 'listo_venta' ? 'selected' : '' }}>
                        ✅ Listo para Venta (120+ días)
                    </option>
                    <option value="vendido" {{ old('estado', $pollo->estado) == 'vendido' ? 'selected' : '' }}>
                        💰 Vendido
                    </option>
                </select>
            </div>
            
            <div>
                <label class="block font-bold mb-2">Observaciones</label>
                <textarea name="observaciones" rows="3" 
                    class="w-full p-4 border-2 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('observaciones', $pollo->observaciones) }}</textarea>
            </div>
        </div>
        
        <div class="flex gap-3">
            <button type="submit" class="flex-1 bg-blue-600 text-white py-4 rounded-lg font-bold hover:bg-blue-700">
                💾 Actualizar
            </button>
            <a href="{{ route('pollos.show', $pollo) }}" class="bg-gray-300 px-6 py-4 rounded-lg font-bold hover:bg-gray-400 text-center">
                ← Cancelar
            </a>
        </div>
    </form>
</div>
@endsection
