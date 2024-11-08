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
        Schema::create('citas_planeacion', function (Blueprint $table) {
            $table->id();

            $table->text('plan');
            $table->integer('padre_id')->default(0);
            $table->integer('tipo_plan_id');
            $table->boolean('indicador_seguimiento');
            $table->integer('paciente_id');
            $table->integer('cita_paciente_id');
            
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
        Schema::dropIfExists('citas_planeacion');
    }
};
