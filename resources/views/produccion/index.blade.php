@extends('layouts.app')

@section('title', 'Producción Diaria')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 mb-2">🥚 Producción Diaria</h1>
                <p class="text-gray-600">Historial de producción de huevos</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('produccion.registro-rapido') }}" class="bg-green-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-green-700 transition">
                    ⚡ Registro Rápido
                </a>
                <a href="{{ route('produccion.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-blue-700 transition">
                    + Nueva
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

    <!-- Lista de Producción -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        @if($producciones->count() === 0)
            <div class="p-12 text-center">
                <p class="text-gray-500 mb-4">No hay registros de producción</p>
                <a href="{{ route('produccion.registro-rapido') }}" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                    Registrar Producción
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Galpón</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aves Activas</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Prod. Teórica</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Prod. Neta (90%)</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Prod. Real</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Diferencia</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($producciones as $produccion)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="font-medium">{{ $produccion->fecha->format('d/m/Y') }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-medium text-gray-900">{{ $produccion->galpon->nombre }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="font-medium">{{ number_format($produccion->aves_activas) }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-gray-600">{{ number_format($produccion->produccion_teorica) }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="font-medium text-blue-600">{{ number_format($produccion->produccion_neta) }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="font-bold text-green-600">{{ number_format($produccion->produccion_real) }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $diferencia = $produccion->produccion_real - $produccion->produccion_neta;
                                    @endphp
                                    <span class="font-bold {{ $diferencia >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $diferencia >= 0 ? '+' : '' }}{{ number_format($diferencia) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('produccion.show', $produccion) }}" class="text-blue-600 hover:text-blue-800">
                                            👁️
                                        </a>
                                        <a href="{{ route('produccion.edit', $produccion) }}" class="text-yellow-600 hover:text-yellow-800">
                                            ✏️
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="px-6 py-4 bg-gray-50">
                {{ $producciones->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
