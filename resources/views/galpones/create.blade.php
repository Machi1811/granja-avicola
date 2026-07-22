@extends('layouts.app')
@section('title', 'Nuevo Galpón')
@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6 mb-6"><h1 class="text-2xl font-bold">🏠 Nuevo Galpón</h1></div>
    <form action="{{ route('galpones.store') }}" method="POST" class="space-y-4">
        @csrf
        <div class="bg-white rounded-lg shadow p-6 space-y-4">
            <div><label class="block font-bold mb-2">Nombre</label><input type="text" name="nombre" value="{{ old('nombre') }}" class="w-full p-4 border-2 rounded-lg" required></div>
            <div><label class="block font-bold mb-2">Capacidad</label><input type="number" name="capacidad" value="{{ old('capacidad') }}" class="w-full p-4 border-2 rounded-lg" required min="1"></div>
            <div><label class="block font-bold mb-2">Fecha Inicio</label><input type="date" name="fecha_inicio" value="{{ old('fecha_inicio', date('Y-m-d')) }}" class="w-full p-4 border-2 rounded-lg" required></div>
            <div><label class="block font-bold mb-2">Observaciones</label><textarea name="observaciones" rows="3" class="w-full p-4 border-2 rounded-lg">{{ old('observaciones') }}</textarea></div>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="flex-1 bg-blue-600 text-white py-4 rounded-lg font-bold">💾 Guardar</button>
            <a href="{{ route('galpones.index') }}" class="bg-gray-300 px-6 py-4 rounded-lg font-bold">← Volver</a>
        </div>
    </form>
</div>
@endsection
