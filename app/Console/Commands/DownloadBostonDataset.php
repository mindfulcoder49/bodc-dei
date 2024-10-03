<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DownloadBostonDataset extends Command
{
    protected $signature = 'app:download-boston-dataset';
    protected $description = 'Downloads datasets from Boston Open Data';

    public function handle()
    {
        // Load the configuration from the config file
        $config = config('datasets');
        $baseUrl = $config['base_url'];
        $datasets = $config['datasets'];

        foreach ($datasets as $dataset) {
            $this->downloadDataset($baseUrl, $dataset['resource_id'], $dataset['format'], $dataset['name']);
        }

        $this->info('Datasets download attempted.');
    }

    protected function downloadDataset($baseUrl, $resourceId, $format, $name)
    {
        $url = "{$baseUrl}/{$resourceId}?format={$format}";
        $filename = $this->generateFilename($name, $format);
        $destination = storage_path("app/{$filename}");

        $this->info("Attempting to download dataset: {$name} from {$url}...");

        // Download the dataset file
        if ($this->downloadFile($url, $destination)) {
            $this->info("Downloaded {$filename}");
        } else {
            $this->error("Failed to download dataset: {$resourceId}");
        }
    }

    /**
     * Download the file from the URL.
     * 
     * @param string $url
     * @param string $destination
     * @return bool
     */
    private function downloadFile(string $url, string $destination): bool
    {
        try {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
            curl_setopt($ch, CURLOPT_TIMEOUT, 30); // Set a timeout for the request
    
            $fileContents = curl_exec($ch);
    
            // Check for curl errors
            if (curl_errno($ch)) {
                $this->error("cURL error: " . curl_error($ch));
                curl_close($ch);
                return false;
            }
    
            // Get HTTP status code
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
            // Ensure we got a 200 OK response
            if ($httpCode !== 200) {
                $this->error("HTTP request failed with status code: " . $httpCode);
                curl_close($ch);
                return false;
            }
    
            // Check if content is valid
            if (empty($fileContents)) {
                $this->error("Downloaded file is empty.");
                curl_close($ch);
                return false;
            }
    
            // Save file contents to destination
            file_put_contents($destination, $fileContents);
            curl_close($ch);
            return true;
        } catch (\Exception $e) {
            $this->error("Error downloading the file: " . $e->getMessage());
            return false;
        }
    }
    
    

    protected function generateFilename($name, $format)
    {
        $timestamp = now()->format('Ymd_His');
        return "datasets/{$name}_{$timestamp}.{$format}";
    }
}
