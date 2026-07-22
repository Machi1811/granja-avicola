@extends('layouts.app')
@section('title', 'Editar Galpón')
@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6 mb-6"><h1 class="text-2xl font-bold">✏️ Editar Galpón</h1></div>
    <form action="{{ route('galpones.update', $galpon) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')
        <div class="bg-white rounded-lg shadow p-6 space-y-4">
            <div><label class="block font-bold mb-2">Nombre</label><input type="text" name="nombre" value="{{ $galpon->nombre }}" class="w-full p-4 border-2 rounded-lg" required></div>
            <div><label class="block font-bold mb-2">Capacidad</label><input type="number" name="capacidad" value="{{ $galpon->capacidad }}" class="w-full p-4 border-2 rounded-lg" required></div>
            <div><label class="block font-bold mb-2">Fecha Inicio</label><input type="date" name="fecha_inicio" value="{{ $galpon->fecha_inicio->format('Y-m-d') }}" class="w-full p-4 border-2 rounded-lg" required></div>
            <div><label class="block font-bold mb-2">Estado</label><select name="estado" class="w-full p-4 border-2 rounded-lg"><option value="activo" {{ $galpon->estado === 'activo' ? 'selected' : '' }}>Activo</option><option value="descarte" {{ $galpon->estado === 'descarte' ? 'selected' : '' }}>Descarte</option></select></div>
            <div><label class="block font-bold mb-2">Observaciones</label><textarea name="observaciones" rows="3" class="w-full p-4 border-2 rounded-lg">{{ $galpon->observaciones }}</textarea></div>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="flex-1 bg-blue-600 text-white py-4 rounded-lg font-bold">💾 Actualizar</button>
            <a href="{{ route('galpones.show', $galpon) }}" class="bg-gray-300 px-6 py-4 rounded-lg font-bold">← Cancelar</a>
        </div>
    </form>
</div>
@endsection
