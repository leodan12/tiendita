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
        Schema::create('instrumentos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('garantia');
            $table->double('precio');
            $table->integer('stock1')->nullable();
            $table->integer('stock2')->nullable();
            $table->integer('stockmin')->nullable();
            $table->unsignedBigInteger('marca_id');
            $table->unsignedBigInteger('modelo_id'); 
            $table->foreign('marca_id')->references('id')->on('marcas');
            $table->foreign('modelo_id')->references('id')->on('modelos');
            $table->tinyInteger('status')->default('0')->comment('0=visible,1=oculto');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instrumentos');
    }
};

