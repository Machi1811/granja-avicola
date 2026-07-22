<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GalponController;
use App\Http\Controllers\PonedoraController;
use App\Http\Controllers\ProduccionDiariaController;
use App\Http\Controllers\AlimentoController;
use App\Http\Controllers\PolloEngordeController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\OperarioController;
use App\Http\Controllers\AsistenciaController;

// Dashboard Principal
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Ruta para Scheduler (Cron externo)
Route::get('/artisan/schedule', function() {
    Artisan::call('schedule:run');
    return response()->json([
        'success' => true,
        'message' => 'Scheduler ejecutado correctamente',
        'time' => now()->toDateTimeString()
    ]);
})->middleware('throttle:120,1');

// Galpones
Route::resource('galpones', GalponController::class);

// Ponedoras
Route::resource('ponedoras', PonedoraController::class);

// Producción Diaria
Route::resource('produccion', ProduccionDiariaController::class);
Route::get('/produccion-rapida', [ProduccionDiariaController::class, 'registroRapido'])
    ->name('produccion.registro-rapido');

// Alimentos
Route::resource('alimentos', AlimentoController::class);
Route::get('/alimentos/{alimento}/compra', [AlimentoController::class, 'formCompra'])
    ->name('alimentos.form-compra');
Route::post('/alimentos/{alimento}/compra', [AlimentoController::class, 'registrarCompra'])
    ->name('alimentos.registrar-compra');
Route::get('/alimentos/{alimento}/historial', [AlimentoController::class, 'historialConsumo'])
    ->name('alimentos.historial');

// Pollos de Engorde
Route::resource('pollos', PolloEngordeController::class);
Route::get('/pollos/{pollo}/venta', [PolloEngordeController::class, 'formVenta'])
    ->name('pollos.form-venta');
Route::post('/pollos/{pollo}/venta', [PolloEngordeController::class, 'registrarVenta'])
    ->name('pollos.registrar-venta');
Route::post('/pollos/{pollo}/consumo', [PolloEngordeController::class, 'registrarConsumo'])
    ->name('pollos.registrar-consumo');

// Ventas
Route::resource('ventas', VentaController::class);
Route::get('/ventas-ponedoras', [VentaController::class, 'ventaPonedoras'])
    ->name('ventas.ponedoras');
Route::post('/calcular-precio', [VentaController::class, 'calcularPrecio'])
    ->name('ventas.calcular-precio');

// Operarios
Route::resource('operarios', OperarioController::class);
Route::get('/operarios-reporte-pagos', [OperarioController::class, 'reportePagos'])
    ->name('operarios.reporte-pagos');

// Asistencias
Route::resource('asistencias', AsistenciaController::class);
Route::get('/asistencia-diaria', [AsistenciaController::class, 'registroDiario'])
    ->name('asistencias.registro-diario');
Route::post('/asistencia-diaria', [AsistenciaController::class, 'guardarRegistroDiario'])
    ->name('asistencias.guardar-registro-diario');
Route::get('/asistencias-operario/{operario}', [AsistenciaController::class, 'reporteOperario'])
    ->name('asistencias.reporte-operario');
