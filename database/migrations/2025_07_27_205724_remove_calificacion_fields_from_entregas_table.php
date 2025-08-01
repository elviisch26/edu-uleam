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
        Schema::table('entregas', function (Blueprint $table) {
            // Usamos un array para eliminar múltiples columnas
            // Asegúrate de que los nombres coincidan con los de tu base de datos si son diferentes
            $table->dropColumn(['calificacion', 'retroalimentacion', 'fecha_calificacion']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('entregas', function (Blueprint $table) {
            // El método down nos permite revertir los cambios si algo sale mal
            $table->decimal('calificacion', 5, 2)->nullable();
            $table->text('retroalimentacion')->nullable();
            $table->timestamp('fecha_calificacion')->nullable();
        });
    }
};