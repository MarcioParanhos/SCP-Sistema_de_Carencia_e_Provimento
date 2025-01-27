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
        Schema::create('apoio_pedagogico_vagas', function (Blueprint $table) {
            $table->id();
            $table->integer('nte');
            $table->string('municipio', 100);
            $table->string('unidade_escolar', 100);
            $table->integer('cod_unidade');
            $table->string('profissional', 100);
            $table->integer('regime');
            $table->string('turno', 100);
            $table->integer('qtd');
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
        Schema::dropIfExists('apoio_pedagogico_vagas');
    }
};
