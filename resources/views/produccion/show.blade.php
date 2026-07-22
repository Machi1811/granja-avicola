@extends('layouts.app')

@section('title', 'Detalle de Producción')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex justify-between">
            <div>
                <h1 class="text-2xl font-bold">🥚 Producción {{ $produccion->fecha->format('d/m/Y') }}</h1>
                <p class="text-gray-600">{{ $produccion->galpon->nombre }}</p>
            </div>
            <a href="{{ route('produccion.edit', $produccion) }}" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">✏️ Editar</a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6 space-y-3">
        <div class="flex justify-between"><span>Aves Activas:</span><strong>{{ number_format($produccion->aves_activas) }}</strong></div>
        <div class="flex justify-between"><span>Producción Teórica:</span><strong>{{ number_format($produccion->produccion_teorica) }}</strong></div>
        <div class="flex justify-between"><span>Producción Neta (90%):</span><strong class="text-blue-600">{{ number_format($produccion->produccion_neta) }}</strong></div>
        <div class="flex justify-between"><span>Producción Real:</span><strong class="text-green-600 text-xl">{{ number_format($produccion->produccion_real) }}</strong></div>
        @if($produccion->observaciones)
        <div class="pt-3 border-t"><strong>Observaciones:</strong><p class="text-gray-700 mt-1">{{ $produccion->observaciones }}</p></div>
        @endif
    </div>

    <div class="mt-6"><a href="{{ route('produccion.index') }}" class="bg-gray-300 px-6 py-3 rounded hover:bg-gray-400">← Volver</a></div>
</div>
@endsection
