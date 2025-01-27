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
        Schema::create('provimentos', function (Blueprint $table) {
            $table->id();
            $table->integer('nte');
            $table->integer('cod_unidade');
            $table->string('unidade_escolar', 100);
            $table->string('municipio', 100);
            $table->string('cadastro', 100);
            $table->string('servidor', 100); 
            $table->string('vinculo', 100);
            $table->string('regime', 100);
            $table->string('forma_suprimento', 100);
            $table->string('tipo_movimentacao', 100);
            $table->string('provimento_matutino', 100);
            $table->string('provimento_vespertino', 100);
            $table->string('provimento_noturno', 100);
            $table->string('total', 100);
            $table->integer('id_carencia');
            $table->string('tipo_aula', 100);
            $table->date('data_assuncao');
            $table->string('disciplina', 100);
            $table->string('situacao_provimento', 100);
            $table->string('usuario', 100);
            $table->date('data_encaminhamento', 100);
            $table->string('tipo_carencia_provida', 100);
            $table->date('data_fim_by_temp', 100)->nullable();
            $table->longText('obs')->nullable();
            $table->string('situacao', 100);
            $table->longText('obs_cpg')->nullable();
            $table->string('user_cpg_update', 100);
            $table->string('profile_cpg_update', 100);
            $table->string('pch', 100);
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
        Schema::dropIfExists('provimentos');
    }
};
