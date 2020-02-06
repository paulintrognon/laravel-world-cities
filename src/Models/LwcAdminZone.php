<?php

namespace PaulinTrognon\LaravelWorldCities\Models;

use Illuminate\Database\Eloquent\Model;

class LwcAdminZone extends Model
{
    protected $table = 'lwc_admin_zones';
    public $timestamps = false;

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

    // Helpers

    public function nameWithPostalCode(array $excludeTypes = ['adm1'])
    {
        if (in_array($this->type, $excludeTypes)) {
            return $this->name;
        }
        return "$this->name ($this->postal_code)";
    }

    // Static Helpers

    /**
     * For Select2 plugin
     * https://select2.org/data-sources/ajax
     */
    public static function select2($searchText, array $params = [])
    {
        $countryIso2 = $params['countryIso2'] ?? null;
        $type = $params['type'] ?? null;
        $types = $params['types'] ?? null;

        $query = self
            ::where('postal_code', 'LIKE', "$searchText%")
            ->orWhere('name', 'LIKE', "$searchText%")
            ->orWherehas('alternateNames', function ($query) use ($searchText) {
                $query->where('name', 'LIKE', "$searchText%");
            });

        if ($countryIso2 !== null) {
            $query = $query->where('country_iso2', $countryIso2);
        }
        if ($type) {
            $query = $query->where('type', $type);
        }
        if ($types) {
            $query = $query->whereIn('type', $types);
        }

        $zones = $query
            ->orderBy('postal_code')
            ->take(100)
            ->get();

        $select = [];
        foreach ($zones as $zone) {
            $select[] = [
                "id" => $zone->id,
                "text" => $zone->nameWithPostalCode(),
            ];
        }
        return $select;
    }
}
