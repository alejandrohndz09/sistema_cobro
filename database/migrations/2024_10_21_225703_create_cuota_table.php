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
        Schema::create('cuota', function (Blueprint $table) {
            $table->string('idCuota', 6)->primary();
            $table->dateTime('fechaLimite');
            $table->dateTime('fechaPago');
            $table->decimal('monto', 10);
            $table->decimal('mora', 10);
            $table->integer('estado');
            $table->string('idVenta', 6)->nullable()->index('idventa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuota');
    }
};
