<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('citas_estados', function (Blueprint $table) {
            $table->id();

            $table->integer('secuencia')->unique();
            $table->string('cita_estado')->unique();
            $table->boolean('bloqueo_cita')->default(false);
            $table->string('class_color')->unique();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('citas_estados');
    }
};
