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
        Schema::create('detalle_kitventas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('detalleventa_id');//id del kit
            $table->unsignedBigInteger('kitproduct_id');//id del producto
            $table->integer('cantidad');
            $table->foreign('detalleventa_id')->references('id')->on('detalleventas')->onDelete('cascade');//si elimina el kit se elimina el detalle
            $table->foreign('kitproduct_id')->references('id')->on('products'); //->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_kitventas');
    }
};
