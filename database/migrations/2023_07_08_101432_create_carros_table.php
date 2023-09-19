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
        Schema::create('carros', function (Blueprint $table) {
            $table->id();
            $table->string('nroordenp');
            $table->string('chasis');
            $table->double('porcentajedescuento')->nullable();
            $table->string('redenviada')->nullable();
            $table->string('materialelectricoenv')->nullable();
            $table->string('materialadicionalenv')->nullable();
            $table->string('bonificacion')->nullable();
            $table->string('mesbonificacion')->nullable();
            //$table->string('datosenvio', 500)->nullable();
            $table->string('ordencompra', 500)->nullable();
            $table->string('fechaE')->nullable();
            $table->string('numeroE')->nullable();
            $table->string('fechaD')->nullable();
            $table->string('numeroD')->nullable();
            $table->string('fechaO')->nullable();
            $table->string('numeroO')->nullable();
            $table->string('empresaO')->nullable();
            $table->integer('electrobus_id')->nullable();
            $table->integer('delmy_id')->nullable();
            $table->integer('otraempresa_id')->nullable();
            $table->unsignedBigInteger('produccioncarro_id');
            $table->foreign('produccioncarro_id')->references('id')->on('produccioncarros')->onDelete('cascade');
            $table->tinyInteger('status')->default('0')->comment('0=visible,1=oculto');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carros');
    }
};
