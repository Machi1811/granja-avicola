@extends('layouts.app')

@section('title', 'Registrar Producción')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-2">🥚 Nueva Producción</h1>
        <p class="text-gray-600">Registrar producción diaria de un galpón</p>
    </div>

    @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded">
            <p class="font-bold text-red-800 mb-2">⚠️ Errores de validación:</p>
            <ul class="list-disc list-inside text-red-700">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('produccion.store') }}" method="POST" class="space-y-4">
        @csrf
        <div class="bg-white rounded-lg shadow p-6 space-y-4">
            <div>
                <label class="block text-lg font-bold text-gray-700 mb-2">🏠 Galpón</label>
                <select name="galpon_id" id="galpon_id" class="w-full p-4 border-2 border-gray-300 rounded-lg" required>
                    <option value="">Seleccionar galpón...</option>
                    @foreach($galpones as $galpon)
                        <option value="{{ $galpon->id }}" data-aves="{{ $galpon->ponedoras->first()->cantidad_actual ?? 0 }}">
                            {{ $galpon->nombre }} ({{ $galpon->ponedoras->first()->cantidad_actual ?? 0 }} aves)
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-lg font-bold text-gray-700 mb-2">📅 Fecha</label>
                <input type="date" name="fecha" value="{{ old('fecha', date('Y-m-d')) }}" class="w-full p-4 border-2 border-gray-300 rounded-lg" required>
            </div>

            <div>
                <label class="block text-lg font-bold text-gray-700 mb-2">🐔 Aves Activas</label>
                <input type="number" name="aves_activas" id="aves_activas" value="{{ old('aves_activas') }}" class="w-full p-4 border-2 border-gray-300 rounded-lg" required min="1">
            </div>

            <div>
                <label class="block text-lg font-bold text-gray-700 mb-2">📊 Producción Teórica</label>
                <input type="number" name="produccion_teorica" id="produccion_teorica" value="{{ old('produccion_teorica') }}" class="w-full p-4 border-2 border-gray-300 rounded-lg" required min="0">
                <p class="text-sm text-gray-600 mt-1">Igual al número de aves activas</p>
            </div>

            <div>
                <label class="block text-lg font-bold text-gray-700 mb-2">🥚 Producción Real</label>
                <input type="number" name="produccion_real" value="{{ old('produccion_real') }}" class="w-full p-4 border-2 border-gray-300 rounded-lg" min="0" placeholder="Huevos recolectados">
            </div>

            <div>
                <label class="block text-lg font-bold text-gray-700 mb-2">📝 Observaciones</label>
                <textarea name="observaciones" rows="3" class="w-full p-4 border-2 border-gray-300 rounded-lg">{{ old('observaciones') }}</textarea>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="flex-1 bg-blue-600 text-white py-4 rounded-lg font-bold hover:bg-blue-700">💾 Guardar</button>
            <a href="{{ route('produccion.index') }}" class="bg-gray-300 text-gray-700 py-4 px-6 rounded-lg font-bold hover:bg-gray-400">← Volver</a>
        </div>
    </form>
</div>

<script>
document.getElementById('galpon_id').addEventListener('change', function() {
    const selected = this.options[this.selectedIndex];
    const aves = selected.dataset.aves || 0;
    document.getElementById('aves_activas').value = aves;
    document.getElementById('produccion_teorica').value = aves;
});
</script>
@endsection
