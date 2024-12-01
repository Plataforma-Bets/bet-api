<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('times', function (Blueprint $table) {
            $table->string('estadio')->nullable()->after('escudo'); 
            $table->string('tecnico')->nullable()->after('estadio'); 
        });

        Schema::dropIfExists('tecnicos');
        Schema::dropIfExists('estadios');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('times', function (Blueprint $table) {
            $table->dropColumn(['estadio', 'tecnico']);
        });

        Schema::create('tecnicos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->timestamps();
        });

        Schema::create('estadios', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->timestamps();
        });
    }
};