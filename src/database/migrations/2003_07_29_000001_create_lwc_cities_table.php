<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLwcCitiesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lwc_cities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 200);
            $table->string('adm1', 20);
            $table->string('adm2', 80);
            $table->string('adm3', 20);
            $table->string('adm4', 20);
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
        Schema::drop('lwc_cities');
    }
}
