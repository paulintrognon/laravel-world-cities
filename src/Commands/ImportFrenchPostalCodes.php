<?php

namespace PaulinTrognon\LaravelWorldCities\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use PaulinTrognon\LaravelWorldCities\Models\LwcAdminZone;
use PaulinTrognon\LaravelWorldCities\Models\LwcCity;

class ImportFrenchPostalCodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lwc:postalcodes:fr';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download & import french postal codes';

    protected $customPostalCodes = [
        '69123' => '69000',
        '75056' => '75000',
        '13055' => '13000',
    ];

    /**
     * Execute the console command.
     * @return void
     */
    public function handle()
    {
        $fileName = 'postal_codes_fr.csv';
        $filePath = storage_path("app/geo/$fileName");

        // Downloading the file
        $source = "https://public.opendatasoft.com/explore/dataset/correspondance-code-insee-code-postal/download/?format=csv&timezone=Europe/Berlin&use_labels_for_header=true&csv_separator=%3B";
        $this->downloadingFile($source, $filePath);

        // Importing the postal codes
        $handle = fopen($filePath, 'r');
        $filesize = filesize($filePath);

        ProgressBar::setFormatDefinition('custom', ' %current%/%max%% -- %message%');
        $progressBar = new ProgressBar($this->output, 100);
        $progressBar->setFormat('custom');
        $progressBar->setRedrawFrequency(100);
        $progressBar->maxSecondsBetweenRedraws(0.05);
        $progressBar->minSecondsBetweenRedraws(0.01);

        // We skip line 1
        fgets($handle);
        while (($line = fgets($handle)) !== false)
        {
            $line = explode(";", $line);
            $inseeCode = $line[0];
            $postalCode = explode("/", $line[1])[0] ?? '';

            if (!$postalCode) {
                continue;
            }

            $this->updateCodes($inseeCode, $postalCode);
            
            $progress = ftell($handle) / $filesize * 100;
            $progressBar->setMessage("$line[2]");
            $progressBar->setProgress($progress);
        }

        // Importing custom codes
        $this->importCustomPostalCode();

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

    public function importCustomPostalCode()
    {
        foreach ($this->customPostalCodes as $inseeCode => $postalCode) {
            $this->updateCodes($inseeCode, $postalCode);
        }
    }

    private function updateCodes(string $inseeCode, string $postalCode)
    {
        LwcAdminZone::where('country_iso2', 'FR')->where('adm4', $inseeCode)->update(['postal_code' => $postalCode]);
        LwcCity::where('country_iso2', 'FR')->where('adm4', $inseeCode)->update(['postal_code' => $postalCode]);
    }
}