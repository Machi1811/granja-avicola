@extends('layouts.app')
@section('title', 'Nuevo Lote')
@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h1 class="text-2xl font-bold">🐥 Registrar Nuevo Lote de Pollos de Engorde</h1>
        <p class="text-gray-600 mt-2">RF-03: Ciclo de producción de 120 días</p>
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
    
    <form action="{{ route('pollos.store') }}" method="POST" class="space-y-4">
        @csrf
        <div class="bg-white rounded-lg shadow p-6 space-y-4">
            <div>
                <label class="block font-bold mb-2">Código de Lote *</label>
                <input type="text" name="codigo_lote" value="{{ old('codigo_lote', $codigoSugerido) }}" 
                    class="w-full p-4 border-2 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                <p class="text-sm text-gray-500 mt-1">Debe ser único en el sistema</p>
            </div>
            
            <div>
                <label class="block font-bold mb-2">Cantidad Inicial *</label>
                <input type="number" name="cantidad_inicial" value="{{ old('cantidad_inicial') }}" min="1"
                    class="w-full p-4 border-2 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                <p class="text-sm text-gray-500 mt-1">Número de pollos al iniciar el lote</p>
            </div>
            
            <div>
                <label class="block font-bold mb-2">Fecha de Ingreso *</label>
                <input type="date" name="fecha_ingreso" value="{{ old('fecha_ingreso', date('Y-m-d')) }}" 
                    class="w-full p-4 border-2 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                <p class="text-sm text-gray-500 mt-1">El ciclo de 120 días iniciará desde esta fecha</p>
            </div>
            
            <div>
                <label class="block font-bold mb-2">Observaciones</label>
                <textarea name="observaciones" rows="3" 
                    class="w-full p-4 border-2 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('observaciones') }}</textarea>
            </div>
        </div>
        
        <div class="flex gap-3">
            <button type="submit" class="flex-1 bg-blue-600 text-white py-4 rounded-lg font-bold hover:bg-blue-700">
                💾 Registrar Lote
            </button>
            <a href="{{ route('pollos.index') }}" class="bg-gray-300 px-6 py-4 rounded-lg font-bold hover:bg-gray-400 text-center">
                ← Cancelar
            </a>
        </div>
    </form>
</div>
@endsection
