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
        Schema::create('detallecarrocerias', function (Blueprint $table) {
            $table->id();
            $table->integer('cantidad');
            $table->string('unidad');
            $table->unsignedBigInteger('materialelectrico_id');
            $table->unsignedBigInteger('producto_id');
            $table->foreign('materialelectrico_id')->references('id')->on('materialelectricos')->onDelete('cascade');
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
        Schema::dropIfExists('detallecarrocerias');
    }
};
