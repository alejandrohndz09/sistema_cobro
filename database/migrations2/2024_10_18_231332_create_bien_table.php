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
        Schema::create('bien', function (Blueprint $table) {
            $table->string('idBien', 6)->primary();
            $table->string('descripcion', 100);
            $table->date('fechaAdquisicion');
            $table->decimal('precio', 10);
            $table->integer('estado');
            $table->string('idDepartamento', 6)->index('iddepartamento');
            $table->string('idActivo', 6)->index('idactivo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bien');
    }
};
