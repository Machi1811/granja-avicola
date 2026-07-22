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
        // Operario 1: Juan Pérez (usar firstOrCreate para evitar duplicados)
        $operario1 = Operario::firstOrCreate(
            ['dni' => '12345678'],
            [
                'nombre' => 'Juan',
                'apellido' => 'Pérez García',
                'telefono' => '987654321',
                'pago_diario' => 80.00,
                'estado' => 'activo',
                'fecha_ingreso' => Carbon::now()->subMonths(6)
            ]
        );

        // Operario 2: María López
        $operario2 = Operario::firstOrCreate(
            ['dni' => '87654321'],
            [
                'nombre' => 'María',
                'apellido' => 'López Torres',
                'telefono' => '912345678',
                'pago_diario' => 80.00,
                'estado' => 'activo',
                'fecha_ingreso' => Carbon::now()->subMonths(8)
            ]
        );

        // Operario 3: Carlos Rodríguez
        $operario3 = Operario::firstOrCreate(
            ['dni' => '45678912'],
            [
                'nombre' => 'Carlos',
                'apellido' => 'Rodríguez Sánchez',
                'telefono' => '923456789',
                'pago_diario' => 80.00,
                'estado' => 'activo',
                'fecha_ingreso' => Carbon::now()->subMonths(3)
            ]
        );

        // Crear asistencias de los últimos 7 días (solo si no existen)
        for ($i = 6; $i >= 0; $i--) {
            $fecha = Carbon::now()->subDays($i)->format('Y-m-d');
            
            // Juan asistió todos los días
            Asistencia::firstOrCreate(
                [
                    'operario_id' => $operario1->id,
                    'fecha' => $fecha
                ],
                [
                    'asistio' => true,
                    'pago_dia' => 80.00
                ]
            );

            // María faltó hace 3 días
            Asistencia::firstOrCreate(
                [
                    'operario_id' => $operario2->id,
                    'fecha' => $fecha
                ],
                [
                    'asistio' => $i !== 3,
                    'pago_dia' => $i !== 3 ? 80.00 : 0,
                    'observaciones' => $i === 3 ? 'Permiso médico' : null
                ]
            );

            // Carlos asistió todos los días
            Asistencia::firstOrCreate(
                [
                    'operario_id' => $operario3->id,
                    'fecha' => $fecha
                ],
                [
                    'asistio' => true,
                    'pago_dia' => 80.00
                ]
            );
        }
    }
}
