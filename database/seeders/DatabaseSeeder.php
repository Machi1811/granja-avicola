<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * 
     * Datos iniciales para testing del sistema de granja avícola:
     * - 4 galpones (con diferentes estados de vida)
     * - 2 tipos de alimento (postura y engorde)
     * - 3 operarios con asistencias de los últimos 7 días
     * - 500 ponedoras distribuidas en 3 galpones
     * - 5 lotes de pollos de engorde en diferentes estados del ciclo
     */
    public function run(): void
    {
        $this->call([
            // Orden importante: primero estructuras, luego dependencias
            GalponSeeder::class,
            AlimentoSeeder::class,
            OperarioSeeder::class,
            PonedoraSeeder::class,
            PolloEngordeSeeder::class,
        ]);

        $this->command->info('✓ Base de datos inicializada con datos de prueba');
        $this->command->info('');
        $this->command->info('📊 Resumen:');
        $this->command->info('   • 4 galpones (3 activos, 1 en descarte)');
        $this->command->info('   • 495 ponedoras activas (distribución: 245+150+100)');
        $this->command->info('   • 2 tipos de alimento (postura: 1.5qq ⚠️, engorde: 8qq ✓)');
        $this->command->info('   • 3 operarios activos con historial de asistencia');
        $this->command->info('   • 5 lotes de pollos (3 en crecimiento, 1 listo, 1 vendido)');
        $this->command->info('');
        $this->command->info('⚠️  ALERTAS CONFIGURADAS:');
        $this->command->info('   • Galpón B: 34 meses (próximo a 36)');
        $this->command->info('   • Alimento postura: 1.5 quintales (bajo stock)');
        $this->command->info('   • LOTE-0002: faltan 5 días para completar 120 días');
        $this->command->info('   • LOTE-0003: listo para venta (120 días completos)');
    }
}

