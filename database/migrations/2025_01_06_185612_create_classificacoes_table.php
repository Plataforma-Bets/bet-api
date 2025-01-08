<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassificacoesTable extends Migration
{
    public function up()
    {
        Schema::create('classificacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campeonato_id')->constrained()->onDelete('cascade');
            $table->foreignId('time_id')->constrained()->onDelete('cascade');
            $table->integer('posicao');
            $table->integer('jogos');
            $table->integer('vitorias');
            $table->integer('empates');
            $table->integer('derrotas');
            $table->integer('gols_pro');
            $table->integer('gols_contra');
            $table->integer('pontos');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('classificacoes');
    }
}