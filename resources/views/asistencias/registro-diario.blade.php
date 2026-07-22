@extends('layouts.app')

@section('title', 'Registro Diario de Asistencia')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-2">✅ Registro Diario de Asistencia</h1>
        <p class="text-gray-600">Marque la asistencia de todos los operarios</p>
    </div>

    <!-- Formulario -->
    <form action="{{ route('asistencias.guardar-registro-diario') }}" method="POST" class="space-y-4">
        @csrf

        <!-- Fecha -->
        <div class="bg-white rounded-lg shadow p-6">
            <label class="block text-lg font-bold text-gray-700 mb-3">📅 Fecha</label>
            <input type="date" 
                   name="fecha" 
                   value="{{ $fecha }}"
                   class="w-full text-2xl p-4 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
                   required>
        </div>

        <!-- Información del Pago -->
        <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-lg">
            <p class="font-bold text-blue-800 mb-2">💰 Información de Pago</p>
            <p class="text-blue-700">Pago por día trabajado: <span class="font-bold text-xl">S/. 80.00</span></p>
        </div>

        <!-- Lista de Operarios -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">👷 Operarios</h2>
            
            @if($operarios->count() === 0)
                <p class="text-gray-500 text-center py-8">No hay operarios activos registrados</p>
            @else
                <div class="space-y-3">
                    @foreach($operarios as $operario)
                        @php
                            $yaRegistrado = in_array($operario->id, $registrosExistentes);
                        @endphp
                        
                        <div class="border-2 rounded-lg p-4 {{ $yaRegistrado ? 'border-gray-300 bg-gray-50' : 'border-blue-300 bg-blue-50' }}">
                            <div class="flex items-center justify-between gap-4">
                                <div class="flex-1">
                                    <p class="font-bold text-lg">{{ $operario->nombre_completo }}</p>
                                    <p class="text-sm text-gray-600">DNI: {{ $operario->dni }}</p>
                                    @if($yaRegistrado)
                                        <span class="inline-block mt-2 bg-gray-600 text-white text-xs px-3 py-1 rounded-full">
                                            Ya registrado
                                        </span>
                                    @endif
                                </div>
                                
                                @if(!$yaRegistrado)
                                    <div class="flex gap-2">
                                        <!-- Botón Asistió (checked por defecto) -->
                                        <label class="flex flex-col items-center cursor-pointer">
                                            <input type="radio" 
                                                   name="asistencias[{{ $operario->id }}]" 
                                                   value="1" 
                                                   class="hidden peer"
                                                   checked>
                                            <div class="w-20 h-20 flex items-center justify-center bg-gray-200 peer-checked:bg-green-500 rounded-lg transition text-3xl">
                                                ✓
                                            </div>
                                            <span class="mt-2 text-sm font-semibold">Asistió</span>
                                        </label>
                                        
                                        <!-- Botón Faltó -->
                                        <label class="flex flex-col items-center cursor-pointer">
                                            <input type="radio" 
                                                   name="asistencias[{{ $operario->id }}]" 
                                                   value="0" 
                                                   class="hidden peer">
                                            <div class="w-20 h-20 flex items-center justify-center bg-gray-200 peer-checked:bg-red-500 rounded-lg transition text-3xl">
                                                ✗
                                            </div>
                                            <span class="mt-2 text-sm font-semibold">Faltó</span>
                                        </label>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Resumen -->
        <div class="bg-green-50 border-l-4 border-green-500 p-6 rounded-lg">
            <p class="font-bold text-green-800 mb-2">📊 Resumen del Día</p>
            <div class="space-y-1 text-green-700">
                <p>Operarios presentes: <span id="count_presentes" class="font-bold">{{ $operarios->count() }}</span></p>
                <p>Operarios ausentes: <span id="count_ausentes" class="font-bold">0</span></p>
                <p class="text-lg font-bold">Total a pagar hoy: S/. <span id="total_pago">{{ number_format($operarios->count() * 80, 2) }}</span></p>
            </div>
        </div>

        <!-- Botones -->
        <div class="flex gap-3">
            <button type="submit" class="flex-1 bg-blue-600 text-white py-4 px-6 rounded-lg text-xl font-bold hover:bg-blue-700 transition shadow-lg">
                💾 Guardar Asistencias
            </button>
            <a href="{{ route('dashboard') }}" class="bg-gray-300 text-gray-700 py-4 px-6 rounded-lg text-xl font-bold hover:bg-gray-400 transition">
                ✕
            </a>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Actualizar contador en tiempo real
    const radioButtons = document.querySelectorAll('input[type="radio"]');
    
    radioButtons.forEach(radio => {
        radio.addEventListener('change', actualizarResumen);
    });

    function actualizarResumen() {
        let presentes = 0;
        let ausentes = 0;

        // Contar solo radios con name que empiece con "asistencias["
        const grupos = {};
        radioButtons.forEach(radio => {
            if (radio.name.startsWith('asistencias[')) {
                grupos[radio.name] = true;
            }
        });

        Object.keys(grupos).forEach(nombre => {
            const seleccionado = document.querySelector(`input[name="${nombre}"]:checked`);
            if (seleccionado) {
                if (seleccionado.value === '1') {
                    presentes++;
                } else {
                    ausentes++;
                }
            }
        });

        document.getElementById('count_presentes').textContent = presentes;
        document.getElementById('count_ausentes').textContent = ausentes;
        document.getElementById('total_pago').textContent = (presentes * 80).toFixed(2);
    }

    // Inicializar
    actualizarResumen();
</script>
@endpush
@endsection
