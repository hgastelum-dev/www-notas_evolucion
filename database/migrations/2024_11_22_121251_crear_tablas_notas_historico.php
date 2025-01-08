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
        Schema::create('pacientes_notas_historic', function (Blueprint $table) {
            
            $table->id();

            $table->date('fecha');
            $table->text("subjetivo")->nullable();
            $table->string('obj_ta')->nullable();
            $table->string('obj_fc')->nullable();
            $table->string('obj_fr')->nullable();
            $table->string('obj_temp')->nullable();
            $table->string('obj_talla')->nullable();
            $table->string('obj_peso')->nullable();
            $table->string('obj_imc')->nullable();
            $table->string('obj_sat_o2')->nullable();
            $table->string('obj_hb')->nullable();
            $table->string('obj_hto')->nullable();
            $table->string('obj_vcm')->nullable();
            $table->string('obj_hcm')->nullable();
            $table->string('obj_porcentaje_eritrocitos_hipocromicos')->nullable();
            $table->string('obj_plaq')->nullable();
            $table->string('obj_leuc')->nullable();
            $table->string('obj_cr')->nullable();
            $table->string('obj_ckdepi')->nullable();
            $table->string('obj_bun')->nullable();
            $table->string('obj_g')->nullable();
            $table->string('obj_hba1c_porcentaje')->nullable();
            $table->string('obj_insulina_serica')->nullable();
            $table->string('obj_homa')->nullable();
            $table->string('obj_au')->nullable();
            $table->string('obj_na')->nullable();
            $table->string('obj_k')->nullable();
            $table->string('obj_cl')->nullable();
            $table->string('obj_ca')->nullable();
            $table->string('obj_p')->nullable();
            $table->string('obj_mg')->nullable();
            $table->string('obj_col')->nullable();
            $table->string('obj_alb')->nullable();
            $table->string('obj_tgs')->nullable();
            $table->string('obj_hdl_col')->nullable();
            $table->string('obj_ldl_col')->nullable();
            $table->string('obj_ego')->nullable();
            $table->string('obj_albu_cru')->nullable();
            $table->text('obj_exploracion_fisica')->nullable();
            $table->text("analisis")->nullable();
            $table->unsignedBigInteger('paciente_id');
            
            $table->timestamps();

            $table->foreign('paciente_id')->references('id')->on('pacientes');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pacientes_notas_historic', function (Blueprint $table) {

            $table->dropForeign('pacientes_notas_historic_paciente_id_foreign');
        });

        Schema::dropIfExists('pacientes_notas_historic');
    }
};
