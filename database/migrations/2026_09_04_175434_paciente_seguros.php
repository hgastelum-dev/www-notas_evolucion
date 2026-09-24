<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('paciente_seguro_medico', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('paciente_id');
            $table->unsignedBigInteger('seguro_medico_id');

            $table->foreign('paciente_id')->references('id')->on('pacientes')->cascadeOnDelete();
            $table->foreign('seguro_medico_id')->references('id')->on('seguros_medicos')->cascadeOnDelete();

            // evita asociar el mismo seguro dos veces al mismo paciente
            $table->unique(['paciente_id', 'seguro_medico_id']);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('paciente_seguro_medico');
    }
};