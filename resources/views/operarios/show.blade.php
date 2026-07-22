@extends('layouts.app')
@section('title', 'Detalle de Operario')
@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex justify-between"><div><h1 class="text-2xl font-bold">👷 {{ $operario->nombre_completo }}</h1><p class="text-gray-600">DNI: {{ $operario->dni }}</p></div><a href="{{ route('operarios.edit', $operario) }}" class="bg-yellow-500 text-white px-4 py-2 rounded">✏️ Editar</a></div>
    </div>
    @if(session('success'))<div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded"><p class="text-green-800 font-semibold">{{ session('success') }}</p></div>@endif
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="font-bold text-xl mb-4">📋 Información</h2>
        <div class="space-y-2">
            <div class="flex justify-between"><span>Teléfono:</span><strong>{{ $operario->telefono }}</strong></div>
            <div class="flex justify-between"><span>Estado:</span><strong class="{{ $operario->estado === 'activo' ? 'text-green-600' : 'text-red-600' }}">{{ ucfirst($operario->estado) }}</strong></div>
            <div class="flex justify-between"><span>Pago Diario:</span><strong class="text-green-600 text-xl">S/. {{ number_format($operario->pago_diario, 2) }}</strong></div>
            <div class="flex justify-between"><span>Fecha Ingreso:</span><strong>{{ $operario->fecha_ingreso->format('d/m/Y') }}</strong></div>
        </div>
    </div>
    <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-lg">
        <h2 class="font-bold text-blue-800 mb-2">💰 Pago del Mes Actual</h2>
        <p class="text-blue-700">Días trabajados: <strong>{{ $diasMesActual }}</strong></p>
        <p class="text-blue-700 text-2xl font-bold mt-2">Total: S/. {{ number_format($pagoMesActual, 2) }}</p>
    </div>
    <div class="mt-6"><a href="{{ route('operarios.index') }}" class="bg-gray-300 px-6 py-3 rounded">← Volver</a></div>
</div>
@endsection
