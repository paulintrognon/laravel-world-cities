<?php

namespace PaulinTrognon\LaravelWorldCities\Commands;

use Illuminate\Console\Command;

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
    protected $description = 'Inserts all cities in the database.';

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
    }

    private function getFiles()
    {
        $countries = $this->option('countries');
        $this->info($countries);

        if ($countries == 'all') {
            return ['allCountries.zip'];
        }

        $countries = explode(',', $countries);        
    
        $files = [];
        foreach ($countries as $country) {
            $files[] = "$country.zip";
        }

        return $files;
    }
}