<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Buildingpermits;
use Illuminate\Support\Facades\File;
use League\Csv\Reader;
use Illuminate\Support\Facades\DB;

class BuildingpermitsSeeder extends Seeder
{
    private const BATCH_SIZE = 500;

    public function run()
    {
        $files = File::files(public_path('BuildingPermits'));

        foreach ($files as $file) {
            $this->processFile($file);
        }
    }

    private function processFile($file)
    {
        $csv = Reader::createFromPath($file);
        $csv->setHeaderOffset(0);
        $records = $csv->getRecords();

        $dataBatch = [];
        $progress = 0;

        foreach ($records as $record) {
            $progress++;
            $dataBatch[] = {
                'permitnumber', 'worktype', 'permittypedescr', 'description', 'comments', 'applicant', 'declared_valuation', 'total_fees', 'issued_date', 'expiration_date', 'status', 'occupancytype', 'sq_feet', 'address', 'city', 'state', 'zip', 'property_id', 'parcel_id', 'gpsy', 'gpsx', 'y_latitude', 'x_longitude', 
                'created_at': now(),
                'updated_at': now(),
            };

            if ($progress % self::BATCH_SIZE == 0) {
                DB::table((new Buildingpermits)->getTable())->insert($dataBatch);
                $dataBatch = [];
            }
        }

        if (!empty($dataBatch)) {
            DB::table((new Buildingpermits)->getTable())->insert($dataBatch);
        }
    }
}
