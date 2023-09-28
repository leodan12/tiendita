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
        Schema::create('uniformes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('genero');
            $table->double('precio');
            $table->integer('stock1')->nullable();
            $table->integer('stock2')->nullable();
            $table->integer('stockmin')->nullable();
            $table->unsignedBigInteger('talla_id');
            $table->unsignedBigInteger('tipotela_id'); 
            $table->unsignedBigInteger('color_id'); 
            $table->foreign('color_id')->references('id')->on('colors');
            $table->foreign('tipotela_id')->references('id')->on('tipotelas');
            $table->foreign('talla_id')->references('id')->on('tallas');
            $table->tinyInteger('status')->default('0')->comment('0=visible,1=oculto');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uniformes');
    }
};
