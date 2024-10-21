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
        Schema::create('empleado', function (Blueprint $table) {
            $table->string('idEmpleado', 6)->primary();
            $table->string('dui', 10);
            $table->string('nombres', 50);
            $table->string('apellidos', 50);
            $table->string('cargo', 50);
            $table->integer('estado');
            $table->string('idDepartamento', 6)->nullable()->index('iddepartamento');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empleado');
    }
};
