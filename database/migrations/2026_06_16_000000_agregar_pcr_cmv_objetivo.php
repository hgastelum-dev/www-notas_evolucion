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
        Schema::table('citas_objetivo', function (Blueprint $table) {
            $table->string('pcr_cmv')->nullable()->after('fk');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('citas_objetivo', function (Blueprint $table) {
            $table->dropColumn('pcr_cmv');
        });
    }
};
