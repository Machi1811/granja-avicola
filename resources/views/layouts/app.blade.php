<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Granja Avícola') }} - @yield('title')</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Navegación Mobile First -->
    <nav class="bg-green-600 text-white shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <a href="{{ route('dashboard') }}" class="text-xl font-bold">
                    🐔 Granja Avícola
                </a>
                <button id="menu-toggle" class="lg:hidden focus:outline-none">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
            
            <!-- Menu Móvil -->
            <div id="mobile-menu" class="hidden mt-4 pb-2 space-y-2">
                <a href="{{ route('dashboard') }}" class="block py-2 px-3 rounded hover:bg-green-700">
                    📊 Dashboard
                </a>
                <a href="{{ route('produccion.registro-rapido') }}" class="block py-2 px-3 rounded hover:bg-green-700 bg-green-700">
                    ⚡ Registro Rápido
                </a>
                <a href="{{ route('galpones.index') }}" class="block py-2 px-3 rounded hover:bg-green-700">
                    🏠 Galpones
                </a>
                <a href="{{ route('produccion.index') }}" class="block py-2 px-3 rounded hover:bg-green-700">
                    🥚 Producción
                </a>
                <a href="{{ route('pollos.index') }}" class="block py-2 px-3 rounded hover:bg-green-700">
                    🐥 Pollos Engorde
                </a>
                <a href="{{ route('alimentos.index') }}" class="block py-2 px-3 rounded hover:bg-green-700">
                    🌾 Alimentos
                </a>
                <a href="{{ route('ventas.index') }}" class="block py-2 px-3 rounded hover:bg-green-700">
                    💰 Ventas
                </a>
                <a href="{{ route('operarios.index') }}" class="block py-2 px-3 rounded hover:bg-green-700">
                    👷 Operarios
                </a>
                <a href="{{ route('asistencias.registro-diario') }}" class="block py-2 px-3 rounded hover:bg-green-700">
                    ✅ Asistencia Diaria
                </a>
            </div>
        </div>
    </nav>

    <!-- Alertas -->
    @if(session('success'))
        <div class="container mx-auto px-4 mt-4">
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow" role="alert">
                <p class="font-bold">✓ Éxito</p>
                <p>{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="container mx-auto px-4 mt-4">
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow" role="alert">
                <p class="font-bold">✗ Error</p>
                <p>{{ session('error') }}</p>
            </div>
        </div>
    @endif

    @if(session('warning'))
        <div class="container mx-auto px-4 mt-4">
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded shadow" role="alert">
                <p class="font-bold">⚠ Advertencia</p>
                <p>{{ session('warning') }}</p>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="container mx-auto px-4 mt-4">
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow">
                <p class="font-bold">⚠ Errores de validación:</p>
                <ul class="mt-2 list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- Contenido Principal -->
    <main class="container mx-auto px-4 py-6">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white text-center py-4 mt-8">
        <p>&copy; {{ date('Y') }} Granja Avícola - Sistema de Gestión</p>
        <p class="text-sm text-gray-400 mt-1">Versión MVP 1.0</p>
    </footer>

    <script>
        // Toggle menú móvil
        document.getElementById('menu-toggle').addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });

        // Auto-ocultar alertas después de 5 segundos
        setTimeout(function() {
            const alerts = document.querySelectorAll('[role="alert"]');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    </script>

    @stack('scripts')
</body>
</html>
