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
        Schema::create('enviomaterials', function (Blueprint $table) {
            $table->id();
            $table->string('fecha')->nullable(); 
            $table->unsignedBigInteger('carro_id');
            $table->unsignedBigInteger('material_id');
            $table->foreign('material_id')->references('id')->on('materialcarros')->onDelete('cascade');
            $table->foreign('carro_id')->references('id')->on('carros')->onDelete('cascade');
            $table->tinyInteger('status')->default('0')->comment('0=visible,1=oculto');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enviomaterials');
    }
};
