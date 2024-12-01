<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaisAndLogoToCampeonatosTable extends Migration
{
    public function up()
    {
        Schema::table('campeonatos', function (Blueprint $table) {
            $table->string('pais')->nullable();  
            $table->string('logo')->nullable(); 
        });
    }

    public function down()
    {
        Schema::table('campeonatos', function (Blueprint $table) {
            $table->dropColumn(['pais', 'logo']);
        });
    }
}