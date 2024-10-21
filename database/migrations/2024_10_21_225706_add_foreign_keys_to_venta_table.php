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
        Schema::table('venta', function (Blueprint $table) {
            $table->foreign(['idCliente_juridico'], 'venta_ibfk_1')->references(['idClienteJuridico'])->on('cliente_juridico')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['idCliente_natural'], 'venta_ibfk_2')->references(['idCliente_natural'])->on('cliente_natural')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['idEmpleado'], 'venta_ibfk_3')->references(['idEmpleado'])->on('empleado')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('venta', function (Blueprint $table) {
            $table->dropForeign('venta_ibfk_1');
            $table->dropForeign('venta_ibfk_2');
            $table->dropForeign('venta_ibfk_3');
        });
    }
};
