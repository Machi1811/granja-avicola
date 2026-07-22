@extends('layouts.app')
@section('title', 'Nuevo Lote')
@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6 mb-6"><h1 class="text-2xl font-bold">🐥 Nuevo Lote de Pollos</h1></div>
    <form action="{{ route('pollos.store') }}" method="POST" class="space-y-4">
        @csrf
        <div class="bg-white rounded-lg shadow p-6 space-y-4">
            <div><label class="block font-bold mb-2">Código Lote</label><input type="text" name="codigo_lote" value="{{ old('codigo_lote', $codigoSugerido) }}" class="w-full p-4 border-2 rounded-lg" required></div>
            <div><label class="block font-bold mb-2">Cantidad Inicial</label><input type="number" name="cantidad_inicial" value="{{ old('cantidad_inicial') }}" class="w-full p-4 border-2 rounded-lg" required min="1"></div>
            <div><label class="block font-bold mb-2">Fecha Ingreso</label><input type="date" name="fecha_ingreso" value="{{ old('fecha_ingreso', date('Y-m-d')) }}" class="w-full p-4 border-2 rounded-lg" required></div>
            <div><label class="block font-bold mb-2">Observaciones</label><textarea name="observaciones" rows="3" class="w-full p-4 border-2 rounded-lg">{{ old('observaciones') }}</textarea></div>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="flex-1 bg-blue-600 text-white py-4 rounded-lg font-bold">💾 Guardar</button>
            <a href="{{ route('pollos.index') }}" class="bg-gray-300 px-6 py-4 rounded-lg font-bold">← Volver</a>
        </div>
    </form>
</div>
@endsection
