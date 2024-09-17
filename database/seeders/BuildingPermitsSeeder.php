<?php

// database/seeders/BuildingPermitsSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BuildingPermit;
use Illuminate\Support\Facades\File;
use League\Csv\Reader;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BuildingPermitsSeeder extends Seeder
{
    private const BATCH_SIZE = 500;

    public function run()
    {
        $name = 'building-permits';
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

    private function processFile($filePath)
    {
        try {
            $csv = Reader::createFromPath($filePath, 'r');
            $csv->setHeaderOffset(0); // The header is on the first row
            //set the escape character to null
            $csv->setEscape('');

            $records = $csv->getRecords();

            $dataBatch = [];
            $progress = 0;

            foreach ($records as $permit) {
                $progress++;
                //echo $date, permit number, etc, on one line
                echo "SqFt contains: " . $permit['sq_feet'] . " Permit number: " . $permit['permitnumber'] . "\n";
                // Check and format datetime fields
                $issuedDate = $this->formatDate($permit['issued_date']);
                $expirationDate = $this->formatDate($permit['expiration_date']);

                //check sq_feet and convert to integer if it is a number, otherwise set to 0
                $sqfeet = is_numeric($permit['sq_feet']) ? (int)$permit['sq_feet'] : 0;

                //set it to a maximum of one billion
                if ($sqfeet > 1000000000) {
                    $sqfeet = 1000000000;
                }



                //convert empty strings to null
                foreach ($permit as $key => $value) {
                    if ($value === '') {
                        $permit[$key] = null;
                    }
                }

                // Add data to batch array
                $dataBatch[] = [
                    'permitnumber' => $permit['permitnumber'],
                    'worktype' => $permit['worktype'],
                    'permittypedescr' => $permit['permittypedescr'],
                    'description' => $permit['description'],
                    'comments' => $permit['comments'],
                    'applicant' => $permit['applicant'],
                    'declared_valuation' => $permit['declared_valuation'],
                    'total_fees' => $permit['total_fees'],
                    'issued_date' => $issuedDate,
                    'expiration_date' => $expirationDate,
                    'status' => $permit['status'],
                    'occupancytype' => $permit['occupancytype'],
                    'sq_feet' => $sqfeet,
                    'address' => $permit['address'],
                    'city' => $permit['city'],
                    'state' => $permit['state'],
                    'zip' => $permit['zip'],
                    'property_id' => $permit['property_id'],
                    'parcel_id' => $permit['parcel_id'],
                    'gpsy' => $permit['gpsy'],
                    'gpsx' => $permit['gpsx'],
                    'y_latitude' => $permit['y_latitude'],
                    'x_longitude' => $permit['x_longitude'],
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

            echo "File processed: " . basename($filePath) . "\n";
        } catch (\Exception $e) {
            echo "Error processing file: " . basename($filePath) . " - " . $e->getMessage() . "\n";
        }
    }

    private function formatDate($date)
    {

        if (empty($date)) {
            // Set to null or a default value that makes sense for your application
            return null;
        }

        if ( $date == '1970-01-01 00:00:00') {
            echo "Date is 1970-01-01 00:00:00\n";
            return null;
        }

        //if date is before epoch time, set to null
        if (strtotime($date) < 0) {
            echo "Date is before epoch time\n";
            return null;
        }

        
        // Ensure date is in the correct format
        $formattedDate = date('Y-m-d H:i:s', strtotime($date));

        return $formattedDate;
    }
        

    private function insertOrUpdateBatch(array $dataBatch): void
    {
        DB::table((new BuildingPermit)->getTable())->upsert($dataBatch, ['permitnumber'], [
            'worktype', 'permittypedescr', 'description', 'comments', 'applicant', 
            'declared_valuation', 'total_fees', 'issued_date', 'expiration_date', 
            'status', 'occupancytype', 'sq_feet', 'address', 'city', 'state', 'zip', 
            'property_id', 'parcel_id', 'gpsy', 'gpsx', 'y_latitude', 'x_longitude'
        ]);
    }

    private function parseFloat($value)
    {
        // Return float value or null if the value is not numeric
        return is_numeric($value) ? (float)$value : null;
    }
}
