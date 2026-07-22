@extends('layouts.app')
@section('title', 'Editar Ponedora')
@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold mb-6">🐔 Editar Lote de Ponedoras</h1>
        
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('ponedoras.update', $ponedora) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-sm font-medium mb-2">Galpón *</label>
                <select name="galpon_id" required class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Seleccione un galpón</option>
                    @foreach($galpones as $galpon)
                        <option value="{{ $galpon->id }}" {{ old('galpon_id', $ponedora->galpon_id) == $galpon->id ? 'selected' : '' }}>
                            {{ $galpon->nombre }} (Capacidad: {{ $galpon->capacidad }} aves)
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Cantidad Inicial</label>
                <input type="number" value="{{ $ponedora->cantidad_inicial }}" disabled 
                    class="w-full px-3 py-2 border rounded-lg bg-gray-100">
                <p class="text-sm text-gray-500 mt-1">Este valor no se puede editar</p>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Cantidad Actual *</label>
                <input type="number" name="cantidad_actual" value="{{ old('cantidad_actual', $ponedora->cantidad_actual) }}" min="0" required 
                    class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Fecha de Ingreso *</label>
                <input type="date" name="fecha_ingreso" value="{{ old('fecha_ingreso', $ponedora->fecha_ingreso->format('Y-m-d')) }}" required 
                    class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Estado *</label>
                <select name="estado" required class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="activo" {{ old('estado', $ponedora->estado) == 'activo' ? 'selected' : '' }}>Activo</option>
                    <option value="vendido" {{ old('estado', $ponedora->estado) == 'vendido' ? 'selected' : '' }}>Vendido</option>
                    <option value="descarte" {{ old('estado', $ponedora->estado) == 'descarte' ? 'selected' : '' }}>Descarte</option>
                </select>
            </div>

            <div class="flex gap-3 pt-4">
                <button type="submit" class="flex-1 bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                    ✅ Actualizar
                </button>
                <a href="{{ route('ponedoras.show', $ponedora) }}" class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 text-center">
                    ❌ Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
