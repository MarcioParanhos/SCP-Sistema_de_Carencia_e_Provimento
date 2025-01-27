<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servidores', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('tipo_cargo', 100)->nullable();
            $table->string('vinculo', 100)->nullable();
            $table->string('cargo', 100)->nullable();
            $table->integer('regime')->nullable();
            $table->string('cadastro', 100)->nullable();
            $table->string('nome', 100)->nullable();
            $table->integer('cadastro_sap')->nullable();
            $table->string('tipo', 100)->nullable();
            $table->string('cpf', 100)->nullable();
            $table->string('profile', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('servidores');
    }
};
