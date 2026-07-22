@extends('layouts.app')
@section('title', 'Registrar Venta')
@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6 mb-6"><h1 class="text-2xl font-bold">💰 Registrar Venta - {{ $pollo->codigo_lote }}</h1><p class="text-gray-600">{{ $pollo->cantidad_actual }} pollos listos</p></div>
    <form action="{{ route('pollos.registrar-venta', $pollo) }}" method="POST" class="space-y-4">
        @csrf
        <div class="bg-white rounded-lg shadow p-6 space-y-4">
            <div><label class="block font-bold mb-2">Peso Total Venta (kg)</label><input type="number" step="0.01" name="peso_venta_kg" class="w-full p-4 border-2 rounded-lg" required></div>
            <div class="bg-blue-50 p-4 rounded-lg">
                <p class="font-bold mb-2">Resumen:</p>
                <p>Cantidad: {{ $pollo->cantidad_actual }} pollos</p>
                <p>Días de crianza: {{ $pollo->dias_transcurridos }} días</p>
                <p>Consumo total: {{ number_format($pollo->consumo_total_kg, 2) }} kg</p>
            </div>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="flex-1 bg-green-600 text-white py-4 rounded-lg font-bold">💾 Registrar Venta</button>
            <a href="{{ route('pollos.show', $pollo) }}" class="bg-gray-300 px-6 py-4 rounded-lg font-bold">← Cancelar</a>
        </div>
    </form>
</div>
@endsection
