<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('ingresso_candidatos')) {
            return;
        }

        Schema::table('ingresso_candidatos', function (Blueprint $table) {
            if (! Schema::hasColumn('ingresso_candidatos', 'documentos_validados')) {
                $table->boolean('documentos_validados')->default(false);
            }
            if (! Schema::hasColumn('ingresso_candidatos', 'status_validated_by')) {
                $table->unsignedBigInteger('status_validated_by')->nullable();
            }
            if (! Schema::hasColumn('ingresso_candidatos', 'status_validated_at')) {
                $table->timestamp('status_validated_at')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('ingresso_candidatos')) {
            return;
        }

        Schema::table('ingresso_candidatos', function (Blueprint $table) {
            if (Schema::hasColumn('ingresso_candidatos', 'status_validated_at')) {
                $table->dropColumn('status_validated_at');
            }
            if (Schema::hasColumn('ingresso_candidatos', 'status_validated_by')) {
                $table->dropColumn('status_validated_by');
            }
            if (Schema::hasColumn('ingresso_candidatos', 'documentos_validados')) {
                $table->dropColumn('documentos_validados');
            }
        });
    }
};
