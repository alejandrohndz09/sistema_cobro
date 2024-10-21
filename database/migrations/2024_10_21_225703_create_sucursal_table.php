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
        Schema::create('sucursal', function (Blueprint $table) {
            $table->string('idSucursal', 6)->primary();
            $table->string('telefono', 9);
            $table->string('direccion', 100);
            $table->string('ubicacion', 100);
            $table->integer('estado');
            $table->string('idEmpresa', 6)->nullable()->index('idempresa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sucursal');
    }
};
