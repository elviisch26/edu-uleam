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
        // Añadimos una columna para el archivo, puede ser nula.
        $table->string('ruta_archivo_guia')->nullable()->after('descripcion');
    });
}

public function down(): void
{
    Schema::table('tareas', function (Blueprint $table) {
        $table->dropColumn('ruta_archivo_guia');
    });
}
};
