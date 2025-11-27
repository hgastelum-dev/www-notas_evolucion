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
        Schema::table('citas_objetivo', function (Blueprint $table) 
        {
            $table->string('tsh')->nullable()->after('albu_cru');
            $table->string('vit_d_serica')->nullable()->after('tsh');
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('citas_objetivo', function (Blueprint $table) 
        {
            $table->dropColumn('tsh');
            $table->dropColumn('vit_d_serica');
        });
    }
};
