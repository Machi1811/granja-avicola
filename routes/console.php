<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/**
 * Tareas Programadas del Sistema de Granja Avícola
 * 
 * RF-04: Actualizar ciclos de pollos de engorde (120 días)
 * RF-05: Verificar galpones para descarte (36 meses)
 * RF-02: Verificar stock de alimentos (alerta < 2 quintales)
 */

// Actualizar ciclos de pollos diariamente a las 00:01
Schedule::command('pollos:actualizar-ciclos')
    ->dailyAt('00:01')
    ->timezone('America/Lima');

// Verificar galpones para descarte diariamente a las 00:05
Schedule::command('galpones:verificar-descarte')
    ->dailyAt('00:05')
    ->timezone('America/Lima');

// Verificar stock de alimentos dos veces al día (6:00 AM y 6:00 PM)
Schedule::command('alimentos:verificar-stock')
    ->twiceDaily(6, 18)
    ->timezone('America/Lima');
