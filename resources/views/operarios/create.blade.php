@extends('layouts.app')
@section('title', 'Nuevo Operario')
@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6 mb-6"><h1 class="text-2xl font-bold">👷 Nuevo Operario</h1></div>
    @if($errors->any())<div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded"><p class="font-bold text-red-800 mb-2">⚠️ Errores:</p><ul class="list-disc list-inside text-red-700">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    <form action="{{ route('operarios.store') }}" method="POST" class="space-y-4">
        @csrf
        <div class="bg-white rounded-lg shadow p-6 space-y-4">
            <div><label class="block font-bold mb-2">Nombre</label><input type="text" name="nombre" value="{{ old('nombre') }}" class="w-full p-4 border-2 rounded-lg" required></div>
            <div><label class="block font-bold mb-2">Apellido</label><input type="text" name="apellido" value="{{ old('apellido') }}" class="w-full p-4 border-2 rounded-lg" required></div>
            <div><label class="block font-bold mb-2">DNI</label><input type="text" name="dni" value="{{ old('dni') }}" maxlength="8" class="w-full p-4 border-2 rounded-lg" required></div>
            <div><label class="block font-bold mb-2">Teléfono</label><input type="text" name="telefono" value="{{ old('telefono') }}" class="w-full p-4 border-2 rounded-lg"></div>
            <div><label class="block font-bold mb-2">Pago Diario (S/.)</label><input type="number" step="0.01" name="pago_diario" value="{{ old('pago_diario', '80.00') }}" class="w-full p-4 border-2 rounded-lg" required></div>
            <div><label class="block font-bold mb-2">Fecha Ingreso</label><input type="date" name="fecha_ingreso" value="{{ old('fecha_ingreso', date('Y-m-d')) }}" class="w-full p-4 border-2 rounded-lg" required></div>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="flex-1 bg-blue-600 text-white py-4 rounded-lg font-bold">💾 Guardar</button>
            <a href="{{ route('operarios.index') }}" class="bg-gray-300 px-6 py-4 rounded-lg font-bold">← Volver</a>
        </div>
    </form>
</div>
@endsection
