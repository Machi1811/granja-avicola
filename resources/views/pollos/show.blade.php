@extends('layouts.app')
@section('title', 'Detalle de Lote')
@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex justify-between"><div><h1 class="text-2xl font-bold">🐥 {{ $pollo->codigo_lote }}</h1><p class="text-gray-600">{{ $pollo->cantidad_actual }} pollos</p></div><a href="{{ route('pollos.edit', $pollo) }}" class="bg-yellow-500 text-white px-4 py-2 rounded">✏️</a></div>
    </div>
    <div class="grid md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="font-bold text-xl mb-4">📊 Información</h2>
            <div class="space-y-2">
                <div class="flex justify-between"><span>Cantidad Inicial:</span><strong>{{ $pollo->cantidad_inicial }}</strong></div>
                <div class="flex justify-between"><span>Cantidad Actual:</span><strong>{{ $pollo->cantidad_actual }}</strong></div>
                <div class="flex justify-between"><span>Fecha Ingreso:</span><strong>{{ $pollo->fecha_ingreso->format('d/m/Y') }}</strong></div>
                <div class="flex justify-between"><span>Días Transcurridos:</span><strong class="text-blue-600">{{ $pollo->dias_transcurridos }}</strong></div>
                <div class="flex justify-between"><span>Días Restantes:</span><strong class="text-orange-600">{{ $pollo->dias_restantes }}</strong></div>
                <div class="flex justify-between"><span>Estado:</span><strong class="{{ $pollo->estado === 'listo_venta' ? 'text-green-600' : 'text-blue-600' }}">{{ ucfirst(str_replace('_', ' ', $pollo->estado)) }}</strong></div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="font-bold text-xl mb-4">🌾 Consumo</h2>
            <p class="text-3xl font-bold text-green-600">{{ number_format($pollo->consumo_total_kg, 2) }} kg</p>
            @if($pollo->estado === 'listo_venta')
            <a href="{{ route('pollos.venta', $pollo) }}" class="mt-4 block bg-green-600 text-white text-center py-3 rounded-lg font-bold">💰 Registrar Venta</a>
            @endif
        </div>
    </div>
    @if($consumos->count() > 0)
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="font-bold text-xl mb-4">📋 Últimos Consumos</h2>
        <div class="space-y-2">
            @foreach($consumos as $consumo)
            <div class="flex justify-between border-b pb-2"><span>{{ $consumo->fecha->format('d/m/Y') }}</span><span class="font-bold">{{ number_format($consumo->cantidad_kg, 2) }} kg</span></div>
            @endforeach
        </div>
    </div>
    @endif
    <div class="mt-6"><a href="{{ route('pollos.index') }}" class="bg-gray-300 px-6 py-3 rounded">← Volver</a></div>
</div>
@endsection
