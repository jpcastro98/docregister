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
        Schema::create('doc_documento', function (Blueprint $table) {
            $table->id('doc_id');
            $table->string('doc_nombre',60);
            $table->string('doc_codigo')->unique();
            $table->string('doc_path',4000);
            $table->string('doc_contenido',4000);
            $table->unsignedBigInteger('doc_id_tipo');
            $table->unsignedBigInteger('doc_id_proceso');
            $table->string('slug',400);

            /*Se crean las llaves foraneas*/
            $table->foreign('doc_id_proceso')->references('pro_id')->on('pro_proceso');
            $table->foreign('doc_id_tipo')->references('tip_id')->on('tip_tipo_doc');
            $table->timestamps();



        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doc_documento');
        Schema::table('doc_documento', function (Blueprint $table) {
            $table->dropForeign(['doc_id_proceso']);
            $table->dropForeign(['doc_id_tipo']);
        });
    }
};
