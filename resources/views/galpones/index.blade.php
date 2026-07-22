@extends('layouts.app')

@section('title', 'Galpones')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800">🏠 Galpones</h1>
        <a href="{{ route('galpones.create') }}" class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 font-semibold">
            ➕ Nuevo Galpón
        </a>
    </div>

    <!-- Lista de Galpones -->
    @if($galpones->count() === 0)
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <p class="text-gray-500 text-lg mb-4">No hay galpones registrados</p>
            <a href="{{ route('galpones.create') }}" class="inline-block bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700">
                Crear Primer Galpón
            </a>
        </div>
    @else
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($galpones as $galpon)
                <div class="bg-white rounded-lg shadow hover:shadow-lg transition p-6">
                    <!-- Estado -->
                    <div class="flex justify-between items-start mb-3">
                        <h2 class="text-xl font-bold text-gray-800">{{ $galpon->nombre }}</h2>
                        @if($galpon->estado === 'activo')
                            <span class="bg-green-100 text-green-800 text-xs px-3 py-1 rounded-full font-semibold">
                                ✓ Activo
                            </span>
                        @else
                            <span class="bg-red-100 text-red-800 text-xs px-3 py-1 rounded-full font-semibold">
                                ⚠ Descarte
                            </span>
                        @endif
                    </div>

                    <!-- Información -->
                    <div class="space-y-2 text-sm text-gray-600 mb-4">
                        <p><strong>Capacidad:</strong> {{ $galpon->capacidad }} aves</p>
                        <p><strong>Ponedoras activas:</strong> {{ $galpon->ponedoras_activas }}</p>
                        <p><strong>Meses de vida:</strong> {{ $galpon->meses_transcurridos }} meses</p>
                        <p><strong>Fecha inicio:</strong> {{ $galpon->fecha_inicio->format('d/m/Y') }}</p>
                    </div>

                    <!-- Alerta de descarte -->
                    @if($galpon->debeDescartarse())
                        <div class="bg-red-50 border-l-4 border-red-500 p-3 mb-4">
                            <p class="text-red-700 text-sm font-semibold">
                                ⚠️ Supera los 36 meses de vida
                            </p>
                        </div>
                    @elseif($galpon->meses_transcurridos >= 33)
                        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-3 mb-4">
                            <p class="text-yellow-700 text-sm font-semibold">
                                ⚠️ Próximo a cumplir 36 meses
                            </p>
                        </div>
                    @endif

                    <!-- Botón -->
                    <a href="{{ route('galpones.show', $galpon) }}" class="block w-full text-center bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                        Ver Detalles
                    </a>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
