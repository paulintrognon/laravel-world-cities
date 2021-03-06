<?php

namespace PaulinTrognon\LaravelWorldCities\Commands;

use Illuminate\Console\Command;

class Download extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lwc:download {--countries=all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download cities data from geonames.org. --countries=FR,IT to specify specific countries.';

    /**
     * Execute the console command.
     * @return void
     */
    public function handle()
    {
        $filesToDownload = $this->getFilesToDownload();

        \Storage::makeDirectory('geo');

        foreach ($filesToDownload as $fileName) {
            $source = "http://download.geonames.org/export/dump/$fileName";
            $target = storage_path("app/geo/$fileName");
            $targetTxt = storage_path('app/geo/' . preg_replace('/\.zip/', '.txt', $fileName));

            $this->info(" Source file {$source} \n Target file {$targetTxt}");

           if (! (file_exists($target) || file_exists($targetTxt))) {
                $this->info(" Downloading file {$fileName}");
                if (! copy($source, $target)) {
                    throw new \Exception("Failed to download the file $source");
                }
            }

            if (file_exists($target) && ! file_exists($targetTxt)) {
                if (preg_match('/\.zip/', $fileName)) {
                    $zip = new \ZipArchive;
                    $zip->open($target);
                    $zip->extractTo(dirname($target));
                    $zip->close();
                }
            }
        }

        $this->info('End.');
    }

    private function getFilesToDownload()
    {
        $countries = $this->option('countries');

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