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
        Schema::create('movimientos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idsocio')->references('id')->on('socios')->constrained('cascade')->onUpdate('cascade')->nullable();
            $table->foreignId('idalmacen')->references('id')->on('almacenes')->constrained('cascade')->onUpdate('cascade');
            $table->foreignId('idusuario')->references('id')->on('users')->constrained('cascade')->onUpdate('cascade');
            $table->foreignId('idtipo')->references('id')->on('tipos_movimientos')->constrained('cascade')->onUpdate('cascade');
            $table->string('referencia',20)->nullable();
            $table->text('notas')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimientos');
    }
};
