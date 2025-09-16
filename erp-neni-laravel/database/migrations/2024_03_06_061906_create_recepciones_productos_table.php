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
        Schema::create('recepciones_productos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idrecepcion')->references('id')->on('recepciones')->constrained('cascade')->onUpdate('cascade')->required(); 
            $table->float('costo')->required();  /*revisar si es clave foranea con id en recepciones y cambiar arriba */
            $table->foreignId('idproducto')->references('id')->on('productos')->constrained('cascade')->onUpdate('cascade')->required();
            $table->integer('cantidad')->required();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recepciones_productos');
    }
};
