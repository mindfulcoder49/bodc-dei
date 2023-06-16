<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ThreeOneOneCase;
use App\Models\Prediction;
use App\Models\MlModel;

class ThreeOneOneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Read a csv file line by line
        
        $file = fopen(database_path('seeders/311.csv'), 'r');
        $header = fgetcsv($file);
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

        $file = fopen(database_path('seeders/311_ml_models.csv'), 'r');
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

        $file = fopen(database_path('seeders/311_predictions.csv'), 'r');
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
}
