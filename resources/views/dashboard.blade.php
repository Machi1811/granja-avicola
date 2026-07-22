@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Título -->
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800">📊 Dashboard</h1>
        <a href="{{ route('produccion.registro-rapido') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 text-sm font-semibold">
            ⚡ Registro Rápido
        </a>
    </div>

    <!-- Alertas Críticas -->
    @if($alertas['galpones_descarte'] > 0 || $alertas['alimento_postura_bajo'] > 0 || $alertas['alimento_engorde_bajo'] > 0 || $alertas['pollos_listos'] > 0)
    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow">
        <h2 class="text-lg font-bold text-red-800 mb-3">🚨 Alertas Críticas</h2>
        <div class="space-y-2">
            @if($alertas['galpones_descarte'] > 0)
            <div class="flex items-center gap-2 text-red-700">
                <span class="text-2xl">⚠️</span>
                <span><strong>{{ $alertas['galpones_descarte'] }}</strong> galpón(es) superaron los 36 meses</span>
            </div>
            @endif
            
            @if($alertas['alimento_postura_bajo'] > 0)
            <div class="flex items-center gap-2 text-orange-700">
                <span class="text-2xl">📉</span>
                <span>Alimento de <strong>postura</strong> bajo (menos de 2 quintales)</span>
            </div>
            @endif
            
            @if($alertas['alimento_engorde_bajo'] > 0)
            <div class="flex items-center gap-2 text-orange-700">
                <span class="text-2xl">📉</span>
                <span>Alimento de <strong>engorde</strong> bajo (menos de 2 quintales)</span>
            </div>
            @endif
            
            @if($alertas['pollos_listos'] > 0)
            <div class="flex items-center gap-2 text-blue-700">
                <span class="text-2xl">✅</span>
                <span><strong>{{ $alertas['pollos_listos'] }}</strong> lote(s) de pollos listos para venta (120 días cumplidos)</span>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Estadísticas Principales -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <!-- Ponedoras Activas -->
        <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
            <div class="flex items-center gap-3">
                <div class="text-4xl">🐔</div>
                <div>
                    <p class="text-gray-600 text-sm">Ponedoras Activas</p>
                    <p class="text-3xl font-bold text-gray-800">{{ number_format($estadisticas['ponedoras_activas']) }}</p>
                </div>
            </div>
        </div>

        <!-- Pollos en Crecimiento -->
        <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
            <div class="flex items-center gap-3">
                <div class="text-4xl">🐥</div>
                <div>
                    <p class="text-gray-600 text-sm">Pollos en Crecimiento</p>
                    <p class="text-3xl font-bold text-gray-800">{{ number_format($estadisticas['pollos_crecimiento']) }}</p>
                </div>
            </div>
        </div>

        <!-- Producción Hoy -->
        <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
            <div class="flex items-center gap-3">
                <div class="text-4xl">🥚</div>
                <div>
                    <p class="text-gray-600 text-sm">Producción Hoy</p>
                    <p class="text-3xl font-bold text-gray-800">{{ number_format($estadisticas['produccion_hoy']) }}</p>
                </div>
            </div>
        </div>

        <!-- Ventas del Mes -->
        <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
            <div class="flex items-center gap-3">
                <div class="text-4xl">💰</div>
                <div>
                    <p class="text-gray-600 text-sm">Ventas del Mes</p>
                    <p class="text-3xl font-bold text-green-600">S/. {{ number_format($estadisticas['ventas_mes'], 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Operarios Activos -->
        <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
            <div class="flex items-center gap-3">
                <div class="text-4xl">👷</div>
                <div>
                    <p class="text-gray-600 text-sm">Operarios Activos</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $estadisticas['operarios_activos'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Stock de Alimentos -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">🌾 Stock de Alimentos</h2>
        <div class="space-y-4">
            @foreach($alimentos as $alimento)
            <div class="border-l-4 p-4 rounded {{ $alimento->tiene_alerta_stock ? 'border-red-500 bg-red-50' : 'border-green-500 bg-green-50' }}">
                <div class="flex justify-between items-center flex-wrap gap-2">
                    <div>
                        <p class="font-bold text-lg capitalize">{{ $alimento->tipo }}</p>
                        <p class="text-sm text-gray-600">
                            <span class="font-semibold">{{ number_format($alimento->quintales_stock, 2) }}</span> quintales
                            ({{ number_format($alimento->kg_stock, 2) }} kg)
                        </p>
                    </div>
                    <div class="text-right">
                        @if($alimento->tiene_alerta_stock)
                        <span class="bg-red-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
                            ⚠️ BAJO STOCK
                        </span>
                        @else
                        <span class="bg-green-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
                            ✓ Stock OK
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Galpones con Alerta de Descarte (33+ meses) -->
    @if($galponesAlerta->count() > 0)
    <div class="bg-yellow-50 border-l-4 border-yellow-500 rounded-lg shadow p-6">
        <h2 class="text-xl font-bold text-yellow-800 mb-4">⚠️ Galpones Próximos a Descarte</h2>
        <p class="text-sm text-yellow-700 mb-4">Estos galpones están próximos a cumplir 36 meses de vida</p>
        <div class="space-y-3">
            @foreach($galponesAlerta as $galpon)
            <div class="bg-white p-4 rounded border border-yellow-300">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="font-bold">{{ $galpon->nombre }}</p>
                        <p class="text-sm text-gray-600">{{ $galpon->meses_transcurridos }} meses de vida</p>
                    </div>
                    <a href="{{ route('galpones.show', $galpon) }}" class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700 text-sm">
                        Ver Detalles
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Pollos Próximos a Completar Ciclo (7 días o menos) -->
    @if($pollosProximos->count() > 0)
    <div class="bg-blue-50 border-l-4 border-blue-500 rounded-lg shadow p-6">
        <h2 class="text-xl font-bold text-blue-800 mb-4">⏰ Pollos Próximos a Completar Ciclo (120 días)</h2>
        <div class="space-y-3">
            @foreach($pollosProximos as $pollo)
            <div class="bg-white p-4 rounded border border-blue-300">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="font-bold">{{ $pollo->codigo_lote }}</p>
                        <p class="text-sm text-gray-600">
                            Faltan <strong>{{ $pollo->dias_restantes }}</strong> día(s) - {{ $pollo->cantidad_actual }} pollos
                        </p>
                    </div>
                    <a href="{{ route('pollos.show', $pollo) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">
                        Ver Detalles
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Accesos Rápidos -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">⚡ Accesos Rápidos</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
            <a href="{{ route('produccion.registro-rapido') }}" class="bg-green-600 text-white p-4 rounded-lg text-center hover:bg-green-700 transition">
                <div class="text-3xl mb-2">🥚</div>
                <div class="text-sm font-semibold">Registrar Producción</div>
            </a>
            <a href="{{ route('asistencias.registro-diario') }}" class="bg-blue-600 text-white p-4 rounded-lg text-center hover:bg-blue-700 transition">
                <div class="text-3xl mb-2">✅</div>
                <div class="text-sm font-semibold">Asistencia Diaria</div>
            </a>
            <a href="{{ route('ventas.create') }}" class="bg-purple-600 text-white p-4 rounded-lg text-center hover:bg-purple-700 transition">
                <div class="text-3xl mb-2">💰</div>
                <div class="text-sm font-semibold">Nueva Venta</div>
            </a>
            <a href="{{ route('alimentos.index') }}" class="bg-yellow-600 text-white p-4 rounded-lg text-center hover:bg-yellow-700 transition">
                <div class="text-3xl mb-2">🌾</div>
                <div class="text-sm font-semibold">Ver Alimentos</div>
            </a>
        </div>
    </div>
</div>
@endsection
