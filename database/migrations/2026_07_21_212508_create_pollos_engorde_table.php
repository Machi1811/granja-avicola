<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pollos_engorde', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_lote')->unique(); // Identificador del lote/camada
            $table->integer('cantidad_inicial');
            $table->integer('cantidad_actual');
            $table->date('fecha_ingreso');
            $table->integer('dias_transcurridos')->default(0); // Calculado automáticamente
            $table->enum('estado', ['crecimiento', 'listo_venta', 'vendido'])->default('crecimiento');
            $table->decimal('consumo_total_kg', 10, 2)->default(0); // Acumulado de consumo
            $table->decimal('peso_venta_kg', 10, 2)->nullable(); // Peso total cuando se vende
            $table->date('fecha_venta')->nullable();
            $table->text('observaciones')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pollos_engorde');
    }
};
