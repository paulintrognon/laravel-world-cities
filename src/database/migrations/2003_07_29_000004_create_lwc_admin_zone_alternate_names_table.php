<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLwcAdminZoneAlternateNamesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lwc_admin_zone_alternate_names', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('lwc_admin_zone_id');
            $table->string('name', 200);

            $table->foreign('lwc_admin_zone_id')->references('id')->on('lwc_admin_zones')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('lwc_admin_zone_alternate_names');
    }
}
