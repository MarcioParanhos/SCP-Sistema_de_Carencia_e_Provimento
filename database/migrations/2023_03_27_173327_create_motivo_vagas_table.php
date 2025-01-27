<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('motivo_vagas', function (Blueprint $table) {
            $table->id();
            $table->string('motivo', 100);
            $table->string('tipo', 100);
            $table->timestamps();
        });

        DB::table('motivo_vagas')->insert([
            ['motivo' => 'APOSENTADORIA', 'tipo' => 'Real'],
            ['motivo' => 'DEMISSÃO REDA', 'tipo' => 'Real'],
            ['motivo' => 'DESISTÊNCIA DE AULAS EXTRAS', 'tipo' => 'Real'],
            ['motivo' => 'EXONERAÇÃO DE EFETIVO', 'tipo' => 'Real'],
            ['motivo' => 'FALECIMENTO', 'tipo' => 'Real'],
            ['motivo' => 'REDUÇÃO DE CH', 'tipo' => 'Real'],
            ['motivo' => 'READAPTAÇÃO DEFINITIVA', 'tipo' => 'Real'],
            ['motivo' => 'REMOÇÃO', 'tipo' => 'Real'],
            ['motivo' => 'AUMENTO DE TURMA', 'tipo' => 'Real'],
            ['motivo' => 'AFASTAMENTO CAUTELAR', 'tipo' => 'Temp'],
            ['motivo' => 'LICENÇA GESTANTE', 'tipo' => 'Temp'],
            ['motivo' => 'LICENÇA MEDICA', 'tipo' => 'Temp'],
            ['motivo' => 'LICENÇA PARA CURSO', 'tipo' => 'Temp'],
            ['motivo' => 'LICENÇA POR APRAZAMENTO', 'tipo' => 'Temp'],
            ['motivo' => 'LICENÇA PREMIO', 'tipo' => 'Temp'],
            ['motivo' => 'MANDATO ELETIVO', 'tipo' => 'Temp'],
            ['motivo' => 'READAPTAÇÃO TEMPORÁRIA', 'tipo' => 'Temp'],
            ['motivo' => 'VAGA DE SERVIDOR MUNICIPAL', 'tipo' => 'Temp'],
            ['motivo' => 'VAGA DE SERVIDOR MILITAR', 'tipo' => 'Real'],
            ['motivo' => 'VAGA DE SERVIDOR MILITAR', 'tipo' => 'Real'],
            ['motivo' => 'AFASTAMENTO POR INTERESSE PARTICULAR', 'tipo' => 'Real'],
            ['motivo' => 'DESISTÊNCIA DE AULAS', 'tipo' => 'Real'],
            ['motivo' => 'AULAS RESIDUAIS', 'tipo' => 'Real'],
            ['motivo' => 'NOMEAÇÃO DE CARGO', 'tipo' => 'Real'],
            ['motivo' => 'DEIXOU DE COMPLEMENTAR', 'tipo' => 'Real'],
            ['motivo' => 'MOVIMENTAÇÃO REDA', 'tipo' => 'Real'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('motivo_vagas');
    }
};
