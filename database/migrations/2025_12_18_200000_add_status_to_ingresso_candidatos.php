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
        if (! Schema::hasTable('ingresso_candidatos')) {
            return;
        }

        if (! Schema::hasColumn('ingresso_candidatos', 'status')) {
            Schema::table('ingresso_candidatos', function (Blueprint $table) {
                $table->string('status')->nullable()->default('Documentos Pendentes');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('ingresso_candidatos') && Schema::hasColumn('ingresso_candidatos', 'status')) {
            Schema::table('ingresso_candidatos', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
};
