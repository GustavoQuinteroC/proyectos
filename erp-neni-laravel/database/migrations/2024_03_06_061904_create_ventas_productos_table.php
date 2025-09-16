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
        Schema::create('ventas_productos', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->foreignId('idproducto')->references('id')->on('productos')->constrained('cascade')->onUpdate('cascade');
            $table->integer('cantidad')->required();
            $table->foreignId('idventa')->references('id')->on('ventas')->constrained('cascade')->onUpdate('cascade');
            $table->float('preciou')->required();
            $table->float('desc_porcentaje')->required();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas_productos');
    }
};
