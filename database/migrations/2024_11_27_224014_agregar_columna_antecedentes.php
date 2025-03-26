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
        Schema::table('pacientes_antecedentes', function (Blueprint $table) 
        {
            $table->string('pnp_gineco_obstetricos')->nullable()->after('pnp_toxicos_tipo');
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pacientes_antecedentes', function (Blueprint $table) 
        {
            $table->dropColumn('pnp_gineco_obstetricos');
        });
    }
};
