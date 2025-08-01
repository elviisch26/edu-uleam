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
    Schema::table('tareas', function (Blueprint $table) {
        // Añadimos la columna para el nombre original, puede ser nula.
        $table->string('nombre_archivo_guia')->nullable()->after('ruta_archivo_guia');
    });
}

public function down(): void
{
    Schema::table('tareas', function (Blueprint $table) {
        $table->dropColumn('nombre_archivo_guia');
    });
}
};
