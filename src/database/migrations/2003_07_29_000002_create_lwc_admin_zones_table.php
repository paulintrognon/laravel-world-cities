<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLwcAdminZonesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lwc_admin_zones', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 200);
            $table->enum('type', ['admin1', 'admin2', 'admin3', 'admin4']);
            $table->string('code');
            $table->string('country_iso2', 2);
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('lwc_admin_zones');
    }
}
