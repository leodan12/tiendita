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
        Schema::create('detallereds', function (Blueprint $table) {
            $table->id();
            $table->integer('cantidad');
            $table->string('unidad');
            $table->unsignedBigInteger('red_id');
            $table->unsignedBigInteger('producto_id');
            $table->foreign('red_id')->references('id')->on('reds')->onDelete('cascade');
            $table->foreign('producto_id')->references('id')->on('products');
            $table->tinyInteger('status')->default('0')->comment('0=visible,1=oculto');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detallereds');
    }
};
