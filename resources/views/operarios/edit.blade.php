@extends('layouts.app')
@section('title', 'Editar Operario')
@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6 mb-6"><h1 class="text-2xl font-bold">✏️ Editar Operario</h1></div>
    <form action="{{ route('operarios.update', $operario) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')
        <div class="bg-white rounded-lg shadow p-6 space-y-4">
            <div><label class="block font-bold mb-2">Nombre</label><input type="text" name="nombre" value="{{ $operario->nombre }}" class="w-full p-4 border-2 rounded-lg" required></div>
            <div><label class="block font-bold mb-2">Apellido</label><input type="text" name="apellido" value="{{ $operario->apellido }}" class="w-full p-4 border-2 rounded-lg" required></div>
            <div><label class="block font-bold mb-2">DNI</label><input type="text" name="dni" value="{{ $operario->dni }}" maxlength="8" class="w-full p-4 border-2 rounded-lg" required></div>
            <div><label class="block font-bold mb-2">Teléfono</label><input type="text" name="telefono" value="{{ $operario->telefono }}" class="w-full p-4 border-2 rounded-lg"></div>
            <div><label class="block font-bold mb-2">Pago Diario</label><input type="number" step="0.01" name="pago_diario" value="{{ $operario->pago_diario }}" class="w-full p-4 border-2 rounded-lg" required></div>
            <div><label class="block font-bold mb-2">Estado</label><select name="estado" class="w-full p-4 border-2 rounded-lg"><option value="activo" {{ $operario->estado === 'activo' ? 'selected' : '' }}>Activo</option><option value="inactivo" {{ $operario->estado === 'inactivo' ? 'selected' : '' }}>Inactivo</option></select></div>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="flex-1 bg-blue-600 text-white py-4 rounded-lg font-bold">💾 Actualizar</button>
            <a href="{{ route('operarios.show', $operario) }}" class="bg-gray-300 px-6 py-4 rounded-lg font-bold">← Cancelar</a>
        </div>
    </form>
</div>
@endsection
