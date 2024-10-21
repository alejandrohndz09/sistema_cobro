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
        Schema::create('compra', function (Blueprint $table) {
            $table->string('idCompra', 6)->primary();
            $table->dateTime('fecha');
            $table->integer('stockDisponible');
            $table->string('idEmpleado', 6)->index('idempleado');
            $table->string('idProveedor', 6)->index('idproveedor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compra');
    }
};
