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
        Schema::create('pacientes', function (Blueprint $table) {
            $table->id();

            $table->string('nombre_s')->unique();
            $table->string('email')->nullable()->unique();

            $table->string("numero_expediente")->unique()->nullable();
            $table->string("nombre")->nullable();
            $table->string("apellido_paterno")->nullable();
            $table->string("apellido_materno")->nullable();
            $table->string('foto_path')->default("empty");
            $table->string("tipo_sangre")->nullable();
            $table->string('cat_procedencia_id')->nullable();
            $table->string('contacto_procedencia')->nullable();
            $table->boolean("protocolo_transplante")->nullable();
            $table->string("fecha_nacimiento")->nullable();
            $table->string("direccion")->nullable();
            $table->string("telefono")->nullable();
            $table->string("lugar_nacimiento")->nullable();
            $table->string("lugar_residencia")->nullable();
            $table->string("genero_id")->nullable();
            $table->string("ocupacion")->nullable();
            $table->string("escolaridad")->nullable();
            $table->string("religion")->nullable();
            $table->string("estado_civil_id")->nullable();
            $table->string("fecha_ingreso")->nullable();
            $table->string("fecha_elaboracion")->nullable();
            $table->integer("user_id")->nullable();
            $table->boolean("activo")->default(1);
            
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
        Schema::dropIfExists('pacientes');
    }
};
