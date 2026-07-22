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
        Schema::create('ponedoras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('galpon_id')->constrained('galpones')->onDelete('cascade');
            $table->integer('cantidad_inicial');
            $table->integer('cantidad_actual');
            $table->date('fecha_ingreso');
            $table->enum('estado', ['activo', 'vendido', 'descarte'])->default('activo');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ponedoras');
    }
};
