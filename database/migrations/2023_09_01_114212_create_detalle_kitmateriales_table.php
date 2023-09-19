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
        Schema::create('detalle_kitmateriales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('detallematerialcarro_id');//id del kit
            $table->unsignedBigInteger('product_id');//id del producto
            $table->integer('cantidad');
            $table->foreign('detallematerialcarro_id')->references('id')->on('materialcarros')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products'); //->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_kitmateriales');
    }
};
