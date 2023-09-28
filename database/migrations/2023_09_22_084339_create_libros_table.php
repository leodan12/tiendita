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
        Schema::create('libros', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->string('autor');
            $table->integer('anio');
            $table->string('original');
            $table->double('precio');
            $table->integer('stock1')->nullable();
            $table->integer('stock2')->nullable();
            $table->integer('stockmin')->nullable();
            $table->unsignedBigInteger('formato_id'); 
            $table->unsignedBigInteger('tipopapel_id'); 
            $table->unsignedBigInteger('tipopasta_id'); 
            $table->unsignedBigInteger('edicion_id'); 
            $table->unsignedBigInteger('especializacion_id'); 
            $table->foreign('formato_id')->references('id')->on('formatos');
            $table->foreign('tipopapel_id')->references('id')->on('tipopapels');
            $table->foreign('tipopasta_id')->references('id')->on('tipopastas');
            $table->foreign('edicion_id')->references('id')->on('edicions');
            $table->foreign('especializacion_id')->references('id')->on('especializacions');
            $table->tinyInteger('status')->default('0')->comment('0=visible,1=oculto');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('libros');
    }
};
