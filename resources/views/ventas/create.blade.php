@extends('layouts.app')

@section('title', 'Nueva Venta')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-2">💰 Nueva Venta</h1>
        <p class="text-gray-600">Registrar venta de productos de la granja</p>
    </div>

    <!-- Formulario -->
    <form action="{{ route('ventas.store') }}" method="POST" class="space-y-4" id="form-venta">
        @csrf

        <!-- Fecha -->
        <div class="bg-white rounded-lg shadow p-6">
            <label class="block text-lg font-bold text-gray-700 mb-3">📅 Fecha</label>
            <input type="date" 
                   name="fecha" 
                   value="{{ date('Y-m-d') }}"
                   class="w-full text-xl p-4 border-2 border-gray-300 rounded-lg focus:border-green-500 focus:outline-none"
                   required>
        </div>

        <!-- Tipo de Producto -->
        <div class="bg-white rounded-lg shadow p-6">
            <label class="block text-lg font-bold text-gray-700 mb-3">📦 Tipo de Producto</label>
            <select name="tipo_producto" 
                    id="tipo_producto"
                    class="w-full text-xl p-4 border-2 border-gray-300 rounded-lg focus:border-green-500 focus:outline-none"
                    required>
                <option value="">Seleccione tipo de producto</option>
                <option value="ponedora_viva">🐔 Gallina Ponedora Viva</option>
                <option value="huevos">🥚 Huevos</option>
                <option value="pollo_engorde">🐥 Pollo de Engorde</option>
                <option value="carne_gallina">🍗 Carne de Gallina (Descarte)</option>
            </select>
        </div>

        <!-- Tarificador Ponedoras (RF-03) - Se muestra solo para ponedoras -->
        <div id="tarificador" class="hidden bg-yellow-50 border-l-4 border-yellow-500 p-6 rounded-lg">
            <h3 class="font-bold text-yellow-800 mb-3">💡 Tarificador Automático (RF-03)</h3>
            <div class="space-y-2 text-yellow-700 text-sm">
                <p><strong>De 1 a 12 gallinas:</strong> S/. 35.00 por unidad</p>
                <p><strong>Más de 12 gallinas:</strong> S/. 30.00 por unidad</p>
                <p class="mt-2 text-xs italic">El precio se calculará automáticamente según la cantidad</p>
            </div>
        </div>

        <!-- Cantidad -->
        <div class="bg-white rounded-lg shadow p-6">
            <label class="block text-lg font-bold text-gray-700 mb-3">🔢 Cantidad</label>
            <input type="number" 
                   name="cantidad" 
                   id="cantidad"
                   class="w-full text-3xl p-4 border-2 border-gray-300 rounded-lg focus:border-green-500 focus:outline-none text-center font-bold"
                   min="0.01"
                   step="0.01"
                   placeholder="0"
                   required>
        </div>

        <!-- Unidad de Medida -->
        <div class="bg-white rounded-lg shadow p-6">
            <label class="block text-lg font-bold text-gray-700 mb-3">📏 Unidad de Medida</label>
            <select name="unidad_medida" 
                    id="unidad_medida"
                    class="w-full text-xl p-4 border-2 border-gray-300 rounded-lg focus:border-green-500 focus:outline-none"
                    required>
                <option value="unidad">Unidad</option>
                <option value="docena">Docena</option>
                <option value="kg">Kilogramo (kg)</option>
            </select>
        </div>

        <!-- Precio Unitario -->
        <div class="bg-white rounded-lg shadow p-6">
            <label class="block text-lg font-bold text-gray-700 mb-3">💵 Precio Unitario</label>
            <div class="flex items-center gap-2">
                <span class="text-2xl font-bold">S/.</span>
                <input type="number" 
                       name="precio_unitario" 
                       id="precio_unitario"
                       class="flex-1 text-3xl p-4 border-2 border-gray-300 rounded-lg focus:border-green-500 focus:outline-none text-center font-bold"
                       min="0"
                       step="0.01"
                       placeholder="0.00">
            </div>
            <p class="text-sm text-gray-500 mt-2">Para ponedoras, se calcula automáticamente</p>
        </div>

        <!-- Total Calculado -->
        <div class="bg-green-50 border-l-4 border-green-500 p-6 rounded-lg">
            <p class="font-bold text-green-800 mb-2">💰 Total de la Venta</p>
            <p class="text-4xl font-bold text-green-700">
                S/. <span id="total_venta">0.00</span>
            </p>
        </div>

        <!-- Cliente -->
        <div class="bg-white rounded-lg shadow p-6">
            <label class="block text-lg font-bold text-gray-700 mb-3">👤 Cliente (Opcional)</label>
            <input type="text" 
                   name="cliente" 
                   class="w-full text-lg p-4 border-2 border-gray-300 rounded-lg focus:border-green-500 focus:outline-none"
                   placeholder="Nombre del cliente">
        </div>

        <!-- Observaciones -->
        <div class="bg-white rounded-lg shadow p-6">
            <label class="block text-lg font-bold text-gray-700 mb-3">📝 Observaciones</label>
            <textarea name="observaciones" 
                      rows="3"
                      class="w-full text-lg p-4 border-2 border-gray-300 rounded-lg focus:border-green-500 focus:outline-none"
                      placeholder="Notas adicionales"></textarea>
        </div>

        <!-- Botones -->
        <div class="flex gap-3">
            <button type="submit" class="flex-1 bg-green-600 text-white py-4 px-6 rounded-lg text-xl font-bold hover:bg-green-700 transition shadow-lg">
                💾 Registrar Venta
            </button>
            <a href="{{ route('ventas.index') }}" class="bg-gray-300 text-gray-700 py-4 px-6 rounded-lg text-xl font-bold hover:bg-gray-400 transition">
                ✕
            </a>
        </div>
    </form>
</div>

@push('scripts')
<script>
    const tipoProducto = document.getElementById('tipo_producto');
    const cantidad = document.getElementById('cantidad');
    const precioUnitario = document.getElementById('precio_unitario');
    const totalVenta = document.getElementById('total_venta');
    const tarificador = document.getElementById('tarificador');
    const unidadMedida = document.getElementById('unidad_medida');

    // Mostrar/ocultar tarificador y calcular precio
    tipoProducto.addEventListener('change', function() {
        if (this.value === 'ponedora_viva') {
            tarificador.classList.remove('hidden');
            unidadMedida.value = 'unidad';
            calcularPrecioPonedora();
        } else {
            tarificador.classList.add('hidden');
            precioUnitario.readOnly = false;
        }
        calcularTotal();
    });

    // Calcular precio para ponedoras según RF-03
    cantidad.addEventListener('input', function() {
        if (tipoProducto.value === 'ponedora_viva') {
            calcularPrecioPonedora();
        }
        calcularTotal();
    });

    precioUnitario.addEventListener('input', calcularTotal);

    function calcularPrecioPonedora() {
        const cant = parseFloat(cantidad.value) || 0;
        let precio;
        
        // RF-03: Tarificador de Venta de Aves
        if (cant > 12) {
            precio = 30.00; // Más de una docena
        } else {
            precio = 35.00; // Hasta una docena
        }
        
        precioUnitario.value = precio.toFixed(2);
        precioUnitario.readOnly = true;
    }

    function calcularTotal() {
        const cant = parseFloat(cantidad.value) || 0;
        const precio = parseFloat(precioUnitario.value) || 0;
        const total = cant * precio;
        totalVenta.textContent = total.toFixed(2);
    }
</script>
@endpush
@endsection
