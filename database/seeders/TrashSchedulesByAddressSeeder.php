<?php

// database/seeders/TrashSchedulesByAddressSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TrashScheduleByAddress;
use Illuminate\Support\Facades\File;
use League\Csv\Reader;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TrashSchedulesByAddressSeeder extends Seeder
{
    private const BATCH_SIZE = 500;

    public function run()
    {

        $name = 'trash-schedules-by-address';
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

        foreach ($records as $trashSchedule) {
            $progress++;

            // Add data to batch array
            $dataBatch[] = [
                'sam_address_id' => $trashSchedule['sam_address_id'],
                'full_address' => $trashSchedule['full_address'],
                'mailing_neighborhood' => $trashSchedule['mailing_neighborhood'],
                'state' => $trashSchedule['state'],
                'zip_code' => $trashSchedule['zip_code'],
                'x_coord' => $trashSchedule['x_coord'],
                'y_coord' => $trashSchedule['y_coord'],
                'recollect' => $trashSchedule['recollect'] == 'T',
                'trashday' => $trashSchedule['trashday'],
                'pwd_district' => $trashSchedule['pwd_district'],
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
        DB::table((new TrashScheduleByAddress)->getTable())->upsert($dataBatch, ['sam_address_id'], [
            'full_address', 'mailing_neighborhood', 'state', 'zip_code', 'x_coord', 
            'y_coord', 'recollect', 'trashday', 'pwd_district'
        ]);
    }
}
