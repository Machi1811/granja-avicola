@extends('layouts.app')
@section('title', 'Operarios')
@section('content')
<div class="max-w-6xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex justify-between items-center">
            <div><h1 class="text-2xl font-bold">👷 Operarios</h1><p class="text-gray-600">Gestión de personal</p></div>
            <a href="{{ route('operarios.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-blue-700">+ Nuevo</a>
        </div>
    </div>
    @if(session('success'))<div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded"><p class="text-green-800 font-semibold">{{ session('success') }}</p></div>@endif
    <div class="bg-white rounded-lg shadow overflow-hidden">
        @if($operarios->count() === 0)
            <div class="p-12 text-center"><p class="text-gray-500 mb-4">No hay operarios registrados</p><a href="{{ route('operarios.create') }}" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg">Registrar Operario</a></div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50"><tr><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">DNI</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Teléfono</th><th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Estado</th><th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Pago Diario</th><th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Acciones</th></tr></thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($operarios as $operario)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4"><span class="font-medium">{{ $operario->nombre_completo }}</span></td>
                                <td class="px-6 py-4 text-gray-500">{{ $operario->dni }}</td>
                                <td class="px-6 py-4 text-gray-500">{{ $operario->telefono }}</td>
                                <td class="px-6 py-4 text-center"><span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $operario->estado === 'activo' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">{{ ucfirst($operario->estado) }}</span></td>
                                <td class="px-6 py-4 text-right"><span class="font-bold text-green-600">S/. {{ number_format($operario->pago_diario, 2) }}</span></td>
                                <td class="px-6 py-4 text-center"><div class="flex justify-center gap-2"><a href="{{ route('operarios.show', $operario) }}" class="text-blue-600 hover:text-blue-800">👁️</a><a href="{{ route('operarios.edit', $operario) }}" class="text-yellow-600 hover:text-yellow-800">✏️</a></div></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
