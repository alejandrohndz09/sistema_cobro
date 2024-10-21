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
        Schema::create('detalle_compra', function (Blueprint $table) {
            $table->string('idDetalleCompra', 6)->primary();
            $table->decimal('precio', 10);
            $table->integer('cantidad');
            $table->string('idCompra', 6)->index('idcompra');
            $table->string('idProducto', 6)->index('idproducto');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_compra');
    }
};
