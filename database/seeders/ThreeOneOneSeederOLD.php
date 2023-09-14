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
        
        // Initialize a counter for the total number of lines across all files
        $totalLines = 0;
        
        // Loop through each manifest file and categorize the files listed within
        foreach ($manifestFiles as $manifestFile) {
            $lines = file($manifestFile, FILE_IGNORE_NEW_LINES);
            foreach ($lines as $line) {
                if (file_exists($seedersDir . $line)) {
                    // Count the lines in the file (subtracting 1 for the header) and add to the total
                    $totalLines += count(file($seedersDir . $line)) - 1;
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

        // Inform the user about the total number of records to be processed
        echo "\nTotal records to process: " . $totalLines . "\n";

        // Process the categorized files
        $this->processFiles($casesFiles, $seedersDir, 'ThreeOneOneCase', ['case_enquiry_id'], $totalLines);
        $this->processFiles($mlModelFiles, $seedersDir, 'MlModel', ['ml_model_name'], $totalLines);
        $this->processFiles($predictionFiles, $seedersDir, 'Prediction', ['case_enquiry_id', 'ml_model_name', 'prediction_date'], $totalLines);
        
        // Rename the manifest files to indicate they've been processed
        foreach ($manifestFiles as $manifestFile) {
            $newName = str_replace('manifest', 'm4n1f3st', $manifestFile);
            rename($manifestFile, $newName);
        } 
    }

    /**
     * Process a list of files of a specific type.
     */
    private function processFiles($files, $seedersDir, $modelClass, $uniqueKeys, &$totalLines)
    {
        foreach ($files as $csvFile) {
            $this->processCSV($seedersDir . $csvFile, $modelClass, $uniqueKeys, $totalLines);
        }
    }

    /**
     * Format the given time in seconds into a readable format.
     *
     * @param float $timeInSeconds
     * @return string
     */
    private function formatTime($timeInSeconds)
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


            
    /**
     * Process a specific CSV file and insert/update records in the database.
     */
    private function processCSV($filePath, $modelClass, $uniqueKeys, &$totalLines)
    {
        echo "\nProcessing ".$filePath."\n";

        $file = fopen($filePath, 'r');
        $header = fgetcsv($file);

        $progress = 0;
        $startTime = microtime(true);

        // Get the file count once before the loop
        $fileCount = count(file($filePath));

        // Loop through each line in the CSV
        while ($row = fgetcsv($file)) {
            $progress++;
            $totalLines--;

            if ($progress % 100 == 0) {
                $endTime = microtime(true);
                $timeTaken = $endTime - $startTime;
                $estimatedTimePerRecord = $timeTaken / 100;
                $estimatedTimeRemainingFile = $estimatedTimePerRecord * ($fileCount - 1 - $progress);
                $estimatedTimeRemainingTotal = $estimatedTimePerRecord * $totalLines;
            
            
                // Clear the previous 6 lines
                echo "\033[6A";  // Move 6 lines up
                echo "\033[K";   // Clear current line
                echo $progress . " " . $modelClass . " records processed.\n";
                echo "\033[K";   // Clear current line
                echo "Records remaining in this file: " . (count(file($filePath)) - 1 - $progress) . ".\n";
                echo "\033[K";   // Clear current line
                echo "Total records remaining: " . $totalLines . ".\n";
                echo "\033[K";   // Clear current line
                echo "Time for last 100 records: " . round($timeTaken, 2) . " seconds.\n";
                echo "\033[K";   // Clear current line
                echo "Estimated time remaining for this file: " . $this->formatTime($estimatedTimeRemainingFile) . ".\n";
                echo "\033[K";   // Clear current line
                echo "Estimated time for all files: " . $this->formatTime($estimatedTimeRemainingTotal) . ".\n";
            
                $startTime = microtime(true);
                gc_collect_cycles();
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
