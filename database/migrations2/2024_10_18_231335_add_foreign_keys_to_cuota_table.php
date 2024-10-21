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
        Schema::table('cuota', function (Blueprint $table) {
            $table->foreign(['idVenta'], 'cuota_ibfk_1')->references(['idVenta'])->on('venta')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cuota', function (Blueprint $table) {
            $table->dropForeign('cuota_ibfk_1');
        });
    }
};
