@extends('layouts.app')

@section('title', 'Registrar Asistencia')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-2">✅ Nueva Asistencia</h1>
        <p class="text-gray-600">Registrar asistencia individual</p>
    </div>

    <!-- Mensajes de error -->
    @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded">
            <p class="font-bold text-red-800 mb-2">⚠️ Errores de validación:</p>
            <ul class="list-disc list-inside text-red-700">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded">
            <p class="text-red-800 font-semibold">{{ session('error') }}</p>
        </div>
    @endif

    <!-- Formulario -->
    <form action="{{ route('asistencias.store') }}" method="POST" class="space-y-4">
        @csrf

        <div class="bg-white rounded-lg shadow p-6 space-y-4">
            <!-- Operario -->
            <div>
                <label class="block text-lg font-bold text-gray-700 mb-2">👷 Operario</label>
                <select name="operario_id" class="w-full p-4 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none" required>
                    <option value="">Seleccionar operario...</option>
                    @foreach($operarios as $operario)
                        <option value="{{ $operario->id }}" {{ old('operario_id') == $operario->id ? 'selected' : '' }}>
                            {{ $operario->nombre_completo }} - DNI: {{ $operario->dni }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Fecha -->
            <div>
                <label class="block text-lg font-bold text-gray-700 mb-2">📅 Fecha</label>
                <input type="date" 
                       name="fecha" 
                       value="{{ old('fecha', $fecha) }}"
                       class="w-full p-4 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
                       required>
            </div>

            <!-- Asistió -->
            <div>
                <label class="block text-lg font-bold text-gray-700 mb-2">Estado</label>
                <div class="flex gap-4">
                    <label class="flex-1 cursor-pointer">
                        <input type="radio" name="asistio" value="1" class="hidden peer" {{ old('asistio', '1') == '1' ? 'checked' : '' }} required>
                        <div class="border-2 border-gray-300 peer-checked:border-green-500 peer-checked:bg-green-50 rounded-lg p-4 text-center transition">
                            <span class="text-4xl">✓</span>
                            <p class="font-bold mt-2">Asistió</p>
                        </div>
                    </label>
                    <label class="flex-1 cursor-pointer">
                        <input type="radio" name="asistio" value="0" class="hidden peer" {{ old('asistio') == '0' ? 'checked' : '' }}>
                        <div class="border-2 border-gray-300 peer-checked:border-red-500 peer-checked:bg-red-50 rounded-lg p-4 text-center transition">
                            <span class="text-4xl">✗</span>
                            <p class="font-bold mt-2">Faltó</p>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Observaciones -->
            <div>
                <label class="block text-lg font-bold text-gray-700 mb-2">📝 Observaciones (opcional)</label>
                <textarea name="observaciones" 
                          rows="3" 
                          class="w-full p-4 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
                          placeholder="Motivo de inasistencia u observaciones...">{{ old('observaciones') }}</textarea>
            </div>
        </div>

        <!-- Botones -->
        <div class="flex gap-3">
            <button type="submit" class="flex-1 bg-blue-600 text-white py-4 px-6 rounded-lg text-xl font-bold hover:bg-blue-700 transition shadow-lg">
                💾 Guardar
            </button>
            <a href="{{ route('asistencias.index') }}" class="bg-gray-300 text-gray-700 py-4 px-6 rounded-lg text-xl font-bold hover:bg-gray-400 transition">
                ← Volver
            </a>
        </div>
    </form>
</div>
@endsection
