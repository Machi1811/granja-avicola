<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Galpon;

class VerificarDescarteGalpones extends Command
{
    /**
     * The name and description of the console command.
     *
     * @var string
     */
    protected $signature = 'galpones:verificar-descarte';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica y marca galpones que superan los 36 meses de vida para descarte (RF-05)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🏠 Verificando galpones para descarte (36 meses)...');

        // Obtener galpones activos
        $galpones = Galpon::where('estado', 'activo')->get();

        $verificados = 0;
        $marcadosDescarte = 0;

        foreach ($galpones as $galpon) {
            $verificados++;
            
            if ($galpon->verificarEstado()) {
                $marcadosDescarte++;
                $this->warn("⚠️  Galpón '{$galpon->nombre}' marcado para DESCARTE ({$galpon->meses_transcurridos} meses)");
                $this->info("   • Ponedoras del galpón bloqueadas para producción");
                $this->info("   • Habilitada venta como 'Carne de Gallina'");
            } elseif ($galpon->meses_transcurridos >= 33) {
                $this->info("⏰ Galpón '{$galpon->nombre}' tiene {$galpon->meses_transcurridos} meses (próximo a 36)");
            }
        }

        $this->info("\n📊 Resultado:");
        $this->info("   • Galpones verificados: {$verificados}");
        $this->info("   • Galpones marcados para descarte: {$marcadosDescarte}");
        
        if ($marcadosDescarte > 0) {
            $this->error("🚨 ALERTA: {$marcadosDescarte} galpón(es) superaron los 36 meses de vida");
        }

        $this->info('✓ Verificación completada');

        return Command::SUCCESS;
    }
}
