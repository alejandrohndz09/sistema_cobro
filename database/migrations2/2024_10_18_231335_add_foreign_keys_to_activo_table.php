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
        Schema::table('activo', function (Blueprint $table) {
            $table->foreign(['idCategoria'], 'activo_ibfk_1')->references(['idCategoria'])->on('categoria')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activo', function (Blueprint $table) {
            $table->dropForeign('activo_ibfk_1');
        });
    }
};
