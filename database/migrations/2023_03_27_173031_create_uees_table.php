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
        Schema::create('uees', function (Blueprint $table) {
            $table->id();
            $table->integer('cod_unidade');
            $table->integer('nte');
            $table->string('municipio', 100);
            $table->string('situacao', 100);
            $table->string('unidade_escolar', 100);
            $table->string('tipo', 100);
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
        Schema::dropIfExists('uees');
    }
};
