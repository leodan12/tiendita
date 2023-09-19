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
        Schema::create('condicions', function (Blueprint $table) {
            $table->id(); 
            $table->unsignedBigInteger('cotizacion_id');
            $table->string('condicion');
            $table->foreign('cotizacion_id')->references('id')->on('cotizacions')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('condicions');
    }
};
