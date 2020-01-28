<?php

namespace PaulinTrognon\LaravelWorldCities\Models;

use Illuminate\Database\Eloquent\Model;

class LwcAdminZone extends Model
{
    protected $table = 'lwc_admin_zones';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    // RELATIONS

    public function alternateNames()
    {
        return $this->hasMany('PaulinTrognon\LaravelWorldCities\Models\LwcAdminZoneAlternateName');
    }
}
