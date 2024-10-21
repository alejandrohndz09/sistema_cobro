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
        Schema::table('bien', function (Blueprint $table) {
            $table->foreign(['idDepartamento'], 'bien_ibfk_1')->references(['idDepartamento'])->on('departamento')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['idActivo'], 'bien_ibfk_2')->references(['idActivo'])->on('activo')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bien', function (Blueprint $table) {
            $table->dropForeign('bien_ibfk_1');
            $table->dropForeign('bien_ibfk_2');
        });
    }
};
