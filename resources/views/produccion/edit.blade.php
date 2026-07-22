@extends('layouts.app')

@section('title', 'Editar Producción')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h1 class="text-2xl font-bold">✏️ Editar Producción</h1>
    </div>

    <form action="{{ route('produccion.update', $produccion) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')
        <div class="bg-white rounded-lg shadow p-6 space-y-4">
            <div>
                <label class="block font-bold mb-2">Galpón</label>
                <select name="galpon_id" class="w-full p-4 border-2 rounded-lg" required>
                    @foreach($galpones as $galpon)
                        <option value="{{ $galpon->id }}" {{ $produccion->galpon_id == $galpon->id ? 'selected' : '' }}>{{ $galpon->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div><label class="block font-bold mb-2">Fecha</label><input type="date" name="fecha" value="{{ $produccion->fecha->format('Y-m-d') }}" class="w-full p-4 border-2 rounded-lg" required></div>
            <div><label class="block font-bold mb-2">Aves Activas</label><input type="number" name="aves_activas" value="{{ $produccion->aves_activas }}" class="w-full p-4 border-2 rounded-lg" required></div>
            <div><label class="block font-bold mb-2">Producción Teórica</label><input type="number" name="produccion_teorica" value="{{ $produccion->produccion_teorica }}" class="w-full p-4 border-2 rounded-lg" required></div>
            <div><label class="block font-bold mb-2">Producción Real</label><input type="number" name="produccion_real" value="{{ $produccion->produccion_real }}" class="w-full p-4 border-2 rounded-lg"></div>
            <div><label class="block font-bold mb-2">Observaciones</label><textarea name="observaciones" rows="3" class="w-full p-4 border-2 rounded-lg">{{ $produccion->observaciones }}</textarea></div>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="flex-1 bg-blue-600 text-white py-4 rounded-lg font-bold">💾 Actualizar</button>
            <a href="{{ route('produccion.show', $produccion) }}" class="bg-gray-300 px-6 py-4 rounded-lg font-bold">← Cancelar</a>
        </div>
    </form>
</div>
@endsection
