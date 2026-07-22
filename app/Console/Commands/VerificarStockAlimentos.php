<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Alimento;

class VerificarStockAlimentos extends Command
{
    /**
     * The name and description of the console command.
     *
     * @var string
     */
    protected $signature = 'alimentos:verificar-stock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica el stock de alimentos y alerta cuando hay menos de 2 quintales (RF-02)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🌾 Verificando stock de alimentos...');

        $alimentos = Alimento::all();
        $alertasBajas = 0;

        foreach ($alimentos as $alimento) {
            $this->info("\n📦 Alimento de {$alimento->tipo}:");
            $this->info("   • Stock actual: {$alimento->quintales_stock} quintales ({$alimento->kg_stock} kg)");

            if ($alimento->tiene_alerta_stock) {
                $alertasBajas++;
                $this->error("   🚨 CRÍTICO: Stock bajo (menos de 2 quintales)");
                $this->warn("   ⚠️  Se requiere compra urgente");
                
                // Calcular cuánto falta para llegar a 5 quintales (stock recomendado)
                $faltante = 5 - $alimento->quintales_stock;
                if ($faltante > 0) {
                    $this->info("   💡 Recomendación: Comprar al menos {$faltante} quintales");
                }
            } else {
                $this->info("   ✓ Stock adecuado");
            }

            // Mostrar última compra si existe
            if ($alimento->fecha_ultima_compra) {
                $diasDesdeCompra = $alimento->fecha_ultima_compra->diffInDays(now());
                $this->info("   • Última compra: hace {$diasDesdeCompra} días ({$alimento->fecha_ultima_compra->format('d/m/Y')})");
            }
        }

        $this->info("\n📊 Resumen:");
        $this->info("   • Tipos de alimento verificados: {$alimentos->count()}");
        $this->info("   • Alertas de stock bajo: {$alertasBajas}");

        if ($alertasBajas > 0) {
            $this->error("\n🚨 ACCIÓN REQUERIDA: {$alertasBajas} tipo(s) de alimento con stock crítico");
            $this->warn("   Acceda al sistema para registrar compras");
        } else {
            $this->info("\n✓ Todos los alimentos tienen stock adecuado");
        }

        return Command::SUCCESS;
    }
}
