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
        Schema::create('activo', function (Blueprint $table) {
            $table->string('idActivo', 6)->primary();
            $table->string('nombre', 50);
            $table->integer('estado');
            $table->text('imagen');
            $table->text('descripcion');
            $table->string('idCategoria', 6)->nullable()->index('idcategoria');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activo');
    }
};
