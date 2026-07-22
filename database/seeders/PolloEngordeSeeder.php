<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PolloEngorde;
use Carbon\Carbon;

class PolloEngordeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // RF-04: Lotes con diferentes estados del ciclo de 120 días

        // Lote 1: En crecimiento, 45 días transcurridos
        PolloEngorde::create([
            'codigo_lote' => 'LOTE-0001',
            'cantidad_inicial' => 500,
            'cantidad_actual' => 495,
            'fecha_ingreso' => Carbon::now()->subDays(45),
            'dias_transcurridos' => 45,
            'estado' => 'crecimiento',
            'consumo_total_kg' => 850.50,
            'observaciones' => 'Lote en desarrollo normal'
        ]);

        // Lote 2: Próximo a completar ciclo, 115 días (faltan 5)
        PolloEngorde::create([
            'codigo_lote' => 'LOTE-0002',
            'cantidad_inicial' => 400,
            'cantidad_actual' => 390,
            'fecha_ingreso' => Carbon::now()->subDays(115),
            'dias_transcurridos' => 115,
            'estado' => 'crecimiento',
            'consumo_total_kg' => 1850.75,
            'observaciones' => 'Próximo a completar 120 días'
        ]);

        // Lote 3: Listo para venta, completó 120 días
        PolloEngorde::create([
            'codigo_lote' => 'LOTE-0003',
            'cantidad_inicial' => 350,
            'cantidad_actual' => 345,
            'fecha_ingreso' => Carbon::now()->subDays(120),
            'dias_transcurridos' => 120,
            'estado' => 'listo_venta',
            'consumo_total_kg' => 1950.00,
            'observaciones' => 'Ciclo completo - Listo para venta'
        ]);

        // Lote 4: En crecimiento temprano, 20 días
        PolloEngorde::create([
            'codigo_lote' => 'LOTE-0004',
            'cantidad_inicial' => 600,
            'cantidad_actual' => 598,
            'fecha_ingreso' => Carbon::now()->subDays(20),
            'dias_transcurridos' => 20,
            'estado' => 'crecimiento',
            'consumo_total_kg' => 420.25,
            'observaciones' => 'Lote reciente'
        ]);

        // Lote 5: Vendido, completó ciclo hace 10 días
        PolloEngorde::create([
            'codigo_lote' => 'LOTE-0005',
            'cantidad_inicial' => 450,
            'cantidad_actual' => 445,
            'fecha_ingreso' => Carbon::now()->subDays(130),
            'dias_transcurridos' => 130,
            'estado' => 'vendido',
            'consumo_total_kg' => 2100.50,
            'peso_venta_kg' => 890.00,
            'fecha_venta' => Carbon::now()->subDays(10),
            'observaciones' => 'Vendido exitosamente'
        ]);
    }
}
