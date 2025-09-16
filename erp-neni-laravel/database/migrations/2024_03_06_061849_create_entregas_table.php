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
        Schema::create('entregas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idusuario')->references('id')->on('users')->constrained('cascade')->onUpdate('cascade');
            $table->foreignId('idpunto_entrega')->references('id')->on('puntos_entrega')->constrained('cascade')->onUpdate('cascade');
            $table->foreignId('idsocio')->references('id')->on('socios')->constrained('cascade')->onUpdate('cascade');
            $table->date('fecha')->required();
            $table->time('hora')->required();
            $table->string('referencia',20)->nullable();
            $table->text('notas')->nullable();
            $table->set('status', ['Por contactar','Contactado','Confirmado','Entregado','Cancelado - Sin respuesta','Cancelado - Con respuesta','Cancelado - No recibio'])->default('Indefinido')->nullable();
            $table->float('cobrar_cliente')->nullable();
            $table->float('costo_entrega')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entregas');
    }
};
