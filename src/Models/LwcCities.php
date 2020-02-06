<?php

namespace PaulinTrognon\LaravelWorldCities\Models;

use Illuminate\Database\Eloquent\Model;

class LwcCity extends Model
{
    protected $table = 'lwc_cities';
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
        return $this->hasMany('PaulinTrognon\LaravelWorldCities\Models\LwcCityAlternateName');
    }

    // Helpers

    public function nameWithPostalCode(array $excludeTypes = ['adm1'])
    {
        if (in_array($this->type, $excludeTypes)) {
            return $this->name;
        }
        return "$this->name ($this->postal_code)";
    }

    /**
     * For Select2 plugin
     * https://select2.org/data-sources/ajax
     */
    public static function select2($searchText, array $params = [])
    {
        $countryIso2 = $params['countryIso2'] ?? null;

        $citiesQuery = self::where('name', 'LIKE', "$searchText%")
            ->orWherehas('alternateNames', function ($query) use ($searchText) {
                $query->where('name', 'LIKE', "$searchText%");
            });

        if ($countryIso2 !== null) {
            $citiesQuery = $citiesQuery->where('country_iso2', $countryIso2);
        }

        $cities = $citiesQuery
            ->orderBy('name')
            ->take(100)
            ->get();

        $select = [];
        foreach ($cities as $city) {
            $select[] = [
                "id" => $city->id,
                "text" => $city->nameWithPostalCode(),
            ];
        }
        return $select;
    }
}
