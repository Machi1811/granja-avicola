<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ponedora;
use App\Models\ProduccionDiaria;
use App\Models\Galpon;
use Carbon\Carbon;

class PonedoraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener galpones activos
        $galponA = Galpon::where('nombre', 'Galpón A')->first();
        $galponB = Galpon::where('nombre', 'Galpón B')->first();
        $galponC = Galpon::where('nombre', 'Galpón C')->first();

        // RF-01: Población inicial de 500 ponedoras (distribuidas en galpones)
        
        // Galpón A: 250 ponedoras
        $ponedoraA = Ponedora::create([
            'galpon_id' => $galponA->id,
            'cantidad_inicial' => 250,
            'cantidad_actual' => 245, // Algunas vendidas
            'fecha_ingreso' => Carbon::now()->subMonths(20),
            'estado' => 'activo'
        ]);

        // Galpón B: 150 ponedoras
        $ponedoraB = Ponedora::create([
            'galpon_id' => $galponB->id,
            'cantidad_inicial' => 150,
            'cantidad_actual' => 150,
            'fecha_ingreso' => Carbon::now()->subMonths(34),
            'estado' => 'activo'
        ]);

        // Galpón C: 100 ponedoras
        $ponedoraC = Ponedora::create([
            'galpon_id' => $galponC->id,
            'cantidad_inicial' => 100,
            'cantidad_actual' => 100,
            'fecha_ingreso' => Carbon::now()->subMonths(10),
            'estado' => 'activo'
        ]);

        // Crear producción diaria de los últimos 5 días para Galpón A
        for ($i = 4; $i >= 0; $i--) {
            $fecha = Carbon::now()->subDays($i);
            $avesActivas = 245;
            $produccionTeorica = $avesActivas;
            
            // RF-01: El sistema calcula automáticamente la merma del 10%
            ProduccionDiaria::create([
                'galpon_id' => $galponA->id,
                'fecha' => $fecha,
                'aves_activas' => $avesActivas,
                'produccion_teorica' => $produccionTeorica,
                // produccion_neta se calcula automáticamente (90% de teórica)
                'produccion_real' => rand(210, 230), // Producción real varía
                'observaciones' => $i === 0 ? 'Producción del día de hoy' : null
            ]);
        }

        // Crear producción para Galpón B (últimos 3 días)
        for ($i = 2; $i >= 0; $i--) {
            $fecha = Carbon::now()->subDays($i);
            $avesActivas = 150;
            
            ProduccionDiaria::create([
                'galpon_id' => $galponB->id,
                'fecha' => $fecha,
                'aves_activas' => $avesActivas,
                'produccion_teorica' => $avesActivas,
                'produccion_real' => rand(130, 140)
            ]);
        }
    }
}
