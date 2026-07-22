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
        Schema::create('alimentos', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo', ['postura', 'engorde']); // Tipo de alimento
            $table->decimal('quintales_stock', 10, 2); // Stock en quintales
            $table->decimal('kg_stock', 10, 2); // Stock en kg (calculado: quintales * 46)
            $table->date('fecha_ultima_compra')->nullable();
            $table->decimal('quintales_ultima_compra', 10, 2)->nullable();
            $table->decimal('precio_quintal', 10, 2)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alimentos');
    }
};
