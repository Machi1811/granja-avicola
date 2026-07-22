@extends('layouts.app')
@section('title', 'Detalle de Galpón')
@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex justify-between">
            <div><h1 class="text-2xl font-bold">🏠 {{ $galpon->nombre }}</h1><p class="text-gray-600">Capacidad: {{ $galpon->capacidad }} aves</p></div>
            <a href="{{ route('galpones.edit', ['galpone' => $galpon->id]) }}" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">✏️ Editar</a>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="font-bold text-xl mb-4">📊 Información</h2>
        <div class="space-y-2">
            <div class="flex justify-between"><span>Estado:</span><strong class="{{ $galpon->estado === 'activo' ? 'text-green-600' : 'text-red-600' }}">{{ ucfirst($galpon->estado) }}</strong></div>
            <div class="flex justify-between"><span>Fecha Inicio:</span><strong>{{ $galpon->fecha_inicio->format('d/m/Y') }}</strong></div>
            <div class="flex justify-between"><span>Meses de Vida:</span><strong>{{ number_format($galpon->meses_vida, 1) }}</strong></div>
            @if($galpon->ponedoras->first())
            <div class="flex justify-between"><span>Ponedoras:</span><strong>{{ $galpon->ponedoras->first()->cantidad_actual }} aves</strong></div>
            @endif
        </div>
    </div>
    @if($galpon->producciones->count() > 0)
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="font-bold text-xl mb-4">🥚 Últimas Producciones</h2>
        <div class="space-y-2">
            @foreach($galpon->producciones->take(10) as $prod)
            <div class="flex justify-between items-center border-b pb-2">
                <span>{{ $prod->fecha->format('d/m/Y') }}</span>
                <span class="font-bold text-green-600">{{ number_format($prod->produccion_real) }} huevos</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif
    <div class="mt-6"><a href="{{ route('galpones.index') }}" class="bg-gray-300 px-6 py-3 rounded hover:bg-gray-400">← Volver</a></div>
</div>
@endsection
