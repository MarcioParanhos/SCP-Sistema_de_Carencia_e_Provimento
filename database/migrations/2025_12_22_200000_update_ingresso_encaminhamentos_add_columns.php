<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // If the table exists, ensure the required columns are present
        if (Schema::hasTable('ingresso_encaminhamentos')) {
            Schema::table('ingresso_encaminhamentos', function (Blueprint $table) {
                if (! Schema::hasColumn('ingresso_encaminhamentos', 'uee_code')) {
                    $table->string('uee_code', 50)->nullable()->after('ingresso_candidato_id');
                }
                if (! Schema::hasColumn('ingresso_encaminhamentos', 'uee_name')) {
                    $table->string('uee_name', 255)->nullable()->after('uee_code');
                }
                if (! Schema::hasColumn('ingresso_encaminhamentos', 'disciplina_code')) {
                    $table->string('disciplina_code', 50)->nullable()->after('uee_name');
                }
                if (! Schema::hasColumn('ingresso_encaminhamentos', 'disciplina_name')) {
                    $table->string('disciplina_name', 255)->nullable()->after('disciplina_code');
                }
                if (! Schema::hasColumn('ingresso_encaminhamentos', 'quant_matutino')) {
                    $table->integer('quant_matutino')->nullable()->after('disciplina_name');
                }
                if (! Schema::hasColumn('ingresso_encaminhamentos', 'quant_vespertino')) {
                    $table->integer('quant_vespertino')->nullable()->after('quant_matutino');
                }
                if (! Schema::hasColumn('ingresso_encaminhamentos', 'quant_noturno')) {
                    $table->integer('quant_noturno')->nullable()->after('quant_vespertino');
                }
                if (! Schema::hasColumn('ingresso_encaminhamentos', 'observacao')) {
                    $table->text('observacao')->nullable()->after('quant_noturno');
                }
                if (! Schema::hasColumn('ingresso_encaminhamentos', 'created_by')) {
                    $table->bigInteger('created_by')->nullable()->after('observacao');
                }
                if (! Schema::hasColumn('ingresso_encaminhamentos', 'created_at')) {
                    $table->timestamps();
                }
                if (! Schema::hasColumn('ingresso_encaminhamentos', 'ingresso_candidato_id')) {
                    $table->bigInteger('ingresso_candidato_id')->unsigned()->nullable()->after('id');
                }
            });
        } else {
            // Create the table if it does not exist
            Schema::create('ingresso_encaminhamentos', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->bigInteger('ingresso_candidato_id')->unsigned()->nullable();
                $table->string('uee_code', 50)->nullable();
                $table->string('uee_name', 255)->nullable();
                $table->string('disciplina_code', 50)->nullable();
                $table->string('disciplina_name', 255)->nullable();
                $table->integer('quant_matutino')->nullable();
                $table->integer('quant_vespertino')->nullable();
                $table->integer('quant_noturno')->nullable();
                $table->text('observacao')->nullable();
                $table->bigInteger('created_by')->nullable();
                $table->timestamps();
                $table->index('ingresso_candidato_id');
            });
        }
    }

    public function down()
    {
        if (! Schema::hasTable('ingresso_encaminhamentos')) {
            return;
        }

        Schema::table('ingresso_encaminhamentos', function (Blueprint $table) {
            // Drop columns if they exist
            $cols = [
                'quant_noturno', 'quant_vespertino', 'quant_matutino',
                'disciplina_name', 'disciplina_code',
                'uee_name', 'uee_code',
                'observacao', 'created_by'
            ];
            foreach ($cols as $c) {
                if (Schema::hasColumn('ingresso_encaminhamentos', $c)) {
                    try {
                        $table->dropColumn($c);
                    } catch (\Throwable $e) {
                        // ignore drop errors
                    }
                }
            }
        });
    }
};
