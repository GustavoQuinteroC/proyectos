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
        Schema::create('almacenes', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique()->required();
            $table->string('direccion', 100)->unique()->required();
            $table->foreignId('idusuario_encargado')->references('id')->on('users')->constrained('cascade')->onUpdate('cascade')->required();
            $table->integer('capacidad_m3')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('almacenes');
    }
};
