<?php

// database/seeders/ConstructionOffHoursSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ConstructionOffHour;
use Illuminate\Support\Facades\File;
use League\Csv\Reader;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ConstructionOffHoursSeeder extends Seeder
{
    private const BATCH_SIZE = 500;

    public function run()
    {
        $name = 'construction-off-hours';
        // get most recent file from Storage::disk('local'), filename is name with date appended
        $files = Storage::disk('local')->files($name);
        $file = end($files);

        foreach ($files as $file) {
            $this->processFile($file);
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
        $fileCount = count(file($file));
        $skipped = 0;

        foreach ($records as $offHour) {
            $progress++;

            // Add data to batch array
            $dataBatch[] = [
                'app_no' => $offHour['app_no'],
                'start_datetime' => $offHour['start_datetime'],
                'stop_datetime' => $offHour['stop_datetime'],
                'address' => $offHour['address'],
                'ward' => $offHour['ward'],
            ];

            if ($progress % self::BATCH_SIZE == 0) {
                $this->insertOrUpdateBatch($dataBatch);
                $dataBatch = []; // Reset the batch
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
        DB::table((new ConstructionOffHour)->getTable())->upsert($dataBatch, ['app_no'], [
            'start_datetime', 'stop_datetime', 'address', 'ward'
        ]);
    }
}
