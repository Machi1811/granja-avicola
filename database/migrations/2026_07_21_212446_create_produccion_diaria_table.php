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
        Schema::create('produccion_diaria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('galpon_id')->constrained('galpones')->onDelete('cascade');
            $table->date('fecha');
            $table->integer('aves_activas'); // Cantidad de ponedoras activas ese día
            $table->integer('produccion_teorica'); // Huevos esperados
            $table->decimal('porcentaje_merma', 5, 2)->default(10.00); // 10% fijo
            $table->integer('produccion_neta'); // Calculado automáticamente (90% de teórica)
            $table->integer('produccion_real')->nullable(); // Lo que realmente se recolectó
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
        Schema::dropIfExists('produccion_diaria');
    }
};
