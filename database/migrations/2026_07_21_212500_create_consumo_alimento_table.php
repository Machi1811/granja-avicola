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
        Schema::create('consumo_alimento', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alimento_id')->constrained('alimentos')->onDelete('cascade');
            $table->date('fecha');
            $table->integer('cantidad_aves'); // Número de aves que consumieron
            $table->decimal('consumo_por_ave_gramos', 8, 2); // 120g para ponedoras
            $table->decimal('consumo_total_kg', 10, 2); // Calculado automáticamente
            $table->decimal('consumo_quintales', 10, 2); // Calculado automáticamente
            $table->enum('tipo_ave', ['ponedora', 'engorde']);
            $table->foreignId('referencia_id')->nullable(); // ID del galpón o lote de pollos
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consumo_alimento');
    }
};
