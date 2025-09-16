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
        Schema::create('users', function (Blueprint $table) {
            // Columna de la llave primaria autoincremental
            $table->id()->autoIncrement();

            // Columnas de identificación y contacto
            $table->string('name', 100)->unique()->required();
            $table->string('email', 100)->unique()->required();
            $table->timestamp('email_verified_at')->nullable();
            $table->bigInteger('telefono')->unique()->required()->default(0000000000);

            // Columnas de información adicional
            $table->string('ine', 20)->unique()->nullable();
            $table->string('domicilio', 100)->nullable();
            $table->string('rfc', 25)->unique()->nullable();

            // Relaciones con otras tablas
            $table->foreignId('idrol')->references('id')->on('roles')->constrained('roles')->onUpdate('cascade')->required()->default('1');

            // Autenticación y seguridad
            $table->string('password')->required();
            $table->rememberToken();

            // Timestamps
            $table->timestamps();

            // Index para mejorar búsquedas
            $table->index(['name', 'email']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
