<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddPostalCodeToLwcAdminZonesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lwc_admin_zones', function (Blueprint $table) {
            $table->string('postal_code', 20)->nullable();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lwc_admin_zones', function (Blueprint $table) {
            $table->dropColumn('postal_code');
        });
    }
}
