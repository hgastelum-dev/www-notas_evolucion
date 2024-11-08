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
        Schema::create('pacientes_antecedentes', function (Blueprint $table) {
            
            $table->id();

            $table->integer('paciente_id');
            $table->text('he_diabetes')->nullable();
            $table->text('he_has')->nullable();
            $table->text('he_car_izq')->nullable();
            $table->text('he_cancer')->nullable();
            $table->text('he_neumopatia')->nullable();
            $table->text('he_enf_renal')->nullable();
            $table->text('he_enf_hepatica')->nullable();
            $table->text('he_otros')->nullable();
            $table->string('pnp_tabaco')->nullable();
            $table->string('pnp_tabaco_cantidad')->nullable();
            $table->string('pnp_tabaco_inicio')->nullable();
            $table->string('pnp_tabaco_fin')->nullable();
            $table->string('pnp_oh')->nullable();
            $table->string('pnp_oh_cantidad')->nullable();
            $table->string('pnp_oh_inicio')->nullable();
            $table->string('pnp_oh_fin')->nullable();
            $table->string('pnp_toxicos')->nullable();
            $table->string('pnp_toxicos_inicio')->nullable();
            $table->string('pnp_toxicos_fin')->nullable();
            $table->string('pnp_toxicos_tipo')->nullable();
            $table->string('pnp_otro')->nullable();
            $table->string('pp_qx')->nullable();
            $table->string('pp_qx_tipo')->nullable();
            $table->string('pp_fx')->nullable();
            $table->string('pp_fx_tipo')->nullable();
            $table->string('pp_alergia')->nullable();
            $table->string('pp_transfusiones')->nullable();
            $table->string('pp_transfusiones_numero')->nullable();
            $table->string('pp_transfusiones_inicial')->nullable();
            $table->string('pp_transfusiones_ultima')->nullable();
            $table->string('pp_patias')->nullable();
            $table->string('pp_anos')->nullable();
            
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
        Schema::dropIfExists('pacientes_antecedentes');
    }
};
