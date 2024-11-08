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
        Schema::create('citas_objetivo', function (Blueprint $table) {
            $table->id();

            $table->string('ta')->nullable();
            $table->string('fc')->nullable();
            $table->string('fr')->nullable();
            $table->string('temp')->nullable();
            $table->string('talla')->nullable();
            $table->string('peso')->nullable();
            $table->string('imc')->nullable();
            $table->string('sat_o2')->nullable();
            $table->string('hb')->nullable();
            $table->string('hto')->nullable();
            $table->string('vcm')->nullable();
            $table->string('hcm')->nullable();
            $table->string('porcentaje_eritrocitos_hipocromicos')->nullable();
            $table->string('plaq')->nullable();
            $table->string('leuc')->nullable();
            $table->string('cr')->nullable();
            $table->string('ckdepi')->nullable();
            $table->string('bun')->nullable();
            $table->string('g')->nullable();
            $table->string('hba1c_porcentaje')->nullable();
            $table->string('insulina_serica')->nullable();
            $table->string('homa')->nullable();
            $table->string('au')->nullable();
            $table->string('na')->nullable();
            $table->string('k')->nullable();
            $table->string('cl')->nullable();
            $table->string('ca')->nullable();
            $table->string('p')->nullable();
            $table->string('mg')->nullable();
            $table->string('col')->nullable();
            $table->string('alb')->nullable();
            $table->string('tgs')->nullable();
            $table->string('hdl_col')->nullable();
            $table->string('ldl_col')->nullable();
            $table->string('ego')->nullable();
            $table->string('albu_cru')->nullable();
            $table->text('exploracion_fisica')->nullable();
            $table->integer('cita_paciente_id')->unique();
            
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
        Schema::dropIfExists('citas_objetivo');
    }
};
