@extends('layouts.app')
@section('title', 'Registrar Compra')
@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6 mb-6"><h1 class="text-2xl font-bold">🛒 Registrar Compra de Alimento</h1><p class="text-gray-600">{{ ucfirst($alimento->tipo) }}</p></div>
    <form action="{{ route('alimentos.guardar-compra', $alimento) }}" method="POST" class="space-y-4">
        @csrf
        <div class="bg-white rounded-lg shadow p-6 space-y-4">
            <div class="bg-blue-50 p-4 rounded-lg"><p class="font-bold mb-1">Stock Actual:</p><p class="text-2xl font-bold text-blue-600">{{ number_format($alimento->quintales_stock, 2) }} qq</p></div>
            <div><label class="block font-bold mb-2">Cantidad (quintales)</label><input type="number" step="0.01" name="quintales" class="w-full p-4 border-2 rounded-lg" required></div>
            <div><label class="block font-bold mb-2">Precio por Quintal (S/.)</label><input type="number" step="0.01" name="precio_quintal" value="{{ $alimento->precio_quintal }}" class="w-full p-4 border-2 rounded-lg" required></div>
            <div><label class="block font-bold mb-2">Fecha</label><input type="date" name="fecha" value="{{ date('Y-m-d') }}" class="w-full p-4 border-2 rounded-lg" required></div>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="flex-1 bg-green-600 text-white py-4 rounded-lg font-bold">💾 Registrar Compra</button>
            <a href="{{ route('alimentos.show', $alimento) }}" class="bg-gray-300 px-6 py-4 rounded-lg font-bold">← Cancelar</a>
        </div>
    </form>
</div>
@endsection
