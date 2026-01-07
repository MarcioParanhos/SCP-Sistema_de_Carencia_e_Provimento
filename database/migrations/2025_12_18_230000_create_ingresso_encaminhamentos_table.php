<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (! Schema::hasTable('ingresso_encaminhamentos')) {
            Schema::create('ingresso_encaminhamentos', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->bigInteger('ingresso_candidato_id')->unsigned()->nullable();
                $table->string('uee_code')->nullable();
                $table->string('uee_name')->nullable();
                $table->string('disciplina_code')->nullable();
                $table->string('disciplina_name')->nullable();
                $table->text('observacao')->nullable();
                $table->bigInteger('created_by')->nullable();
                $table->timestamps();
                $table->index('ingresso_candidato_id');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('ingresso_encaminhamentos');
    }
};
