<?php

namespace PaulinTrognon\LaravelWorldCities\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use PaulinTrognon\LaravelWorldCities\Models\LwcCities;
use PaulinTrognon\LaravelWorldCities\Models\LwcCityAlternateName;
use PaulinTrognon\LaravelWorldCities\Models\LwcAdminZone;
use PaulinTrognon\LaravelWorldCities\Models\LwcAdminZoneAlternateName;

class Seed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lwc:seed {--countries=all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Inserts all cities in the database. --countries=FR,IT to specify specific countries.';

    /**
     * Execute the console command.
     * @return void
     */
    public function handle()
    {
        $countryFiles = $this->getFiles();

        foreach ($countryFiles as $fileName) {
            $this->seed($fileName);
        }

        $this->info('End.');
    }

    private function seed(string $fileName)
    {
        $this->info("Seeding $fileName");

        $handle = fopen($fileName, 'r');
        $filesize = filesize($fileName);

        $progressBar = new ProgressBar($this->output, 100);

        $cities = [];
        $cityAlternateNames = [];
        $adminZones = [];
        $adminZoneAlternateNames = [];

        $i = 0;
        while (($line = fgets($handle)) !== false)
        {
            // ignore empty lines and comments
            if (! $line || $line === '' || strpos($line, '#') === 0 || trim($line[0]) === '') {
                continue;
            }

            // Convert TAB sepereted line to array
            $line = explode("\t", $line);

            switch ($line[7]) {
                case 'ADM1':
                case 'ADM2':
                case 'ADM3':
                case 'ADM4':
                    $adminZones[] = [
                        'id' => $line[0],
                        'name' => trim($line[1]),
                        'country_iso2' => $line[8],
                        'type' => strtolower($line[7]),
                        'adm1' => $line[10] ?? '',
                        'adm2' => $line[11] ?? '',
                        'adm3' => $line[12] ?? '',
                        'adm4' => $line[13] ?? '',
                        'latitude' => $line[4],
                        'longitude' => $line[5],
                    ];
                    $i++;
                    foreach (explode(',', $line[3]) as $alternateName) {
                        if (trim($alternateName) === '') {
                            continue;
                        }
                        $adminZoneAlternateNames[] = [
                            'admin_zone_id' => $line[0],
                            'name' => $alternateName,
                        ];
                        $i++;
                    }
                break;
                
                case 'PPL':
                case 'PPLA':
                case 'PPLA2':
                case 'PPLA3':
                case 'PPLA4':
                case 'PPLA5':
                case 'PPLC':
                    $cities[] = [
                        'id' => $line[0],
                        'name' => trim($line[1]),
                        'country_iso2' => $line[8],
                        'adm1' => $line[10] ?? '',
                        'adm2' => $line[11] ?? '',
                        'adm3' => $line[12] ?? '',
                        'adm4' => $line[13] ?? '',
                        'latitude' => $line[4],
                        'longitude' => $line[5],
                    ];
                    $i++;
                    foreach (explode(',', $line[3]) as $alternateName) {
                        if (trim($alternateName) === '') {
                            continue;
                        }
                        $cityAlternateNames[] = [
                            'city_id' => $line[0],
                            'name' => $alternateName,
                        ];
                        $i++;
                    }
                break;
            }

            if ($i > 5000) {
                $this->insertCities($cities);
                $this->insertAdminZones($adminZones);
                $this->insertCityAlternateNames($cityAlternateNames);
                $this->insertAdminZoneAlternateNames($adminZoneAlternateNames);
                $cities = [];
                $adminZones = [];
                $cityAlternateNames = [];
                $adminZoneAlternateNames = [];
                $i = 0;
            }

            $progress = ftell($handle) / $filesize * 100;
            $progressBar->setProgress($progress);
        }


        $this->insertCities($cities);
        $this->insertAdminZones($adminZones);
        $this->insertCityAlternateNames($cityAlternateNames);
        $this->insertAdminZoneAlternateNames($adminZoneAlternateNames);

        $progressBar->finish();

        $this->info("$fileName Done.");
    }

    private function insertCities(array $cities)
    {
        $ids = array_column($cities, 'id');
        if (count($ids) > 0) {
            LwcCities::destroy($ids);
        }
        LwcCities::insert($cities);
    }

    public function insertAdminZones(array $adminZones)
    {
        $ids = array_column($adminZones, 'id');
        if (count($ids) > 0) {
            LwcAdminZone::destroy($ids);
        }
        LwcAdminZone::insert($adminZones);
    }

    private function insertCityAlternateNames(array $cityAlternateNames)
    {
        $ids = array_column($cityAlternateNames, 'city_id');
        if (count($ids) > 0) {
            LwcCityAlternateName::whereIn('city_id', $ids)->delete();
        } 
        LwcCityAlternateName::insert($cityAlternateNames);
    }

    public function insertAdminZoneAlternateNames(array $adminZoneAlternateNames)
    {
        $ids = array_column($adminZoneAlternateNames, 'admin_zone_id');
        if (count($ids) > 0) {
            LwcAdminZoneAlternateName::whereIn('admin_zone_id', $ids)->delete();
        } 
        LwcAdminZoneAlternateName::insert($adminZoneAlternateNames);
    }

    private function getFiles()
    {
        $countries = $this->option('countries');

        if ($countries == 'all') {
            return [storage_path('app/geo/allCountries.txt')];
        }

        $countries = explode(',', $countries);        
    
        $files = [];
        foreach ($countries as $country) {
            $files[] = storage_path("app/geo/$country.txt");
        }

        return $files;
    }
}