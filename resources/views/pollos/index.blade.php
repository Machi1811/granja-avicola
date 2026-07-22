@extends('layouts.app')

@section('title', 'Pollos de Engorde')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800">🐥 Pollos de Engorde</h1>
        <a href="{{ route('pollos.create') }}" class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 font-semibold">
            ➕ Nuevo Lote
        </a>
    </div>

    <!-- Información del Ciclo (RF-04) -->
    <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-lg">
        <h3 class="font-bold text-blue-800 mb-2">📘 Ciclo de Vida (RF-04)</h3>
        <p class="text-blue-700"><strong>Ciclo obligatorio:</strong> 120 días exactos</p>
        <p class="text-blue-700 text-sm mt-1">Al completar 120 días, el lote se marca automáticamente como "Listo para Venta"</p>
    </div>

    <!-- Filtros por Estado -->
    <div class="bg-white rounded-lg shadow p-4">
        <div class="flex gap-2 overflow-x-auto">
            <button class="px-4 py-2 rounded bg-blue-600 text-white font-semibold whitespace-nowrap">
                Todos ({{ $pollos->count() }})
            </button>
            <button class="px-4 py-2 rounded bg-gray-200 text-gray-700 font-semibold whitespace-nowrap">
                En Crecimiento ({{ $pollos->where('estado', 'crecimiento')->count() }})
            </button>
            <button class="px-4 py-2 rounded bg-gray-200 text-gray-700 font-semibold whitespace-nowrap">
                Listos ({{ $pollos->where('estado', 'listo_venta')->count() }})
            </button>
            <button class="px-4 py-2 rounded bg-gray-200 text-gray-700 font-semibold whitespace-nowrap">
                Vendidos ({{ $pollos->where('estado', 'vendido')->count() }})
            </button>
        </div>
    </div>

    <!-- Alertas de Lotes Listos -->
    @php
        $lotesListos = $pollos->where('estado', 'listo_venta');
    @endphp

    @if($lotesListos->count() > 0)
        <div class="bg-green-50 border-l-4 border-green-500 p-6 rounded-lg shadow">
            <p class="font-bold text-green-800 text-lg mb-2">
                ✅ {{ $lotesListos->count() }} lote(s) completaron el ciclo de 120 días
            </p>
            <p class="text-green-700">Estos lotes están listos para ser vendidos</p>
        </div>
    @endif

    <!-- Lista de Lotes -->
    @if($pollos->count() === 0)
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <p class="text-gray-500 text-lg mb-4">No hay lotes de pollos registrados</p>
            <a href="{{ route('pollos.create') }}" class="inline-block bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700">
                Crear Primer Lote
            </a>
        </div>
    @else
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($pollos as $pollo)
                <div class="bg-white rounded-lg shadow hover:shadow-lg transition p-6">
                    <!-- Estado Badge -->
                    <div class="flex justify-between items-start mb-3">
                        <h2 class="text-xl font-bold text-gray-800">{{ $pollo->codigo_lote }}</h2>
                        @if($pollo->estado === 'crecimiento')
                            <span class="bg-blue-100 text-blue-800 text-xs px-3 py-1 rounded-full font-semibold">
                                🐥 Crecimiento
                            </span>
                        @elseif($pollo->estado === 'listo_venta')
                            <span class="bg-green-100 text-green-800 text-xs px-3 py-1 rounded-full font-semibold animate-pulse">
                                ✅ Listo
                            </span>
                        @else
                            <span class="bg-gray-100 text-gray-800 text-xs px-3 py-1 rounded-full font-semibold">
                                ✓ Vendido
                            </span>
                        @endif
                    </div>

                    <!-- Información del Lote -->
                    <div class="space-y-2 text-sm text-gray-600 mb-4">
                        <p><strong>Cantidad:</strong> {{ number_format($pollo->cantidad_actual) }} pollos</p>
                        <p><strong>Fecha ingreso:</strong> {{ $pollo->fecha_ingreso->format('d/m/Y') }}</p>
                        
                        <!-- Progreso del Ciclo -->
                        <div class="mt-3">
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-xs font-semibold">Días transcurridos</span>
                                <span class="text-xs font-bold {{ $pollo->listo_para_venta ? 'text-green-600' : 'text-blue-600' }}">
                                    {{ $pollo->dias_transcurridos }} / 120
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                @php
                                    $porcentaje = min(($pollo->dias_transcurridos / 120) * 100, 100);
                                @endphp
                                <div class="h-3 rounded-full {{ $pollo->listo_para_venta ? 'bg-green-600' : 'bg-blue-600' }}" 
                                     style="width: {{ $porcentaje }}%">
                                </div>
                            </div>
                        </div>

                        <!-- Días Restantes -->
                        @if(!$pollo->listo_para_venta && $pollo->estado !== 'vendido')
                            <p class="text-center font-bold text-blue-600 mt-2">
                                Faltan {{ $pollo->dias_restantes }} día(s)
                            </p>
                        @elseif($pollo->listo_para_venta)
                            <p class="text-center font-bold text-green-600 mt-2">
                                ✓ Ciclo Completo
                            </p>
                        @endif

                        <!-- Consumo Total -->
                        @if($pollo->consumo_total_kg > 0)
                            <p class="text-xs text-gray-500 mt-2">
                                Consumo total: {{ number_format($pollo->consumo_total_kg, 2) }} kg
                            </p>
                        @endif
                    </div>

                    <!-- Alerta Próximo a Completar (7 días o menos) -->
                    @if($pollo->dias_restantes > 0 && $pollo->dias_restantes <= 7)
                        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-3 mb-4">
                            <p class="text-yellow-700 text-xs font-semibold">
                                ⚠️ Próximo a completar ciclo
                            </p>
                        </div>
                    @endif

                    <!-- Botones -->
                    <div class="space-y-2">
                        <a href="{{ route('pollos.show', $pollo) }}" 
                           class="block w-full text-center bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                            Ver Detalles
                        </a>
                        
                        @if($pollo->estado === 'listo_venta')
                            <a href="{{ route('pollos.form-venta', $pollo) }}" 
                               class="block w-full text-center bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition font-semibold">
                                💰 Registrar Venta
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

@push('scripts')
<script>
    // Animación para lotes listos
    setInterval(() => {
        document.querySelectorAll('.animate-pulse').forEach(el => {
            el.classList.toggle('opacity-75');
        });
    }, 1000);
</script>
@endpush
@endsection
