<?php

namespace PaulinTrognon\LaravelWorldCities\Models;

use Illuminate\Database\Eloquent\Model;

class LwcCities extends Model
{
    protected $table = 'lwc_cities';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
}
