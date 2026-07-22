@extends('layouts.app')
@section('title', 'Detalle de Venta')
@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6"><h1 class="text-2xl font-bold">💰 Detalle de Venta</h1><p class="text-gray-600">Vista de detalle</p></div>
    <div class="mt-6"><a href="{{ route('ventas.index') }}" class="bg-gray-300 px-6 py-3 rounded">← Volver</a></div>
</div>
@endsection
