<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('provimentos', function (Blueprint $table) {
            $table->unsignedBigInteger('servidor_id')->nullable()->after('usuario')->index();
        });
    }

    public function down()
    {
        Schema::table('provimentos', function (Blueprint $table) {
            $table->dropColumn('servidor_id');
        });
    }
};
