<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
    Schema::create('entregas', function (Blueprint $table) {
        $table->id();
        $table->foreignId('tarea_id')->constrained('tareas')->onDelete('cascade');
       $table->foreignId('user_id')->constrained('usuarios')->onDelete('cascade');// El estudiante que entrega
        $table->string('ruta_archivo');
        $table->decimal('calificacion', 5, 2)->nullable();
        $table->text('retroalimentacion')->nullable();
        $table->timestamp('fecha_calificacion')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entregas');
    }
};
