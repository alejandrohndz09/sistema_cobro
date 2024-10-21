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
        Schema::table('detalle_compra', function (Blueprint $table) {
            $table->foreign(['idCompra'], 'detalle_compra_ibfk_1')->references(['idCompra'])->on('compra')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['idProducto'], 'detalle_compra_ibfk_2')->references(['idProducto'])->on('producto')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detalle_compra', function (Blueprint $table) {
            $table->dropForeign('detalle_compra_ibfk_1');
            $table->dropForeign('detalle_compra_ibfk_2');
        });
    }
};
