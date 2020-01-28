<?php

namespace PaulinTrognon\LaravelWorldCities\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use PaulinTrognon\LaravelWorldCities\Models\LwcCities;

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
        $this->info("About to seed $fileName");

        $handle = fopen($fileName, 'r');
        $filesize = filesize($fileName);

        $progressBar = new ProgressBar($this->output, 100);

        $cities = [];

        $i = 0;
        while (($line = fgets($handle)) !== false)
        {
            // ignore empty lines and comments
            if (! $line || $line === '' || strpos($line, '#') === 0) {
                continue;
            }

            // Convert TAB sepereted line to array
            $line = explode("\t", $line);

            if ($line[7] === 'PPL') {
                $cities[] = [
                    'name' => trim($line[1]),
                    'country_iso2' => $line[8],
                    'admin1' => $line[10] ?? '',
                    'admin2' => $line[11] ?? '',
                    'admin3' => $line[12] ?? '',
                    'admin4' => $line[13] ?? '',
                    'latitude' => $line[4],
                    'longitude' => $line[5],
                ];
                $i++;
            }

            if ($i > 10000) {
                LwcCities::insert($cities);
                $cities = [];
                $i = 0;
            }

            $progress = ftell($handle) / $filesize * 100;
            $progressBar->setProgress($progress);
        }

        LwcCities::insert($cities);

        $progressBar->finish();
    }

    private function getFiles()
    {
        $countries = $this->option('countries');
        $this->info($countries);

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