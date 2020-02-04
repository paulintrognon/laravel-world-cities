<?php

namespace PaulinTrognon\LaravelWorldCities\Commands;

use Illuminate\Console\Command;
use PaulinTrognon\LaravelWorldCities\Models\LwcAdminZone;
use PaulinTrognon\LaravelWorldCities\Models\LwcCity;

class ImportFrenchPostalCodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lwc:postalcodes:french';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download & import french postal codes';

    /**
     * Execute the console command.
     * @return void
     */
    public function handle()
    {
        $fileName = 'postal_codes_fr.csv';
        $filePath = storage_path($fileName);

        // Downloading the file
        $source = "https://public.opendatasoft.com/explore/dataset/correspondance-code-insee-code-postal/download/?format=csv&timezone=Europe/Berlin&use_labels_for_header=true&csv_separator=%3B";
        $this->downloadingFile($source, $filePath);

        // Importing the postal codes
        $handle = fopen($fileName, 'r');
        $filesize = filesize($fileName);

        $progressBar = new ProgressBar($this->output, 100);

        // We skip line 1
        fgets($handle);
        while (($line = fgets($handle)) !== false)
        {
            $line = explode(";", $line);
            $inseeCode = $line[0];
            $postalCode = $line[1];

            $zones = LwcAdminZone::where('adm4', $inseeCode)->get();
            foreach ($zones as $zone) {
                $zone->update(['postal_code', $postalCode]);
            }

            $cities = LwcCity::where('adm4', $inseeCode)->get();
            foreach ($cities as $city) {
                $city->update(['postal_code', $postalCode]);
            }
        }

        $this->info('End.');
    }

    private function downloadingFile($source, $target)
    {
        if (file_exists($target)) {
            return;
        }
        $this->info("Downloading postal codes into $target");
        if (! copy($source, $target)) {
            throw new \Exception("Failed to download the file $source");
        }
    }
}