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
        Schema::table('pacientes_notas_historic', function (Blueprint $table) 
        {
            $table->string('obj_ta2')->nullable()->after('obj_ta');
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pacientes_notas_historic', function (Blueprint $table) 
        {
            $table->dropColumn('obj_ta2');
        });
    }
};
