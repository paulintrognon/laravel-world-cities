<?php

namespace PaulinTrognon\LaravelWorldCities\Models;

use Illuminate\Database\Eloquent\Model;

class LwcAdminZoneAlternateName extends Model
{
    protected $table = 'lwc_admin_zone_alternate_names';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    // RELATIONS

    public function adminZone()
    {
        return $this->belongsTo('PaulinTrognon\LaravelWorldCities\Models\LwcAdminZone');
    }
}
