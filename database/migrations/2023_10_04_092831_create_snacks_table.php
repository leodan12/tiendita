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
        Schema::create('snacks', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->double('precio');
            $table->string('tamanio');
            $table->string('fechavencimiento');
            $table->string('fechavencimiento2');
            $table->integer('stock1')->nullable();
            $table->integer('stock2')->nullable();
            $table->integer('stockmin')->nullable();
            $table->unsignedBigInteger('marcasnack_id');
            $table->unsignedBigInteger('saborsnack_id'); 
            $table->foreign('marcasnack_id')->references('id')->on('marcasnacks');
            $table->foreign('saborsnack_id')->references('id')->on('saborsnacks');
            $table->tinyInteger('status')->default('0')->comment('0=visible,1=oculto');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('snacks');
    }
};
