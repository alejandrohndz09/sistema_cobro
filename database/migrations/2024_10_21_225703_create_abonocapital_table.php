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
        Schema::create('abonocapital', function (Blueprint $table) {
            $table->string('idAbono', 6)->primary();
            $table->dateTime('fecha');
            $table->decimal('monto', 10);
            $table->string('idVenta', 6)->nullable()->index('idventa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('abonocapital');
    }
};
