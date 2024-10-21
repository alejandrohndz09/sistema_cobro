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
        Schema::table('detalle_venta', function (Blueprint $table) {
            $table->foreign(['idProducto'], 'detalle_venta_ibfk_1')->references(['idProducto'])->on('producto')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['idventa'], 'detalle_venta_ibfk_2')->references(['idVenta'])->on('venta')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detalle_venta', function (Blueprint $table) {
            $table->dropForeign('detalle_venta_ibfk_1');
            $table->dropForeign('detalle_venta_ibfk_2');
        });
    }
};
