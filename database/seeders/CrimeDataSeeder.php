<?php

// database/seeders/CrimeDataSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CrimeData;
use Illuminate\Support\Facades\File;
use League\Csv\Reader;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CrimeDataSeeder extends Seeder
{
    private const BATCH_SIZE = 500;

    public function run()
    {
        $name = 'crime-incident-reports';
        // Get all files from the datasets folder in Storage
        $files = Storage::disk('local')->files('datasets');

        // Filter files to only include those with the specified name in the filename
        $files = array_filter($files, function ($file) use ($name) {
            return strpos($file, $name) !== false;
        });

        // Only proceed if there are any files to process
        if (!empty($files)) {
            // Get the most recent file
            $file = end($files);
            echo "Processing file: " . $file . "\n";

            // Process the most recent file
            $this->processFile(Storage::path($file));
        } else {
            echo "No files found to process for name: " . $name . "\n";
        }
    }

    private function processFile($file)
    {
        print_r("Processing file: " . $file . "\n");
        $csv = Reader::createFromPath($file);
        $csv->setHeaderOffset(0); // The header is on the first row

        $records = $csv->getRecords();

        $dataBatch = [];
        $progress = 0;
        $startTime = microtime(true);
        $fileCount = count(file($file));
        $skipped = 0;

        foreach ($records as $crime) {
            $progress++;

            if (!is_numeric($crime['Lat']) || !is_numeric($crime['Long'])) {
                print_r("Skipping record with invalid lat/long: " . $crime['Lat'] . ", " . $crime['Long'] . "\n");
                $skipped++;
                continue;
            }

            $occurred_on_date = $this->formatDate($crime['OCCURRED_ON_DATE']);

            $dataBatch[] = [
                'incident_number' => $crime['INCIDENT_NUMBER'],
                'offense_code' => $crime['OFFENSE_CODE'],
                'offense_code_group' => $crime['OFFENSE_CODE_GROUP'],
                'offense_description' => $crime['OFFENSE_DESCRIPTION'],
                'district' => $crime['DISTRICT'],
                'reporting_area' => $crime['REPORTING_AREA'],
                'shooting' => $crime['SHOOTING'] == 'Y',
                'occurred_on_date' => $occurred_on_date,
                'year' => $crime['YEAR'],
                'month' => $crime['MONTH'],
                'day_of_week' => $crime['DAY_OF_WEEK'],
                'hour' => $crime['HOUR'],
                'ucr_part' => $crime['UCR_PART'],
                'street' => $crime['STREET'],
                'lat' => $crime['Lat'],
                'long' => $crime['Long'],
                'location' => $crime['Location'],
                'offense_category' => $this->categorizeOffense($crime['OFFENSE_CODE']),
            ];

            if ($progress % self::BATCH_SIZE == 0) {
                $this->insertOrUpdateBatch($dataBatch);
                $dataBatch = []; // Reset the batch

                // Progress reporting
                $endTime = microtime(true);
                $timeTaken = $endTime - $startTime;
                $this->reportProgress($progress, $fileCount, $timeTaken);
                $startTime = microtime(true);
            }
        }

        // Process any remaining data
        if (!empty($dataBatch)) {
            $this->insertOrUpdateBatch($dataBatch);
        }

        print_r("File processed: " . $file . "\n");
    }

    private function insertOrUpdateBatch(array $dataBatch): void
    {
        DB::table((new CrimeData)->getTable())->upsert($dataBatch, ['incident_number'], [
            'offense_code',
            'offense_code_group',
            'offense_description',
            'district',
            'reporting_area',
            'shooting',
            'occurred_on_date',
            'year',
            'month',
            'day_of_week',
            'hour',
            'ucr_part',
            'street',
            'lat',
            'long',
            'location',
            'offense_category'
        ]);
    }

    private function reportProgress($progress, $fileCount, $timeTaken)
    {
        $estimatedTimePerRecord = $timeTaken / self::BATCH_SIZE;
        $estimatedTimeRemainingFile = $estimatedTimePerRecord * ($fileCount - $progress);
        
        // Clear the previous 5 lines
        echo "\033[5A";  // Move 5 lines up
        echo "\033[K";   // Clear current line
        echo $progress . " records processed.\n";
        echo "\033[K";   // Clear current line
        echo "Records remaining in this file: " . ($fileCount - $progress) . ".\n";
        echo "\033[K";   // Clear current line
        echo "Time for last " . self::BATCH_SIZE . " records: " . round($timeTaken, 2) . " seconds.\n";
        echo "\033[K";   // Clear current line
        echo "Estimated time remaining for this file: " . $this->formatTime($estimatedTimeRemainingFile) . ".\n";
    }

    private function formatTime(float $timeInSeconds): string
    {
        $hours = floor($timeInSeconds / 3600);
        $minutes = floor(($timeInSeconds % 3600) / 60);
        $seconds = $timeInSeconds % 60;

        $formattedTime = [];
        if ($hours > 0) {
            $formattedTime[] = $hours . ' hour' . ($hours > 1 ? 's' : '');
        }
        if ($minutes > 0 || $hours > 0) {
            $formattedTime[] = $minutes . ' minute' . ($minutes > 1 ? 's' : '');
        }
        $formattedTime[] = round($seconds, 2) . ' second' . ($seconds != 1 ? 's' : '');

        return implode(', ', $formattedTime);
    }

    private function formatDate($date)
    {
        // Strip timezone offset
        if (strpos($date, '+') !== false) {
            $date = explode('+', $date)[0];
        } elseif (strpos($date, '-') !== false) {
            $date = explode('-', $date)[0];
        }

        // Convert to datetime
        return date('Y-m-d H:i:s', strtotime($date));
    }

    private function categorizeOffense($code)
    {
        $categories = [
            'murder_and_manslaughter' => [111, 112, 113, 114, 121, 122, 123, 124, 125],
            'rape' => [
                211, 212, 213, 222, 223, 224, 230, 231, 232, 233, 234, 235, 236, 237,
                241, 242, 243, 244, 251, 252, 253, 254, 261, 271
            ],
            'robbery' => range(301, 382),
            'assault' => range(401, 434),
            'burglary' => range(510, 563),
            'larceny' => range(611, 670),
            'auto_theft' => range(701, 791),
            'simple_assault' => range(801, 805),
            'arson' => range(900, 931),
            'forgery_counterfeiting' => range(1001, 1003),
            'fraud' => range(1101, 1110),
            'embezzlement' => [1201],
            'stolen_property' => range(1300, 1305),
            'vandalism' => range(1401, 1416),
            'weapons_violations' => range(1501, 1511),
            'prostitution' => range(1601, 1621),
            'sex_offenses' => range(1702, 1732),
            'drug_violations' => range(1805, 1876),
            'gambling' => range(1901, 1922),
            'child_offenses' => range(2003, 2011),
            'alcohol_violations' => range(2101, 2205),
            'disorderly_conduct' => range(2401, 2408),
            'kidnapping' => range(2511, 2625),
            'miscellaneous_offenses' => range(2628, 2673),
            'vehicle_laws' => range(2801, 2918),
            'investigations' => range(3001, 3041),
            'other_services' => range(3102, 3171),
            'property' => range(3201, 3211),
            'disputes' => range(3301, 3306),
            'animal_incidents' => range(3402, 3415),
            'missing_persons' => range(3501, 3504),
            'other_reports' => range(3612, 3626),
            'accidents' => range(3701, 3832)
        ];

        foreach ($categories as $category => $codes) {
            if (in_array($code, $codes)) {
                return $category;
            }
        }

        return 'other_offenses';
    }
}
