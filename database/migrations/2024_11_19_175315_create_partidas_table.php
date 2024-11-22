<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('partidas', function (Blueprint $table) {
        $table->id();
        $table->date('data');
        $table->time('horario')->nullable();
        $table->unsignedBigInteger('time_mandante_id');
        $table->unsignedBigInteger('time_visitante_id');
        $table->unsignedBigInteger('campeonato_id');
        $table->integer('placar_mandante')->nullable();
        $table->integer('placar_visitante')->nullable();
        $table->timestamps();

        $table->foreign('time_mandante_id')->references('id')->on('times')->onDelete('cascade');
        $table->foreign('time_visitante_id')->references('id')->on('times')->onDelete('cascade');
        $table->foreign('campeonato_id')->references('id')->on('campeonatos')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partidas');
    }
};
