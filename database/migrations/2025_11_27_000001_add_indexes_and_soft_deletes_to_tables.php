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
        // Índices para la tabla tareas
        Schema::table('tareas', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('fecha_entrega');
            $table->index(['user_id', 'fecha_entrega']);
            $table->softDeletes();
        });

        // Índices para la tabla entregas
        Schema::table('entregas', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('tarea_id');
            $table->unique(['user_id', 'tarea_id']); // Evitar entregas duplicadas
            $table->softDeletes();
        });

        // Índices para la tabla calificaciones
        Schema::table('calificaciones', function (Blueprint $table) {
            $table->unique('entrega_id'); // Una calificación por entrega
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tareas', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['fecha_entrega']);
            $table->dropIndex(['user_id', 'fecha_entrega']);
            $table->dropSoftDeletes();
        });

        Schema::table('entregas', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['tarea_id']);
            $table->dropUnique(['user_id', 'tarea_id']);
            $table->dropSoftDeletes();
        });

        Schema::table('calificaciones', function (Blueprint $table) {
            $table->dropUnique(['entrega_id']);
            $table->dropSoftDeletes();
        });
    }
};
