@extends('layouts.app')

@section('title', 'Detalle de Asistencia')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 mb-2">✅ Detalle de Asistencia</h1>
                <p class="text-gray-600">{{ $asistencia->fecha->format('d/m/Y') }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('asistencias.edit', $asistencia) }}" class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition">
                    ✏️ Editar
                </a>
                <a href="{{ route('asistencias.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition">
                    ← Volver
                </a>
            </div>
        </div>
    </div>

    <!-- Mensajes -->
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded">
            <p class="text-green-800 font-semibold">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Información del Operario -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">👷 Operario</h2>
        <div class="space-y-2">
            <div class="flex justify-between">
                <span class="text-gray-600">Nombre:</span>
                <span class="font-bold">{{ $asistencia->operario->nombre_completo }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">DNI:</span>
                <span class="font-bold">{{ $asistencia->operario->dni }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Teléfono:</span>
                <span class="font-bold">{{ $asistencia->operario->telefono }}</span>
            </div>
        </div>
    </div>

    <!-- Información de Asistencia -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">📋 Información</h2>
        <div class="space-y-3">
            <div class="flex justify-between items-center">
                <span class="text-gray-600">Fecha:</span>
                <span class="font-bold">{{ $asistencia->fecha->format('d/m/Y') }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-gray-600">Estado:</span>
                @if($asistencia->asistio)
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-green-100 text-green-800">
                        ✓ Asistió
                    </span>
                @else
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-red-100 text-red-800">
                        ✗ Faltó
                    </span>
                @endif
            </div>
            <div class="flex justify-between items-center">
                <span class="text-gray-600">Pago del día:</span>
                <span class="text-2xl font-bold {{ $asistencia->asistio ? 'text-green-600' : 'text-red-600' }}">
                    S/. {{ number_format($asistencia->pago_dia, 2) }}
                </span>
            </div>
        </div>
    </div>

    <!-- Observaciones -->
    @if($asistencia->observaciones)
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">📝 Observaciones</h2>
            <p class="text-gray-700">{{ $asistencia->observaciones }}</p>
        </div>
    @endif

    <!-- Eliminar -->
    <div class="bg-white rounded-lg shadow p-6 mt-6">
        <h2 class="text-xl font-bold text-red-600 mb-2">🗑️ Zona de Peligro</h2>
        <p class="text-gray-600 mb-4">Eliminar este registro de asistencia. Esta acción no se puede deshacer.</p>
        <form action="{{ route('asistencias.destroy', $asistencia) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este registro?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 transition">
                Eliminar Registro
            </button>
        </form>
    </div>
</div>
@endsection
