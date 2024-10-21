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
        Schema::create('venta', function (Blueprint $table) {
            $table->string('idVenta', 6)->primary();
            $table->dateTime('fecha');
            $table->integer('tipo');
            $table->integer('meses')->nullable();
            $table->decimal('SaldoCapital', 10);
            $table->decimal('iva', 10);
            $table->decimal('total', 10);
            $table->string('idEmpleado', 6)->nullable()->index('idempleado');
            $table->string('idCliente_juridico', 6)->nullable()->index('idcliente_juridico');
            $table->string('idCliente_natural', 6)->nullable()->index('idcliente_natural');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('venta');
    }
};
