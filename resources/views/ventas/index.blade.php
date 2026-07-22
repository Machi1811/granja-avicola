@extends('layouts.app')
@section('title', 'Ventas')
@section('content')
<div class="max-w-6xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex justify-between items-center"><div><h1 class="text-2xl font-bold">💰 Ventas</h1><p class="text-gray-600">Historial de ventas</p></div><a href="{{ route('ventas.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-bold">+ Nueva Venta</a></div>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-center text-gray-500">No hay ventas registradas</p>
    </div>
</div>
@endsection
