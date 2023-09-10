<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ThreeOneOneCase;
use App\Models\Prediction;
use App\Models\MlModel;

class ThreeOneOneSeeder extends Seeder
{

    public function run(): void
    {
        $dateTime = new \DateTime('now', new \DateTimeZone('America/New_York'));
        $today = $dateTime->format('Y-m-d');
        
        $this->processCSV("seeders/".$today."_311_cases.csv", 'ThreeOneOneCase', ['case_enquiry_id']);
        $this->processCSV("seeders/".$today."_311_ml_models.csv", 'MlModel');
        $this->processCSV("seeders/".$today."_311_predictions.csv", 'Prediction', ['case_enquiry_id', 'ml_model_id', 'prediction_date']);
    }

    private function processCSV($filePath, $modelClass, $uniqueKeys = ['id'])
{
    $file = fopen(database_path($filePath), 'r');
    $header = fgetcsv($file);

    // Processing headers with array_map
    if ($modelClass == 'ThreeOneOneCase') {
        $header = array_map(function ($value) {
            return str_replace(['sla_target_dt', 'on_time', 'closed_photo', 'submitted_photo'], 
                               ['target_dt', 'ontime', 'closedphoto', 'submittedphoto'], 
                               $value);
        }, $header);
    }

    $progress = 0;
    while ($row = fgetcsv($file)) {
        $progress++;
        //if $progress is divisible by 1000, update with $progress
        if ($progress % 20 == 0) {
            echo "\r".$progress . " ". $modelClass ." records processed";
        }
        $data = array_combine($header, $row);
        $data = array_filter($data, function($value) {
            return !($value == '' || $value == ' ');
        });

        $conditions = [];
        foreach ($uniqueKeys as $key) {
            $conditions[$key] = $data[$key];
        }

        $model = '\\App\\Models\\' . $modelClass;
        $model::updateOrCreate($conditions, $data);
    }
    echo "\nfinal progress: ".$progress . " ". $modelClass ." records processed\n";
    fclose($file);
}


    /**
     * Run the database seeds.
    
    public function run(): void
    {
        //get todays datestring
        $dateTime = new \DateTime('now', new \DateTimeZone('America/New_York'));
        $today = $dateTime->format('Y-m-d');
        
        
        //Read a csv file line by line
        
        $file = fopen(database_path("seeders/".$today."_311_cases.csv"), 'r');
        $header = fgetcsv($file);
        //replace any values sla_target_date values ith target_date
        $header = array_map(function ($value) {
            return str_replace('sla_target_dt', 'target_dt', $value);
        }, $header);
        //replace any values on_time with ontime
        $header = array_map(function ($value) {
            return str_replace('on_time', 'ontime', $value);
        }, $header);
        //replace any values closed_photo with closedphoto
        $header = array_map(function ($value) {
            return str_replace('closed_photo', 'closedphoto', $value);
        }, $header);
        $header = array_map(function ($value) {
            return str_replace('submitted_photo', 'submittedphoto', $value);
        }, $header);
        while ($row = fgetcsv($file)) {
            $data = array_combine($header, $row);
            //Check if $data['closed_dt'] is empty, remove it from the array if it is
            foreach ($data as $key => $value) {
                if ($value == '' || $value == ' ') {
                    unset($data[$key]);
                }
            }

            //Check if the case already exists
            $case = ThreeOneOneCase::where('case_enquiry_id', $data['case_enquiry_id'])->first();
            if ($case) {
                //update the case 
                $case->update($data);
            } else {
                //create the case
                $case = ThreeOneOneCase::create($data);
            }
        } 

        
        $file = fopen(database_path('seeders/'.$today.'_311_ml_models.csv'), 'r');
        $header = fgetcsv($file);
        while ($row = fgetcsv($file)) {
            $data = array_combine($header, $row);

            //Check if the MlModel already exists
            $mlModel = MlModel::where('id', $data['id'])->first();
            if ($mlModel) {
                //update the MlModel 
                $mlModel->update($data);
            } else {
                //create the MlModel
                $mlModel = MlModel::create($data);
            }
        }
        
        $file = fopen(database_path('seeders/'.$today.'_311_predictions.csv'), 'r');
        $header = fgetcsv($file);
        while ($row = fgetcsv($file)) {
            $data = array_combine($header, $row);
            //Check if the prediction already exists for the MlModel

            $prediction = Prediction::where('case_enquiry_id', $data['case_enquiry_id'])->where('ml_model_id', $data['ml_model_id'])->first();
            if ($prediction) {
                //update the prediction
                $prediction->update($data);
            } else {
                //create the prediction
                $prediction = Prediction::create($data);
            }
        }
    }
    */
}


/*
Namespace and Imports:

Lines 1-8: The script begins by defining the namespace Database\Seeders and importing various classes and models that will be used in the seeder.
Class Definition:

Line 10: The class ThreeOneOneSeeder is defined, which extends Laravels Seeder class.
Method run:

Lines 15-16: This method, when invoked, will seed the database based on the logic contained within it.
Reading and Processing the 311.csv file:

Lines 17-37:
Opens the 311.csv file and reads its content line by line.
Each line (representing a record) is processed as follows:
Empty values are removed.
If a ThreeOneOneCase with a matching case_enquiry_id exists in the database, it is updated. Otherwise, a new case is created.
Reading and Processing the 311_ml_models.csv file:

Lines 38-59:
Opens the 311_ml_models.csv file and reads its content.
Each line (representing a machine learning model) is processed in a similar way:
If an MlModel with a matching id exists, it is updated. Otherwise, a new model record is created.
Reading and Processing the 311_predictions.csv file:

Lines 60-80:
Opens the 311_predictions.csv file.
Each line (representing a prediction) is processed as follows:
Checks if a Prediction with a matching case_enquiry_id and ml_model_id exists. If it does, its updated. If not, a new prediction record is created.

*/


