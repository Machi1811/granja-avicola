<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PolloEngorde;

class ActualizarCiclosPollos extends Command
{
    /**
     * The name and description of the console command.
     *
     * @var string
     */
    protected $signature = 'pollos:actualizar-ciclos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualiza los días transcurridos de los lotes de pollos y marca como listos los que cumplan 120 días (RF-04)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🐥 Actualizando ciclos de pollos de engorde...');

        // Obtener lotes en crecimiento o listos para venta
        $pollos = PolloEngorde::whereIn('estado', ['crecimiento', 'listo_venta'])->get();

        $actualizados = 0;
        $marcadosListos = 0;

        foreach ($pollos as $pollo) {
            $diasAntes = $pollo->dias_transcurridos;
            $pollo->actualizarDias();
            
            if ($pollo->dias_transcurridos !== $diasAntes) {
                $actualizados++;
            }

            // Verificar si se marcó como listo
            if ($pollo->estado === 'listo_venta' && $pollo->wasChanged('estado')) {
                $marcadosListos++;
                $this->info("✅ Lote {$pollo->codigo_lote} completó 120 días y está listo para venta");
            }
        }

        $this->info("📊 Resultado:");
        $this->info("   • Lotes actualizados: {$actualizados}");
        $this->info("   • Nuevos lotes listos para venta: {$marcadosListos}");
        
        if ($marcadosListos > 0) {
            $this->warn("⚠️  Hay {$marcadosListos} lote(s) que completaron el ciclo de 120 días");
        }

        $this->info('✓ Actualización completada');

        return Command::SUCCESS;
    }
}
