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
        Schema::table('compra', function (Blueprint $table) {
            $table->foreign(['idEmpleado'], 'compra_ibfk_1')->references(['idEmpleado'])->on('empleado')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['idProveedor'], 'compra_ibfk_2')->references(['IdProveedor'])->on('proveedor')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('compra', function (Blueprint $table) {
            $table->dropForeign('compra_ibfk_1');
            $table->dropForeign('compra_ibfk_2');
        });
    }
};
