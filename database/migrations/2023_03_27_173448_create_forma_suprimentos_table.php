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
        Schema::create('forma_suprimentos', function (Blueprint $table) {
            $table->id();
            $table->string('forma', 100);
            $table->timestamps();
        });

        DB::table('forma_suprimentos')->insert([
            ['forma' => 'AJUSTE INTERNO'],
            ['forma' => 'ALTERAÇÃO DE CH'],
            ['forma' => 'CONCURSO EFETIVO'],
            ['forma' => 'EXCEDENTE EFETIVO'],
            ['forma' => 'EXECEDENTE REDA'],
            ['forma' => 'EXECEDENTE REDA'],
            ['forma' => 'REDA EMERGENCIAL'],
            ['forma' => 'REDA CONCURSO'],
            ['forma' => 'EFETIVO'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('forma_suprimentos');
    }
};
