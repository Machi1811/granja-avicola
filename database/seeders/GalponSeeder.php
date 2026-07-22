<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Galpon;
use Carbon\Carbon;

class GalponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Galpón 1: Activo, 20 meses (sin alerta)
        Galpon::create([
            'nombre' => 'Galpón A',
            'capacidad' => 300,
            'fecha_inicio' => Carbon::now()->subMonths(20),
            'estado' => 'activo',
            'observaciones' => 'Galpón principal para ponedoras'
        ]);

        // Galpón 2: Activo, 34 meses (alerta próxima a descarte)
        Galpon::create([
            'nombre' => 'Galpón B',
            'capacidad' => 200,
            'fecha_inicio' => Carbon::now()->subMonths(34),
            'estado' => 'activo',
            'observaciones' => 'Próximo a cumplir 36 meses'
        ]);

        // Galpón 3: Activo, 10 meses (sin alerta)
        Galpon::create([
            'nombre' => 'Galpón C',
            'capacidad' => 250,
            'fecha_inicio' => Carbon::now()->subMonths(10),
            'estado' => 'activo',
            'observaciones' => 'Galpón nuevo'
        ]);

        // Galpón 4: Descarte, 38 meses (superó límite)
        Galpon::create([
            'nombre' => 'Galpón D',
            'capacidad' => 150,
            'fecha_inicio' => Carbon::now()->subMonths(38),
            'estado' => 'descarte',
            'observaciones' => 'Superó los 36 meses, en proceso de descarte'
        ]);
    }
}
