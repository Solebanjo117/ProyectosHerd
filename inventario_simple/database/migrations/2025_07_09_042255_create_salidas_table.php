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
        Schema::create('salidas', function (Blueprint $table) {
            $table->id();
            $table->string('tipo'); // Tipo de salida, puede ser venta o ajuste
            $table->foreignId('cliente_id')->nullable()->constrained('clientes')->onDelete('set null'); // Cliente relacionado, puede ser nulo
            $table->string('referencia')->nullable(); // Descripción de la salida
            $table->string('estado')->default('pendiente'); // Estado de la salida, por defecto es pendiente
            $table->string('observaciones')->nullable(); // Observaciones adicionales
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salidas');
    }
};
