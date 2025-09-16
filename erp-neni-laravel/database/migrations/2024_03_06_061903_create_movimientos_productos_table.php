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
        Schema::create('movimientos_productos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idmovimiento')->references('id')->on('recepciones')->constrained('cascade')->onUpdate('cascade');
            $table->foreignId('idproducto')->references('id')->on('productos')->constrained('cascade')->onUpdate('cascade')->required();
            $table->integer('cantidad')->required();
            $table->string('tabla_ref',50)->nullable();
            $table->integer('idtabla_ref')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimientos_productos');
    }
};
