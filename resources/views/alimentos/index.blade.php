@extends('layouts.app')

@section('title', 'Alimentos')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800">🌾 Stock de Alimentos</h1>
        <a href="{{ route('alimentos.create') }}" class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 font-semibold">
            ➕ Registrar Alimento
        </a>
    </div>

    <!-- Resumen de Alertas -->
    @php
        $alertas = $alimentos->filter(fn($a) => $a->tiene_alerta_stock)->count();
    @endphp

    @if($alertas > 0)
        <div class="bg-red-50 border-l-4 border-red-500 p-6 rounded-lg shadow">
            <p class="font-bold text-red-800 text-lg">🚨 {{ $alertas }} tipo(s) de alimento con stock bajo</p>
            <p class="text-red-700 mt-1">Menos de 2 quintales disponibles. Se requiere compra urgente.</p>
        </div>
    @endif

    <!-- Lista de Alimentos -->
    @if($alimentos->count() === 0)
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <p class="text-gray-500 text-lg mb-4">No hay alimentos registrados</p>
            <a href="{{ route('alimentos.create') }}" class="inline-block bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700">
                Registrar Primer Alimento
            </a>
        </div>
    @else
        <div class="grid gap-4 lg:grid-cols-2">
            @foreach($alimentos as $alimento)
                <div class="bg-white rounded-lg shadow hover:shadow-lg transition p-6">
                    <!-- Cabecera -->
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800 capitalize">
                                {{ $alimento->tipo === 'postura' ? '🐔' : '🐥' }} 
                                Alimento de {{ $alimento->tipo }}
                            </h2>
                        </div>
                        @if($alimento->tiene_alerta_stock)
                            <span class="bg-red-600 text-white text-sm px-4 py-2 rounded-full font-bold animate-pulse">
                                ⚠️ CRÍTICO
                            </span>
                        @else
                            <span class="bg-green-600 text-white text-sm px-4 py-2 rounded-full font-bold">
                                ✓ Stock OK
                            </span>
                        @endif
                    </div>

                    <!-- Stock Actual -->
                    <div class="bg-gray-50 rounded-lg p-6 mb-4">
                        <p class="text-sm text-gray-600 mb-2">Stock Disponible</p>
                        <div class="space-y-1">
                            <p class="text-4xl font-bold text-gray-800">
                                {{ number_format($alimento->quintales_stock, 2) }}
                                <span class="text-xl text-gray-600">quintales</span>
                            </p>
                            <p class="text-lg text-gray-600">
                                = {{ number_format($alimento->kg_stock, 2) }} kg
                            </p>
                        </div>
                    </div>

                    <!-- Última Compra -->
                    @if($alimento->fecha_ultima_compra)
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-4">
                            <p class="text-sm font-semibold text-blue-800">Última Compra</p>
                            <p class="text-sm text-blue-700">
                                {{ $alimento->fecha_ultima_compra->format('d/m/Y') }} - 
                                {{ number_format($alimento->quintales_ultima_compra, 2) }} quintales
                                @if($alimento->precio_quintal)
                                    (S/. {{ number_format($alimento->precio_quintal, 2) }}/qq)
                                @endif
                            </p>
                        </div>
                    @endif

                    <!-- Cálculo de Consumo Diario -->
                    @php
                        $consumoDiario = $alimento->tipo === 'postura' 
                            ? (500 * 120) / 1000 / 46  // Ejemplo: 500 aves
                            : 0;
                        $diasRestantes = $consumoDiario > 0 
                            ? floor($alimento->quintales_stock / $consumoDiario)
                            : 0;
                    @endphp

                    @if($alimento->tipo === 'postura' && $consumoDiario > 0)
                        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 mb-4">
                            <p class="text-sm font-semibold text-yellow-800">📊 Estimación</p>
                            <p class="text-sm text-yellow-700">
                                Consumo diario aprox: {{ number_format($consumoDiario, 3) }} qq
                            </p>
                            <p class="text-sm text-yellow-700 font-bold">
                                Stock suficiente para: ~{{ $diasRestantes }} días
                            </p>
                        </div>
                    @endif

                    <!-- Botones -->
                    <div class="flex gap-2">
                        <a href="{{ route('alimentos.form-compra', $alimento) }}" 
                           class="flex-1 bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition text-center font-semibold">
                            💰 Registrar Compra
                        </a>
                        <a href="{{ route('alimentos.show', $alimento) }}" 
                           class="flex-1 bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition text-center font-semibold">
                            📋 Ver Detalles
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Información RF-02 -->
    <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-lg">
        <h3 class="font-bold text-blue-800 mb-2">📘 Información del Sistema</h3>
        <div class="text-blue-700 space-y-1 text-sm">
            <p><strong>Consumo por ponedora:</strong> 120 gramos/día</p>
            <p><strong>Conversión:</strong> 1 quintal = 46 kg</p>
            <p><strong>Alerta automática:</strong> Cuando stock &lt; 2 quintales</p>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Parpadeo de alertas críticas
    setInterval(() => {
        document.querySelectorAll('.animate-pulse').forEach(el => {
            el.classList.toggle('opacity-75');
        });
    }, 1000);
</script>
@endpush
@endsection
