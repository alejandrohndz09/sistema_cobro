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
        Schema::create('detalle_venta', function (Blueprint $table) {
            $table->string('idDetalleVenta', 6)->primary();
            $table->integer('cantidad');
            $table->decimal('subtotal', 10);
            $table->string('idProducto', 6)->nullable()->index('idproducto');
            $table->string('idventa', 6)->nullable()->index('idventa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_venta');
    }
};
