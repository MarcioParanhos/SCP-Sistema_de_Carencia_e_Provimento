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
        Schema::table('provimentos', function (Blueprint $table) {
            $table->string('arquivo_comprobatorio')->nullable()->after('obs_cpg');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('provimentos', function (Blueprint $table) {
            $table->dropColumn('arquivo_comprobatorio');
        });
    }
};