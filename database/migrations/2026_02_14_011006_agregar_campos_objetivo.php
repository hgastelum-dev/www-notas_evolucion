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
            $table->string('bnp')->nullable()->after('vit_d_serica');
            $table->string('ca_125')->nullable()->after('bnp');
            $table->string('fk')->nullable()->after('ca_125');
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
            $table->dropColumn('bnp');
            $table->dropColumn('ca_125');
            $table->dropColumn('fk');
        });
    }
};
