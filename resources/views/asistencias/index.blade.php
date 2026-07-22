@extends('layouts.app')

@section('title', 'Asistencias')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 mb-2">✅ Asistencias</h1>
                <p class="text-gray-600">Historial de asistencia de operarios</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('asistencias.registro-diario') }}" class="bg-green-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-green-700 transition">
                    ⚡ Registro Diario
                </a>
                <a href="{{ route('asistencias.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-blue-700 transition">
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

    @if(session('info'))
        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded">
            <p class="text-blue-800 font-semibold">{{ session('info') }}</p>
        </div>
    @endif

    <!-- Lista de Asistencias -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        @if($asistencias->count() === 0)
            <div class="p-12 text-center">
                <p class="text-gray-500 mb-4">No hay registros de asistencia</p>
                <a href="{{ route('asistencias.registro-diario') }}" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                    Registrar Asistencia
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Operario</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">DNI</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Estado</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Pago</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($asistencias as $asistencia)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="font-medium">{{ $asistencia->fecha->format('d/m/Y') }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-medium text-gray-900">{{ $asistencia->operario->nombre_completo }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                    {{ $asistencia->operario->dni }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($asistencia->asistio)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                            ✓ Asistió
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                            ✗ Faltó
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    <span class="font-bold {{ $asistencia->asistio ? 'text-green-600' : 'text-red-600' }}">
                                        S/. {{ number_format($asistencia->pago_dia, 2) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('asistencias.show', $asistencia) }}" class="text-blue-600 hover:text-blue-800">
                                            👁️
                                        </a>
                                        <a href="{{ route('asistencias.edit', $asistencia) }}" class="text-yellow-600 hover:text-yellow-800">
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
                {{ $asistencias->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
