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
        Schema::table('citas_pacientes', function (Blueprint $table) 
        {
            $table->string('gabinete_path_pdf')->nullable()->after('gabinete');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('citas_pacientes', function (Blueprint $table) 
        {
            $table->dropColumn('gabinete_path_pdf');
        });
    }
};
