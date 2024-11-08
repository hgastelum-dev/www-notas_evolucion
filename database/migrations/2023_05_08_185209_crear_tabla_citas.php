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
        Schema::create('citas_pacientes', function (Blueprint $table) {
            $table->id();

            $table->date('fecha');
            $table->time('hora_inicio');
            $table->time('hora_termino');
            $table->integer('paciente_id');
            $table->integer('cita_anterior_id')->default(0);
            $table->integer('cita_estado_id');
            $table->integer('user_id');
            
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
        Schema::dropIfExists('citas_pacientes');
    }
};
