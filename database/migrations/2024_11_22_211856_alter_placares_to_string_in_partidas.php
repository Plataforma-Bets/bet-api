<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPlacaresToStringInPartidas extends Migration
{
    public function up()
    {
        Schema::table('partidas', function (Blueprint $table) {
            $table->string('placar_mandante')->nullable()->change();
            $table->string('placar_visitante')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('partidas', function (Blueprint $table) {
            $table->integer('placar_mandante')->nullable()->change();
            $table->integer('placar_visitante')->nullable()->change();
        });
    }
}