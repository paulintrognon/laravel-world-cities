<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLwcCityAlternateNamesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lwc_city_alternate_names', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('lwc_city_id');
            $table->string('name', 200);

            $table->foreign('lwc_city_id')->references('id')->on('lwc_cities')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('lwc_city_alternate_names');
    }
}
