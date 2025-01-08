<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveGoalsFromClassificacoes extends Migration
{
    /**
     * Execute a migration to remove the goals columns.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('classificacoes', function (Blueprint $table) {
            $table->dropColumn(['gols_pro', 'gols_contra']);
        });
    }

    /**
     * Reverse the migration by adding the columns back.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('classificacoes', function (Blueprint $table) {
            $table->integer('gols_pro')->nullable();
            $table->integer('gols_contra')->nullable();
        });
    }
}