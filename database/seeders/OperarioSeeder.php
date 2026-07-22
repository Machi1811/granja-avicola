<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Operario;
use App\Models\Asistencia;
use Carbon\Carbon;

class OperarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Operario 1: Juan Pérez
        $operario1 = Operario::create([
            'nombre' => 'Juan',
            'apellido' => 'Pérez García',
            'dni' => '12345678',
            'telefono' => '987654321',
            'pago_diario' => 80.00,
            'estado' => 'activo',
            'fecha_ingreso' => Carbon::now()->subMonths(6)
        ]);

        // Operario 2: María López
        $operario2 = Operario::create([
            'nombre' => 'María',
            'apellido' => 'López Torres',
            'dni' => '87654321',
            'telefono' => '912345678',
            'pago_diario' => 80.00,
            'estado' => 'activo',
            'fecha_ingreso' => Carbon::now()->subMonths(8)
        ]);

        // Operario 3: Carlos Rodríguez
        $operario3 = Operario::create([
            'nombre' => 'Carlos',
            'apellido' => 'Rodríguez Sánchez',
            'dni' => '45678912',
            'telefono' => '923456789',
            'pago_diario' => 80.00,
            'estado' => 'activo',
            'fecha_ingreso' => Carbon::now()->subMonths(3)
        ]);

        // Crear asistencias de los últimos 7 días
        for ($i = 6; $i >= 0; $i--) {
            $fecha = Carbon::now()->subDays($i);
            
            // Juan asistió todos los días
            Asistencia::create([
                'operario_id' => $operario1->id,
                'fecha' => $fecha,
                'asistio' => true,
                'pago_dia' => 80.00
            ]);

            // María faltó hace 3 días
            Asistencia::create([
                'operario_id' => $operario2->id,
                'fecha' => $fecha,
                'asistio' => $i !== 3,
                'pago_dia' => $i !== 3 ? 80.00 : 0,
                'observaciones' => $i === 3 ? 'Permiso médico' : null
            ]);

            // Carlos asistió todos los días
            Asistencia::create([
                'operario_id' => $operario3->id,
                'fecha' => $fecha,
                'asistio' => true,
                'pago_dia' => 80.00
            ]);
        }
    }
}
