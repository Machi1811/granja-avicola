@extends('layouts.app')
@section('title', 'Registrar Alimento')
@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6 mb-6"><h1 class="text-2xl font-bold">🌾 Registrar Nuevo Tipo de Alimento</h1></div>
    <form action="{{ route('alimentos.store') }}" method="POST" class="space-y-4">
        @csrf
        <div class="bg-white rounded-lg shadow p-6 space-y-4">
            <div><label class="block font-bold mb-2">Tipo</label><select name="tipo" class="w-full p-4 border-2 rounded-lg" required><option value="postura">Postura</option><option value="engorde">Engorde</option></select></div>
            <div><label class="block font-bold mb-2">Stock Inicial (quintales)</label><input type="number" step="0.01" name="quintales_stock" class="w-full p-4 border-2 rounded-lg" required></div>
            <div><label class="block font-bold mb-2">Precio por Quintal (S/.)</label><input type="number" step="0.01" name="precio_quintal" class="w-full p-4 border-2 rounded-lg" required></div>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="flex-1 bg-blue-600 text-white py-4 rounded-lg font-bold">💾 Guardar</button>
            <a href="{{ route('alimentos.index') }}" class="bg-gray-300 px-6 py-4 rounded-lg font-bold">← Volver</a>
        </div>
    </form>
</div>
@endsection
