<?php

namespace PaulinTrognon\LaravelWorldCities\Models;

use Illuminate\Database\Eloquent\Model;

class LwcCityAlternateName extends Model
{
    protected $table = 'lwc_city_alternate_names';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    // RELATIONS

    public function city()
    {
        return $this->belongsTo('PaulinTrognon\LaravelWorldCities\Models\LwcCity');
    }
}
