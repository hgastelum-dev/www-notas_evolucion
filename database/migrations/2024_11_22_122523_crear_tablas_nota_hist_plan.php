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
        Schema::create('pacientes_notas_hist_plan', function (Blueprint $table) {
            $table->id();

            $table->text('plan');
            $table->unsignedBigInteger('padre_id')->nullable();
            $table->unsignedBigInteger('tipo_plan_id');
            $table->unsignedBigInteger('paciente_id');
            $table->unsignedBigInteger('nota_historica_id');
            
            $table->timestamps();

            $table->foreign('padre_id')->references('id')->on('pacientes_notas_hist_plan');
            $table->foreign('tipo_plan_id')->references('id')->on('tipos_planeacion');
            $table->foreign('paciente_id')->references('id')->on('pacientes');
            $table->foreign('nota_historica_id')->references('id')->on('pacientes_notas_historic');
        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pacientes_notas_hist_plan', function (Blueprint $table) {

            $table->dropForeign('pacientes_notas_hist_plan_padre_id_foreign');
            $table->dropForeign('pacientes_notas_hist_plan_tipo_plan_id_foreign');
            $table->dropForeign('pacientes_notas_hist_plan_paciente_id_foreign');
            $table->dropForeign('pacientes_notas_hist_plan_nota_historica_id_foreign');
        });

        Schema::dropIfExists('pacientes_notas_hist_plan');
    }
};
