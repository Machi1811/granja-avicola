@extends('layouts.app')
@section('title', 'Detalle de Alimento')
@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6 mb-6"><h1 class="text-2xl font-bold">🌾 Alimento de {{ ucfirst($alimento->tipo) }}</h1></div>
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="font-bold text-xl mb-4">📊 Stock Actual</h2>
        <p class="text-4xl font-bold text-green-600 mb-2">{{ number_format($alimento->quintales_stock, 2) }} qq</p>
        <p class="text-gray-600">{{ number_format($alimento->kg_stock, 2) }} kg</p>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="font-bold text-xl mb-4">💰 Última Compra</h2>
        <div class="space-y-2">
            <div class="flex justify-between"><span>Fecha:</span><strong>{{ $alimento->fecha_ultima_compra?->format('d/m/Y') ?? 'N/A' }}</strong></div>
            <div class="flex justify-between"><span>Cantidad:</span><strong>{{ number_format($alimento->quintales_ultima_compra, 2) }} qq</strong></div>
            <div class="flex justify-between"><span>Precio/Quintal:</span><strong>S/. {{ number_format($alimento->precio_quintal, 2) }}</strong></div>
        </div>
        <a href="{{ route('alimentos.compra', $alimento) }}" class="mt-4 block bg-green-600 text-white text-center py-3 rounded-lg font-bold">🛒 Registrar Compra</a>
    </div>
    <div class="mt-6"><a href="{{ route('alimentos.index') }}" class="bg-gray-300 px-6 py-3 rounded">← Volver</a></div>
</div>
@endsection
