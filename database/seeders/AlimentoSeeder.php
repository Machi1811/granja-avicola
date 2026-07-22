<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Alimento;
use Carbon\Carbon;

class AlimentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Alimento de postura con stock bajo (alerta)
        Alimento::firstOrCreate(
            ['tipo' => 'postura'],
            [
                'quintales_stock' => 1.5, // Menor a 2 quintales = ALERTA
                'kg_stock' => 1.5 * 46, // Se calculará automáticamente pero lo ponemos
                'fecha_ultima_compra' => Carbon::now()->subDays(15),
                'quintales_ultima_compra' => 10,
                'precio_quintal' => 180.00
            ]
        );

        // Alimento de engorde con stock adecuado
        Alimento::firstOrCreate(
            ['tipo' => 'engorde'],
            [
                'quintales_stock' => 8.0,
                'kg_stock' => 8.0 * 46,
                'fecha_ultima_compra' => Carbon::now()->subDays(5),
                'quintales_ultima_compra' => 15,
                'precio_quintal' => 175.00
            ]
        );
    }
}
