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
        Schema::create('asistencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('operario_id')->constrained('operarios')->onDelete('cascade');
            $table->date('fecha');
            $table->boolean('asistio')->default(true); // true = asistió, false = faltó
            $table->decimal('pago_dia', 8, 2)->default(80.00); // S/. 80.00 por día trabajado
            $table->text('observaciones')->nullable();
            $table->softDeletes();
            $table->timestamps();
            
            // Evitar duplicados: un operario solo puede tener un registro por día
            $table->unique(['operario_id', 'fecha']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asistencias');
    }
};
