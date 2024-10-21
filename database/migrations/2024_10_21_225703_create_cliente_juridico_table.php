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
        Schema::create('cliente_juridico', function (Blueprint $table) {
            $table->string('idClienteJuridico', 6)->primary();
            $table->string('nit', 14);
            $table->string('nombre_empresa', 50);
            $table->string('direccion', 100);
            $table->string('telefono', 20);
            $table->decimal('ventas_netas', 10);
            $table->decimal('activo_corriente', 10);
            $table->decimal('inventario', 10);
            $table->decimal('costos_ventas', 10);
            $table->decimal('pasivos_corriente', 10);
            $table->decimal('cuentas_cobrar', 10);
            $table->decimal('cuentas_pagar', 10);
            $table->integer('estado');
            $table->text('balance_general');
            $table->text('estado_resultado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cliente_juridico');
    }
};
