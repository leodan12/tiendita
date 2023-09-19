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
        Schema::create('ordencompras', function (Blueprint $table) {
            $table->id();
            $table->string('fecha'); 
            $table->string('numero'); 
            $table->string('persona')->nullable();
            $table->string('observacion')->nullable();
            $table->string('moneda')->nullable();
            $table->string('formapago')->nullable();
            $table->string('diascredito')->nullable();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('cliente_id');
            $table->foreign('company_id')->references('id')->on('companies');//->onDelete('cascade');
            $table->foreign('cliente_id')->references('id')->on('clientes');//->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ordencompras');
    }
};
