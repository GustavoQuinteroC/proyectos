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
    Schema::create('socios', function (Blueprint $table) {
        $table->id();  // Primary key with auto-increment

        $table->string('name', 100)->unique()->required();
        $table->string('email', 100)->nullable();
        $table->bigInteger('telefono')->unique()->required();
        $table->string('domicilio', 100)->nullable();
        $table->set('sexo', ['Hombre', 'Mujer', 'Indefinido'])->default('Indefinido')->nullable();
        $table->integer('dias_entrega')->nullable();
        $table->string('plataforma', 100)->nullable();
        $table->string('rfc', 25)->unique()->nullable();
        $table->set('tipo', ['cliente', 'proveedor', 'ambos'])->required();
        $table->timestamps();
        $table->index(['name', 'email']);
    });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('socios');
    }
};
