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
            $table->id()->comment('Chave Primaria da tabela');
            $table->string('name')->comment('Nome completo do usuario');
            $table->string('email')->unique()->comment('Endereço de e-mail unico');
            $table->string('cpf')->unique()->comment('CPF unico do usuario');
            $table->date('birth_date')->comment('Data de nascimento do usuario');
            $table->string('phone')->nullable()->comment('Numero de telefone do usuario');
            $table->timestamps();
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
