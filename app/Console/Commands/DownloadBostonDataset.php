<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Https;
use Illuminate\Support\Facades\Storage;

class DownloadBostonDataset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:download-boston-dataset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Downloads datasets from Boston Open Data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Load the configuration from the config file
        $config = config('datasets');
        $baseUrl = $config['base_url'];
        $datasets = $config['datasets'];

        foreach ($datasets as $dataset) {
            $this->downloadDataset($baseUrl, $dataset['resource_id'], $dataset['format'], $dataset['name']);
        }

        $this->info('Datasets downloaded successfully.');
    }

    /**
     * Download the dataset from the given URL.
     *
     * @param string $baseUrl
     * @param string $resourceId
     * @param string $format
     */
    protected function downloadDataset($baseUrl, $resourceId, $format, $name)
    {
        $url = "{$baseUrl}/{$resourceId}?format={$format}";

        try {
            $response = Https::get($url);

            if ($response->ok()) {
                $filename = $this->generateFilename($name, $format);
                Storage::disk('local')->put($filename, $response->body());
                $this->info("Downloaded {$filename}");
            } else {
                $this->error("Failed to download dataset: {$resourceId}");
            }
        } catch (\Exception $e) {
            $this->error("Error downloading dataset: {$resourceId} - " . $e->getMessage());
        }
    }

    /**
     * Generate a filename for the downloaded dataset.
     *
     * @param string $name
     * @param string $format
     * @return string
     */
    protected function generateFilename($name, $format)
    {
        $timestamp = now()->format('Ymd_His');
        return "datasets/{$name}_{$timestamp}.{$format}";
    }
}
