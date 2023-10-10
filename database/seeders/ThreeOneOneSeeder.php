<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use App\Models\ThreeOneOneCase;
use App\Models\MlModel;
use App\Models\Prediction;

class ThreeOneOneSeeder extends Seeder
{
    private const SEEDERS_DIR = 'seeders/';
    private const BATCH_SIZE = 500;

    public function run(): void
    {
        $seedersDir = database_path(self::SEEDERS_DIR);
        $manifestFiles = glob($seedersDir . "*manifest*");
        
        echo "\nManifest files:\n" . implode("\n", $manifestFiles) . "\n";
        
        $fileCategorization = $this->categorizeFiles($manifestFiles, $seedersDir);
        
        echo "\nTotal records to process: " . $fileCategorization['totalLines'] . "\n";

        $this->processFiles($fileCategorization['casesFiles'], $seedersDir, ThreeOneOneCase::class, ['case_enquiry_id'], $fileCategorization['totalLines']);
        $this->processFiles($fileCategorization['mlModelFiles'], $seedersDir, MlModel::class, ['ml_model_name'], $fileCategorization['totalLines']);
        $this->processFiles($fileCategorization['predictionFiles'], $seedersDir, Prediction::class, ['case_enquiry_id', 'ml_model_id'], $fileCategorization['totalLines']);
        
        $this->renameManifestFiles($manifestFiles);

        
        /* Fetch duplicates based on specific columns
        $duplicates = Prediction::select('three_one_one_case_id', 'ml_model_name')
        ->groupBy('three_one_one_case_id', 'ml_model_name')
        ->havingRaw('COUNT(*) > 1')
        ->get();

        foreach ($duplicates as $duplicate) {
        // Keep one of the duplicate records and delete the rest
            $keepRecord = Prediction::where('three_one_one_case_id', $duplicate->three_one_one_case_id)
                ->where('ml_model_name', $duplicate->ml_model_name)
                ->orderBy('id')  // You can order by other columns if needed
                ->first();

            // If you found a record to keep
            if ($keepRecord) {
                Prediction::where('three_one_one_case_id', $duplicate->three_one_one_case_id)
                    ->where('ml_model_name', $duplicate->ml_model_name)
                    ->where('id', '!=', $keepRecord->id)
                    ->delete();
            }
        } */
        
    }

    private function categorizeFiles(array $manifestFiles, string $seedersDir): array
    {
        $casesFiles = [];
        $mlModelFiles = [];
        $predictionFiles = [];
        $totalLines = 0;

        foreach ($manifestFiles as $manifestFile) {
            $lines = file($manifestFile, FILE_IGNORE_NEW_LINES);
            foreach ($lines as $line) {
                if (file_exists($seedersDir . $line)) {
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

        return [
            'casesFiles' => $casesFiles,
            'mlModelFiles' => $mlModelFiles,
            'predictionFiles' => $predictionFiles,
            'totalLines' => $totalLines
        ];
    }

    private function processFiles(array $files, string $seedersDir, string $modelClass, array $uniqueKeys, int &$totalLines): void
    {
        foreach ($files as $csvFile) {
            $this->processCSV($seedersDir . $csvFile, $modelClass, $uniqueKeys, $totalLines);
        }
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

    private function processCSV(string $filePath, string $modelClass, array $uniqueKeys, int &$totalLines): void
    {
        echo "\nProcessing " . $filePath . "\n";

        $file = fopen($filePath, 'r');
        $header = fgetcsv($file);

        $dataBatch = [];
        $progress = 0;
        $startTime = microtime(true);
        $fileCount = count(file($filePath));

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
                echo "Records remaining in this file: " . ($fileCount - 1 - $progress) . ".\n";
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

            if(count($header) !== count($row)) {
                echo "Warning: Mismatch detected in $filePath at line $progress.\n";
                continue;  // Skip this row
            }
            
            $data = array_combine($header, $row);
            $data = array_map(function ($value) {
                return ($value == '' || $value == ' ') ? null : $value;
            }, $data);
            

            $dataBatch[] = $data;

            if ($progress % self::BATCH_SIZE == 0) {
                if ($modelClass === ThreeOneOneCase::class) {
                    $this->insertOrUpdateCasesBatch($dataBatch, $modelClass, $uniqueKeys);
                } elseif ($modelClass === MlModel::class) {
                    $this->insertOrUpdateMlModelsBatch($dataBatch, $modelClass, $uniqueKeys);
                } elseif ($modelClass === Prediction::class) {
                    $this->insertOrUpdatePredictionsBatch($dataBatch, $modelClass, $uniqueKeys);
                }
                $dataBatch = []; // Reset the batch
            }
        }

        // Process any remaining data
        if (!empty($dataBatch)) {
            if ($modelClass === ThreeOneOneCase::class) {
                $this->insertOrUpdateCasesBatch($dataBatch, $modelClass, $uniqueKeys);
            } elseif ($modelClass === MlModel::class) {
                $this->insertOrUpdateMlModelsBatch($dataBatch, $modelClass, $uniqueKeys);
            } elseif ($modelClass === Prediction::class) {
                $this->insertOrUpdatePredictionsBatch($dataBatch, $modelClass, $uniqueKeys);
            }
        }

        fclose($file);
    }

    private function insertOrUpdateCasesBatch(array $dataBatch, string $modelClass, array $uniqueKeys): void
    { 
        // Convert empty values to null for all data
        foreach ($dataBatch as &$data) {
            $data = array_map(function ($value) {
                return $value === '' ? null : $value;
            }, $data);
            
        }

        // Use the upsert method to insert or update data based on unique keys
        DB::table((new $modelClass)->getTable())->upsert($dataBatch, $uniqueKeys);
    }



    private function insertOrUpdateMlModelsBatch(array $dataBatch, string $modelClass, array $uniqueKeys): void
    {
        foreach ($dataBatch as $data) {
            $conditions = [];
            foreach ($uniqueKeys as $key) {
                $conditions[$key] = $data[$key];
            }
            $modelClass::updateOrCreate($conditions, $data);
        }
    }

    private function insertOrUpdatePredictionsBatch(array $dataBatch, string $modelClass, array $uniqueKeys): void
    {
        // Fetch the three_one_one_case_id for the given case_enquiry_ids
        $caseIds = ThreeOneOneCase::whereIn('case_enquiry_id', array_column($dataBatch, 'case_enquiry_id'))
                                  ->pluck('id', 'case_enquiry_id')
                                  ->toArray();
    
        // Fetch the ml_model_id for the given model_name
        $mlModelIds = MlModel::whereIn('ml_model_name', array_column($dataBatch, 'ml_model_name'))
                             ->pluck('id', 'ml_model_name')
                             ->toArray();
    
        foreach ($dataBatch as &$data) {
            // Convert empty values to null
            $data = array_map(function ($value) {
                return $value === '' ? null : $value;
            }, $data);
    
            // Add the three_one_one_case_id to the data
            $data['three_one_one_case_id'] = $caseIds[$data['case_enquiry_id']] ?? null;
    
            // Add the ml_model_id to the data
            $data['ml_model_id'] = $mlModelIds[$data['ml_model_name']] ?? null;
        }   
    
        // Use the upsert method to insert or update data based on unique keys
        DB::table((new $modelClass)->getTable())->upsert($dataBatch, $uniqueKeys);
    }
    
    
    

    private function renameManifestFiles(array $manifestFiles): void
    {
        foreach ($manifestFiles as $manifestFile) {
            $newName = str_replace('manifest', 'm4n1f3st', $manifestFile);
            rename($manifestFile, $newName);
        }
    }
}
