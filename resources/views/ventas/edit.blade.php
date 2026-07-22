@extends('layouts.app')

@section('title', 'Editar Venta')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-2">✏️ Editar Venta</h1>
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

    <form action="{{ route('ventas.update', $venta) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-lg shadow p-6">
            <label class="block text-lg font-bold text-gray-700 mb-3">📅 Fecha</label>
            <input type="date" 
                   name="fecha" 
                   value="{{ old('fecha', $venta->fecha->format('Y-m-d')) }}"
                   class="w-full text-xl p-4 border-2 border-gray-300 rounded-lg focus:border-green-500 focus:outline-none"
                   required>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <label class="block text-lg font-bold text-gray-700 mb-3">📦 Tipo de Producto</label>
            <select name="tipo_producto" 
                    class="w-full text-xl p-4 border-2 border-gray-300 rounded-lg focus:border-green-500 focus:outline-none"
                    required>
                <option value="ponedora_viva" {{ old('tipo_producto', $venta->tipo_producto) == 'ponedora_viva' ? 'selected' : '' }}>🐔 Gallina Ponedora Viva</option>
                <option value="huevos" {{ old('tipo_producto', $venta->tipo_producto) == 'huevos' ? 'selected' : '' }}>🥚 Huevos</option>
                <option value="pollo_engorde" {{ old('tipo_producto', $venta->tipo_producto) == 'pollo_engorde' ? 'selected' : '' }}>🐥 Pollo de Engorde</option>
                <option value="carne_gallina" {{ old('tipo_producto', $venta->tipo_producto) == 'carne_gallina' ? 'selected' : '' }}>🍗 Carne de Gallina (Descarte)</option>
            </select>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <label class="block text-lg font-bold text-gray-700 mb-3">🔢 Cantidad</label>
            <input type="number" 
                   name="cantidad" 
                   value="{{ old('cantidad', $venta->cantidad) }}"
                   class="w-full text-3xl p-4 border-2 border-gray-300 rounded-lg focus:border-green-500 focus:outline-none text-center font-bold"
                   min="0.01"
                   step="0.01"
                   required>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <label class="block text-lg font-bold text-gray-700 mb-3">📏 Unidad de Medida</label>
            <select name="unidad_medida" 
                    class="w-full text-xl p-4 border-2 border-gray-300 rounded-lg focus:border-green-500 focus:outline-none"
                    required>
                <option value="unidad" {{ old('unidad_medida', $venta->unidad_medida) == 'unidad' ? 'selected' : '' }}>Unidad</option>
                <option value="docena" {{ old('unidad_medida', $venta->unidad_medida) == 'docena' ? 'selected' : '' }}>Docena</option>
                <option value="kg" {{ old('unidad_medida', $venta->unidad_medida) == 'kg' ? 'selected' : '' }}>Kilogramo (kg)</option>
            </select>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <label class="block text-lg font-bold text-gray-700 mb-3">💵 Precio Unitario</label>
            <div class="flex items-center gap-2">
                <span class="text-2xl font-bold">S/.</span>
                <input type="number" 
                       name="precio_unitario" 
                       value="{{ old('precio_unitario', $venta->precio_unitario) }}"
                       class="flex-1 text-3xl p-4 border-2 border-gray-300 rounded-lg focus:border-green-500 focus:outline-none text-center font-bold"
                       min="0"
                       step="0.01"
                       required>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <label class="block text-lg font-bold text-gray-700 mb-3">👤 Cliente (Opcional)</label>
            <input type="text" 
                   name="cliente" 
                   value="{{ old('cliente', $venta->cliente) }}"
                   class="w-full text-lg p-4 border-2 border-gray-300 rounded-lg focus:border-green-500 focus:outline-none">
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <label class="block text-lg font-bold text-gray-700 mb-3">📝 Observaciones</label>
            <textarea name="observaciones" 
                      rows="3"
                      class="w-full text-lg p-4 border-2 border-gray-300 rounded-lg focus:border-green-500 focus:outline-none">{{ old('observaciones', $venta->observaciones) }}</textarea>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="flex-1 bg-blue-600 text-white py-4 px-6 rounded-lg text-xl font-bold hover:bg-blue-700 transition">
                💾 Actualizar Venta
            </button>
            <a href="{{ route('ventas.show', $venta) }}" class="bg-gray-300 text-gray-700 py-4 px-6 rounded-lg text-xl font-bold hover:bg-gray-400 transition text-center">
                ← Cancelar
            </a>
        </div>
    </form>
</div>
@endsection
