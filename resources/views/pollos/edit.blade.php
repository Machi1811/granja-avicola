@extends('layouts.app')
@section('title', 'Editar Lote')
@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6 mb-6"><h1 class="text-2xl font-bold">✏️ Editar Lote</h1></div>
    <form action="{{ route('pollos.update', $pollo) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')
        <div class="bg-white rounded-lg shadow p-6 space-y-4">
            <div><label class="block font-bold mb-2">Código Lote</label><input type="text" name="codigo_lote" value="{{ $pollo->codigo_lote }}" class="w-full p-4 border-2 rounded-lg" required></div>
            <div><label class="block font-bold mb-2">Cantidad Actual</label><input type="number" name="cantidad_actual" value="{{ $pollo->cantidad_actual }}" class="w-full p-4 border-2 rounded-lg" required></div>
            <div><label class="block font-bold mb-2">Observaciones</label><textarea name="observaciones" rows="3" class="w-full p-4 border-2 rounded-lg">{{ $pollo->observaciones }}</textarea></div>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="flex-1 bg-blue-600 text-white py-4 rounded-lg font-bold">💾 Actualizar</button>
            <a href="{{ route('pollos.show', $pollo) }}" class="bg-gray-300 px-6 py-4 rounded-lg font-bold">← Cancelar</a>
        </div>
    </form>
</div>
@endsection
