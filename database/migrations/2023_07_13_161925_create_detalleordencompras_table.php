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
        Schema::create('detalleordencompras', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ordencompra_id');
            $table->unsignedBigInteger('product_id'); 
            $table->integer('cantidad');
            $table->string('unidad')->nullable();
            $table->string('preciocompra')->nullable();
            $table->string('observacionproducto')->nullable();
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('ordencompra_id')->references('id')->on('ordencompras')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalleordencompras');
    }
};
