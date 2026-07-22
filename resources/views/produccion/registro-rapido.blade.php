@extends('layouts.app')

@section('title', 'Registro Rápido de Producción')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-2">⚡ Registro Rápido de Producción</h1>
        <p class="text-gray-600">Optimizado para uso en campo desde móvil</p>
    </div>

    <!-- Formulario -->
    <form action="{{ route('produccion.store') }}" method="POST" class="space-y-4">
        @csrf

        <!-- Fecha -->
        <div class="bg-white rounded-lg shadow p-6">
            <label class="block text-lg font-bold text-gray-700 mb-3">📅 Fecha</label>
            <input type="date" 
                   name="fecha" 
                   value="{{ $fecha }}"
                   class="w-full text-2xl p-4 border-2 border-gray-300 rounded-lg focus:border-green-500 focus:outline-none"
                   required>
        </div>

        <!-- Seleccionar Galpón -->
        <div class="bg-white rounded-lg shadow p-6">
            <label class="block text-lg font-bold text-gray-700 mb-3">🏠 Galpón</label>
            <select name="galpon_id" 
                    id="galpon_id"
                    class="w-full text-2xl p-4 border-2 border-gray-300 rounded-lg focus:border-green-500 focus:outline-none"
                    required>
                <option value="">Seleccione un galpón</option>
                @foreach($galpones as $galpon)
                    <option value="{{ $galpon->id }}" data-aves="{{ $galpon->ponedoras_activas }}">
                        {{ $galpon->nombre }} ({{ $galpon->ponedoras_activas }} aves)
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Aves Activas -->
        <div class="bg-white rounded-lg shadow p-6">
            <label class="block text-lg font-bold text-gray-700 mb-3">🐔 Aves Activas Hoy</label>
            <input type="number" 
                   name="aves_activas" 
                   id="aves_activas"
                   class="w-full text-3xl p-4 border-2 border-gray-300 rounded-lg focus:border-green-500 focus:outline-none text-center font-bold"
                   min="1"
                   placeholder="500"
                   required>
            <p class="text-sm text-gray-500 mt-2">Cantidad de ponedoras que produjeron hoy</p>
        </div>

        <!-- Producción Teórica -->
        <div class="bg-white rounded-lg shadow p-6">
            <label class="block text-lg font-bold text-gray-700 mb-3">🥚 Producción Teórica (Esperada)</label>
            <input type="number" 
                   name="produccion_teorica" 
                   id="produccion_teorica"
                   class="w-full text-3xl p-4 border-2 border-gray-300 rounded-lg focus:border-green-500 focus:outline-none text-center font-bold"
                   min="0"
                   placeholder="500"
                   required>
            <p class="text-sm text-gray-500 mt-2">Huevos que se esperaba recolectar</p>
        </div>

        <!-- Cálculo Automático de Merma -->
        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-6 rounded-lg">
            <p class="font-bold text-yellow-800 mb-2">📊 Cálculo Automático (Merma 10%)</p>
            <div class="space-y-1 text-yellow-700">
                <p>Producción teórica: <span id="display_teorica" class="font-bold">0</span> huevos</p>
                <p>Merma automática (10%): <span id="display_merma" class="font-bold">0</span> huevos</p>
                <p class="text-lg font-bold">Producción neta esperada: <span id="display_neta" class="text-green-700">0</span> huevos</p>
            </div>
        </div>

        <!-- Producción Real Recolectada -->
        <div class="bg-white rounded-lg shadow p-6">
            <label class="block text-lg font-bold text-gray-700 mb-3">✅ Producción Real Recolectada</label>
            <input type="number" 
                   name="produccion_real" 
                   id="produccion_real"
                   class="w-full text-3xl p-4 border-2 border-green-300 rounded-lg focus:border-green-500 focus:outline-none text-center font-bold text-green-600"
                   min="0"
                   placeholder="450">
            <p class="text-sm text-gray-500 mt-2">Huevos realmente recolectados (opcional)</p>
        </div>

        <!-- Observaciones -->
        <div class="bg-white rounded-lg shadow p-6">
            <label class="block text-lg font-bold text-gray-700 mb-3">📝 Observaciones</label>
            <textarea name="observaciones" 
                      rows="3"
                      class="w-full text-lg p-4 border-2 border-gray-300 rounded-lg focus:border-green-500 focus:outline-none"
                      placeholder="Notas adicionales (opcional)"></textarea>
        </div>

        <!-- Botones -->
        <div class="flex gap-3">
            <button type="submit" class="flex-1 bg-green-600 text-white py-4 px-6 rounded-lg text-xl font-bold hover:bg-green-700 transition shadow-lg">
                💾 Guardar Producción
            </button>
            <a href="{{ route('dashboard') }}" class="bg-gray-300 text-gray-700 py-4 px-6 rounded-lg text-xl font-bold hover:bg-gray-400 transition">
                ✕
            </a>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Auto-completar aves activas al seleccionar galpón
    document.getElementById('galpon_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const aves = selectedOption.getAttribute('data-aves');
        if (aves) {
            document.getElementById('aves_activas').value = aves;
            document.getElementById('produccion_teorica').value = aves;
            calcularMerma();
        }
    });

    // Calcular merma automáticamente
    document.getElementById('produccion_teorica').addEventListener('input', calcularMerma);

    function calcularMerma() {
        const teorica = parseInt(document.getElementById('produccion_teorica').value) || 0;
        const merma = Math.round(teorica * 0.10);
        const neta = teorica - merma;

        document.getElementById('display_teorica').textContent = teorica;
        document.getElementById('display_merma').textContent = merma;
        document.getElementById('display_neta').textContent = neta;
    }
</script>
@endpush
@endsection
