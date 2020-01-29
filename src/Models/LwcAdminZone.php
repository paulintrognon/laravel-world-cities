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

    // Scopes

    public function scopeAdm1($query)
    {
        $query->where('type', 'adm1');
    }

    public function scopeAdm2($query)
    {
        $query->where('type', 'adm2');
    }

    public function scopeAdm3($query)
    {
        $query->where('type', 'adm3');
    }

    public function scopeAdm4($query)
    {
        $query->where('type', 'adm4');
    }
}
