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
        Schema::create('carencias', function (Blueprint $table) {
            $table->id();
            $table->integer('nte');
            $table->string('municipio', 100);
            $table->string('unidade_escolar', 100);
            $table->integer('cod_ue');
            $table->string('cadastro', 100)->nullable();
            $table->string('servidor', 100)->nullable();
            $table->string('vinculo', 100)->nullable();
            $table->string('regime', 100)->nullable();
            $table->string('disciplina', 100);
            $table->string('motivo_vaga', 100);
            $table->date('inicio_vaga');
            $table->integer('matutino');
            $table->integer('vespertino');
            $table->integer('noturno');
            $table->integer('total');
            $table->string('tipo_vaga', 100);
            $table->string('eixo', 100)->nullable();
            $table->string('curso', 100)->nullable();
            $table->string('tipo_carencia', 100);
            $table->string('usuario', 100);
            $table->date('fim_vaga')->nullable();
            $table->string('hml', 100)->nullable();
            $table->string('num_rim', 100)->nullable();
            $table->string('area', 100);
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
        Schema::dropIfExists('carencias');
    }
};
