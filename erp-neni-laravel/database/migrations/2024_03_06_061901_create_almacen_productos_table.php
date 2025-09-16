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
        Schema::create('almacen_productos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idproducto')->references('id')->on('productos')->constrained('cascade')->onUpdate('cascade')->required();
            $table->foreignId('idalmacen')->references('id')->on('almacenes')->constrained('cascade')->onUpdate('cascade')->required();
            $table->integer('existencia')->unique()->required();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('almacen_productos');
    }
};
