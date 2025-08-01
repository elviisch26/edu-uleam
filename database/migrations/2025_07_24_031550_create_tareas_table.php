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
    Schema::create('tareas', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('usuarios');
        $table->string('titulo');
        $table->text('descripcion');
        $table->string('ruta_adjunto')->nullable();
        $table->timestamp('fecha_entrega');
        $table->timestamps();
    });
}

/**
 * Reverse the migrations.
 */
public function down(): void
{
    Schema::dropIfExists('tareas');
}
};
