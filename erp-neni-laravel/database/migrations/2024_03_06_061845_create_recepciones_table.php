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
        Schema::create('recepciones', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_recepcion');
            $table->foreignId('idalmacen')->references('id')->on('almacenes')->constrained('cascade')->onUpdate('cascade')->required();
            $table->foreignId('idusuario')->references('id')->on('users')->constrained('cascade')->onUpdate('cascade')->required();
            $table->foreignId('idsocio')->references('id')->on('socios')->constrained('cascade')->onUpdate('cascade')->required();
            $table->set('forma_pago', ['Deposito bancario', 'Tranferencia', 'SPEI', 'Efectivo', 'Pago en plataforma'])->required();
            $table->string('referencia',20)->nullable();
            $table->string('cuenta_pago',30)->nullable();
            $table->string('guia',50)->nullable();
            $table->float('costos_extras');
            $table->float('total')->required();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recepciones');
    }
};
