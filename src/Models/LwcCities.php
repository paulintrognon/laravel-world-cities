<?php

namespace PaulinTrognon\LaravelWorldCities\Models;

use Illuminate\Database\Eloquent\Model;

class LwcCity extends Model
{
    protected $table = 'lwc_cities';

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

    /**
     * For Select2 plugin
     * https://select2.org/data-sources/ajax
     */
    public static function select2($searchText, array $params = [])
    {
        $countryIso2 = $params['countryIso2'] ?? null;

        $citiesQuery = self::wherehas('alternateNames', function ($query) use ($searchText) {
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
                "text" => "$city->name ($city->adm2)",
            ];
        }
        return $select;
    }
}
