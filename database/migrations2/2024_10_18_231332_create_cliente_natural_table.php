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
        Schema::create('cliente_natural', function (Blueprint $table) {
            $table->string('idCliente_natural', 6)->primary();
            $table->integer('dui');
            $table->string('nombres', 50);
            $table->string('apellidos', 50);
            $table->string('telefono', 9);
            $table->string('direccion', 50);
            $table->decimal('ingresos', 10);
            $table->decimal('egresos', 10);
            $table->string('lugarTrabajo', 50);
            $table->integer('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cliente_natural');
    }
};
