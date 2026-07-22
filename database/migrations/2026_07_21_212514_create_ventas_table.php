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
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->enum('tipo_producto', ['ponedora_viva', 'huevos', 'pollo_engorde', 'carne_gallina']);
            $table->integer('cantidad'); // Unidades o kg
            $table->string('unidad_medida'); // 'unidad', 'docena', 'kg'
            $table->decimal('precio_unitario', 10, 2); // Calculado según tarificador
            $table->decimal('total', 10, 2); // Calculado automáticamente
            $table->foreignId('referencia_id')->nullable(); // ID del galpón, lote o producción
            $table->string('cliente')->nullable();
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
        Schema::dropIfExists('ventas');
    }
};
