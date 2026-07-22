@extends('layouts.app')
@section('title', 'Editar Operario')
@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h1 class="text-2xl font-bold">✏️ Editar Operario</h1>
    </div>
    
    @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded">
            <p class="font-bold text-red-800 mb-2">⚠️ Errores:</p>
            <ul class="list-disc list-inside text-red-700">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form action="{{ route('operarios.update', $operario) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')
        <div class="bg-white rounded-lg shadow p-6 space-y-4">
            <div>
                <label class="block font-bold mb-2">Nombre *</label>
                <input type="text" name="nombre" value="{{ old('nombre', $operario->nombre) }}" class="w-full p-4 border-2 rounded-lg focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div>
                <label class="block font-bold mb-2">Apellido *</label>
                <input type="text" name="apellido" value="{{ old('apellido', $operario->apellido) }}" class="w-full p-4 border-2 rounded-lg focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div>
                <label class="block font-bold mb-2">DNI *</label>
                <input type="text" name="dni" value="{{ old('dni', $operario->dni) }}" maxlength="8" class="w-full p-4 border-2 rounded-lg focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div>
                <label class="block font-bold mb-2">Teléfono</label>
                <input type="text" name="telefono" value="{{ old('telefono', $operario->telefono) }}" class="w-full p-4 border-2 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block font-bold mb-2">Fecha de Ingreso *</label>
                <input type="date" name="fecha_ingreso" value="{{ old('fecha_ingreso', $operario->fecha_ingreso->format('Y-m-d')) }}" class="w-full p-4 border-2 rounded-lg focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div>
                <label class="block font-bold mb-2">Pago Diario (S/.) *</label>
                <input type="number" step="0.01" name="pago_diario" value="{{ old('pago_diario', $operario->pago_diario) }}" class="w-full p-4 border-2 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                <p class="text-sm text-gray-600 mt-1">RF-06: Pago estándar S/ 80.00 por día</p>
            </div>
            <div>
                <label class="block font-bold mb-2">Estado *</label>
                <select name="estado" class="w-full p-4 border-2 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                    <option value="activo" {{ old('estado', $operario->estado) === 'activo' ? 'selected' : '' }}>Activo</option>
                    <option value="inactivo" {{ old('estado', $operario->estado) === 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                </select>
            </div>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="flex-1 bg-blue-600 text-white py-4 rounded-lg font-bold hover:bg-blue-700">💾 Actualizar</button>
            <a href="{{ route('operarios.show', $operario) }}" class="bg-gray-300 px-6 py-4 rounded-lg font-bold hover:bg-gray-400 text-center">← Cancelar</a>
        </div>
    </form>
</div>
@endsection
