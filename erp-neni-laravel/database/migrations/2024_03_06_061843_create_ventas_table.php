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
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idsocio')->references('id')->on('socios')->constrained('cascade')->onUpdate('cascade')->required();
            $table->foreignId('idalmacen')->references('id')->on('almacenes')->constrained('cascade')->onUpdate('cascade')->required();
            $table->foreignId('identrega')->references('id')->on('entregas')->constrained('cascade')->onUpdate('cascade');
            $table->foreignId('idusuario')->references('id')->on('users')->constrained('cascade')->onUpdate('cascade')->required();
            $table->set('forma_pago', ['Deposito bancario', 'Tranferencia', 'SPEI', 'Efectivo', 'Pago en plataforma'])->required();
            $table->string('folio',5)->unique()->required();
            $table->string('referencia',20)->nullable();
            $table->string('cuenta_pago',30)->nullable();
            $table->string('total')->required();
            $table->string('notas')->required();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
