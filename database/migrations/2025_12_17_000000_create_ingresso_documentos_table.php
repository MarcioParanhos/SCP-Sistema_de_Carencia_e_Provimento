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
        Schema::create('ingresso_documentos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ingresso_candidato_id')->index();
            $table->string('documento_key');
            $table->string('documento_label')->nullable();
            $table->boolean('validated')->default(false);
            $table->unsignedBigInteger('validated_by')->nullable();
            $table->timestamp('validated_at')->nullable();
            $table->timestamps();

            $table->unique(['ingresso_candidato_id', 'documento_key'], 'ingresso_doc_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingresso_documentos');
    }
};
