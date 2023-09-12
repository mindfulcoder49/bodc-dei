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
        $seedersDir = database_path("seeders/");
        $manifestFiles = glob($seedersDir . "*manifest*");
        echo "\nmanifest files:\n".implode("\n", $manifestFiles)."\n";
        
        $casesFiles = [];
        $mlModelFiles = [];
        $predictionFiles = [];
        
        foreach ($manifestFiles as $manifestFile) {
            $lines = file($manifestFile, FILE_IGNORE_NEW_LINES);
            foreach ($lines as $line) {
                if (file_exists($seedersDir . $line)) {
                    if (strpos($line, '311_cases') !== false) {
                        $casesFiles[] = $line;
                    } elseif (strpos($line, '311_ml_models') !== false) {
                        $mlModelFiles[] = $line;
                    } elseif (strpos($line, '311_predictions') !== false) {
                        $predictionFiles[] = $line;
                    }
                }
            }
        }

        // Process the cases files first
        foreach ($casesFiles as $csvFile) {
            $this->processCSV($seedersDir . $csvFile, 'ThreeOneOneCase', ['case_enquiry_id']);
        }

        // Then, process the ml_model files
        foreach ($mlModelFiles as $csvFile) {
            $this->processCSV($seedersDir . $csvFile, 'MlModel', ['ml_model_name']);
        }

        // Lastly, process the prediction files
        foreach ($predictionFiles as $csvFile) {
            $this->processCSV($seedersDir . $csvFile, 'Prediction', ['case_enquiry_id', 'ml_model_name', 'prediction_date']);
        }
        
        foreach ($manifestFiles as $manifestFile) {
            $newName = str_replace('manifest', 'm4n1f3st', $manifestFile);
            rename($manifestFile, $newName);
        } 
    }


    private function processCSV($filePath, $modelClass, $uniqueKeys = ['id'])
    {
        echo "\nProcessing ".$filePath."\n";

        $file = fopen($filePath, 'r');
        $header = fgetcsv($file);

        if ($modelClass == 'ThreeOneOneCase') {
            $header = array_map(function ($value) {
                return str_replace(
                    ['sla_target_dt', 'on_time', 'closed_photo', 'submitted_photo'], 
                    ['target_dt', 'ontime', 'closedphoto', 'submittedphoto'], 
                    $value
                );
            }, $header);
        }

        $progress = 0;
        while ($row = fgetcsv($file)) {
            $progress++;
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

            if ($modelClass == 'Prediction') {
                // Here we get the actual IDs for foreign keys
                $case = ThreeOneOneCase::where('case_enquiry_id', $data['case_enquiry_id'])->first();
                $mlModel = MlModel::where('ml_model_name', $data['ml_model_name'])->first();
            
                if (!$case) {
                    echo "\nWarning: Skipping Prediction record due to missing Case reference. Case with ID {$data['case_enquiry_id']} not found.\n";
                    continue;  // Skip this iteration and continue with the next one
                }
            
                if (!$mlModel) {
                    echo "\nWarning: Skipping Prediction record due to missing ML Model reference. ML Model with name {$data['ml_model_name']} not found.\n";
                    continue;  // Skip this iteration and continue with the next one
                }
            
                $data['three_one_one_case_id'] = $case->id;
                $data['ml_model_id'] = $mlModel->id;
            
                unset($data['ml_model_name']);
            }
            
            

            $model = '\\App\\Models\\' . $modelClass;
            $model::updateOrCreate($conditions, $data);
        }

        fclose($file);
    }
}
