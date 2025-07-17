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
        Schema::create('entradas_detalles', function (Blueprint $table) {
            $table->id();
    $table->foreignId('entrada_id')->constrained('entradas')->onDelete('cascade');
    $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade');
    $table->integer('cantidad');
    $table->decimal('precio_unitario', 10, 2)->nullable(); // opcional
    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entradas_detalles');
    }
};
