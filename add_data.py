import os
import requests
from bs4 import BeautifulSoup
from datetime import datetime
import pandas as pd

def get_data_urls(base_url):
    print("Trying to get data CSV URLs")

    response = requests.get(base_url)
    soup = BeautifulSoup(response.content, 'html.parser')

    now = datetime.now()
    current_year = now.year
    URL_dict = {}

    for link in soup.find_all('a'):
        url = link.get('href')
        if url and url.endswith('.csv'):
            print("Found URL:", url, "for year", current_year)
            URL_dict[str(current_year)] = url
            current_year -= 1 
    return URL_dict

def load_data_from_urls(data_dir, base_url, *args):
    print("Checking files_dict")
    try:
        print("Trying to call get_data_urls")
        files_dict = get_data_urls(base_url)
        print("files_dict is", files_dict)
    except Exception as e:
        print(f"Error getting data URLs: {e}")
        files_dict = {}

    all_files = [files_dict[str(value)] for value in args[0] if str(value) in files_dict.keys()] if args else list(files_dict.values())

    print("All files are", all_files)
    dfs = []

    if not os.path.exists(data_dir):
        os.makedirs(data_dir)

    for file_url in all_files:
        file_name = file_url.split('/')[-1]
        file_path = os.path.join(data_dir, file_name)

        if os.path.exists(file_path):
            print(f"Loading data from existing file {file_path}")
            df = pd.read_csv(file_path)
        else:
            print(f"Downloading data from {file_url}")
            df = pd.read_csv(file_url)
            df.to_csv(file_path, index=False)
            print(f"Saved downloaded data to {file_path}")

        print("Loaded data with shape:", df.shape)
        dfs.append(df)
        print("Appended data to list")

    same_list_num_col = []
    diff_list_num_col = []
    same_list_order_col = []
    diff_list_order_col = []

    for i in range(len(dfs)):
        if dfs[i].shape[1] != dfs[0].shape[1]:
            diff_list_num_col.append(i)
        else:
            same_list_num_col.append(i)
        if not dfs[i].columns.equals(dfs[0].columns):
            diff_list_order_col.append(i)
        else:
            same_list_order_col.append(i)

    df_all = pd.concat(dfs, ignore_index=True)
    return df_all

def generate_laravel_files(df, laravel_project_dir, dataset_name):
    controller_name = f"{dataset_name.capitalize()}Controller"
    model_name = dataset_name.capitalize()
    migration_table_name = dataset_name
    vue_component_name = dataset_name.capitalize() + "Map"

    columns = df.columns
    migration_fields = ""
    model_fields = ""
    fillable_fields = ""

    for col in columns:
        col_type = "string"
        if df[col].dtype == "int64":
            col_type = "integer"
        elif df[col].dtype == "float64":
            col_type = "decimal"
        migration_fields += f"$table->{col_type}('{col}');\n"
        model_fields += f"'{col}', "
        fillable_fields += f"'{col}', "

    migration_content = f"""<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{{
    public function up()
    {{
        Schema::create('{migration_table_name}', function (Blueprint $table) {{
            $table->id();
            {migration_fields}
            $table->timestamps();
        }});
    }}

    public function down()
    {{
        Schema::dropIfExists('{migration_table_name}');
    }}
}};
"""
    model_content = f"""<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class {model_name} extends Model
{{
    use HasFactory;

    protected $table = '{migration_table_name}';

    protected $fillable = [
        {fillable_fields}
    ];
}}
"""
    seeder_content = f"""<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{model_name};
use Illuminate\Support\Facades\File;
use League\Csv\Reader;
use Illuminate\Support\Facades\DB;

class {model_name}Seeder extends Seeder
{{
    private const BATCH_SIZE = 500;

    public function run()
    {{
        // Ingest each CSV in the {dataset_name} folder one by one
        $files = File::files(public_path('{dataset_name}'));

        foreach ($files as $file) {{
            $this->processFile($file);
        }}
    }}

    private function processFile($file)
    {{
        print_r("Processing file: " . $file . "\\n");
        $csv = Reader::createFromPath($file);
        $csv->setHeaderOffset(0); // The header is on the first row

        $records = $csv->getRecords();

        $dataBatch = [];
        $progress = 0;
        $startTime = microtime(true);
        $fileCount = count(file($file));
        $skipped = 0;

        foreach ($records as $record) {{
            $progress++;

            {self._build_skip_invalid_lat_long(df)}

            {self._build_date_formatting(df)}

            $dataBatch[] = [
                {self._build_data_batch(df)}
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if ($progress % self::BATCH_SIZE == 0) {{
                $this->insertOrUpdateBatch($dataBatch);
                $dataBatch = []; // Reset the batch

                // Progress reporting
                $endTime = microtime(true);
                $timeTaken = $endTime - $startTime;
                $this->reportProgress($progress, $fileCount, $timeTaken);
                $startTime = microtime(true);
            }}
        }}

        // Process any remaining data
        if (!empty($dataBatch)) {{
            $this->insertOrUpdateBatch($dataBatch);
        }}

        print_r("File processed: " . $file . "\\n");
    }}

    private function insertOrUpdateBatch(array $dataBatch): void
    {{
        DB::table((new {model_name})->getTable())->upsert($dataBatch, ['id'], [
            {", ".join([f"'{col}'" for col in columns])}
        ]);
    }}

    private function reportProgress($progress, $fileCount, $timeTaken)
    {{
        $estimatedTimePerRecord = $timeTaken / self::BATCH_SIZE;
        $estimatedTimeRemainingFile = $estimatedTimePerRecord * ($fileCount - $progress);
        
        // Clear the previous 5 lines
        echo "\\033[5A";  // Move 5 lines up
        echo "\\033[K";   // Clear current line
        echo $progress . " records processed.\\n";
        echo "\\033[K";   // Clear current line
        echo "Records remaining in this file: " . ($fileCount - $progress) . ".\\n";
        echo "\\033[K";   // Clear current line
        echo "Time for last " . self::BATCH_SIZE . " records: " . round($timeTaken, 2) . " seconds.\\n";
        echo "\\033[K";   // Clear current line
        echo "Estimated time remaining for this file: " . $this->formatTime($estimatedTimeRemainingFile) . ".\\n";
    }}

    private function formatTime(float $timeInSeconds): string
    {{
        $hours = floor($timeInSeconds / 3600);
        $minutes = floor(($timeInSeconds % 3600) / 60);
        $seconds = $timeInSeconds % 60;

        $formattedTime = [];
        if ($hours > 0) {{
            $formattedTime[] = $hours . ' hour' . ($hours > 1 ? 's' : '');
        }}
        if ($minutes > 0 || $hours > 0) {{
            $formattedTime[] = $minutes . ' minute' . ($minutes > 1 ? 's' : '');
        }}
        $formattedTime[] = round($seconds, 2) . ' second' . ($seconds != 1 ? 's' : '');

        return implode(', ', $formattedTime);
    }}

    private function formatDate($date)
    {{
        if (strpos($date, '+') !== false) {{
            $date = explode('+', $date)[0];
        }} elseif (strpos($date, '-') !== false) {{
            $date = explode('-', $date)[0];
        }}

        return date('Y-m-d H:i:s', strtotime($date));
    }}
}}
    """

    def _build_skip_invalid_lat_long(df):
        if 'lat' in df.columns and 'long' in df.columns:
            return """
            if (!is_numeric($record['lat']) || !is_numeric($record['long'])) {{
                print_r("Skipping record with invalid lat/long: " . $record['lat'] . ", " . $record['long'] . "\\n");
                $skipped++;
                continue;
            }}
            """
        return ""

    def _build_date_formatting(df):
        date_columns = [col for col in df.columns if 'date' in col.lower()]
        return "\n".join([f"$record['{col}'] = $this->formatDate($record['{col}']);" for col in date_columns])

    def _build_data_batch(df):
        data_batch_fields = []
        for col in df.columns:
            if col == 'lat' or col == 'long':
                data_batch_fields.append(f"'{col}' => $record['{col}'],")
            elif 'date' in col.lower():
                data_batch_fields.append(f"'{col}' => $record['{col}'],")
            else:
                data_batch_fields.append(f"'{col}' => $record['{col}'],")
        return "\n".join(data_batch_fields)

    seeder_content = seeder_content.format(
        model_name=model_name,
        dataset_name=dataset_name,
        columns=columns,
        build_skip_invalid_lat_long=_build_skip_invalid_lat_long(df),
        build_date_formatting=_build_date_formatting(df),
        build_data_batch=_build_data_batch(df)
    )


    controller_content = f"""<?php

namespace App\Http\Controllers;

use App\Models\{model_name};
use Illuminate\Http\Request;
use Inertia\Inertia;
use GuzzleHttp\Client;

class {controller_name} extends Controller
{{
    public function index(Request $request)
    {{
        $data = {model_name}::all();

        return Inertia::render('{vue_component_name}', [
            'data' => $data,
            'filters' => $request->all(),
        ]);
    }}

    public function getData(Request $request)
    {{
        $query = {model_name}::query();

        $filters = $request['filters'];

        foreach ($filters as $key => $value) {{
            if (!empty($value)) {{
                $query->where($key, $value);
            }}
        }}

        $query->limit(1500);
        $data = $query->get();

        return response()->json(['data' => $data, 'filters' => $filters]);
    }}

    public function naturalLanguageQuery(Request $request)
    {{
        $queryText = $request->input('query');
        $gptResponse = $this->queryGPT($queryText);

        $gptResponse = json_decode($gptResponse, true);

        if (isset($gptResponse['filters'])) {{
            return $this->getData(Request::create('/api/{dataset_name}', 'POST', $gptResponse));
        }}

        return response()->json(['error' => 'Could not parse query', 
                                 'query' => $queryText,
                                 'response' => $gptResponse], 400);
    }}

    private function queryGPT($queryText)
    {{
        $client = new Client();
        $apiKey = config('services.openai.api_key');

        $response = $client->post('https://api.openai.com/v1/chat/completions', [
            'headers' => [
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ],
            'json' => {{
                'model': 'gpt-4o-mini',
                'messages': [
                    {{'role': 'system', 'content': 'You are a helpful assistant.'}},
                    {{'role': 'user', 'content': 'The current datetime is ' . date('Y-m-d H:i:s')}},
                    {{'role': 'user', 'content': f"Convert this query into {dataset_name} filters: {{queryText}}"}},
                ],
                'function_call': 'auto',
            }}
        ]);

        $responseBody = json_decode($response->getBody()->getContents(), true);
        filters = $responseBody['choices'][0]['message']['function_call']['arguments'];

        return $filters;
    }}
}}
"""

    vue_map_component = f"""<template>
    <div class="">
        <h3 class="text-2xl font-semibold mb-4">Interactive {model_name} Map</h3>
        <div id="map" class="h-[70vh] mb-6"></div>
        <h4 class="text-lg font-semibold mb-4">Natural Language Query</h4>
        <p class="mb-4">Enter a natural language query to filter the data:</p>
        <input v-model="naturalLanguageQuery" type="text" placeholder="Example: All the fraud that happened last week" class="p-2 border rounded-md w-full mb-4">
        <button @click="submitQuery" class="p-2 bg-blue-500 text-white rounded-md mb-4">Submit to GPT-4o-mini</button>
        <pre v-if="filters" class="p-2 border rounded-md w-full mb-4 overflow-scroll" rows="5" readonly>{{ JSON.stringify(filters, null, 2) }}</pre>
        <h4 class="text-lg font-semibold mb-4">Or Use Manual Filters</h4>
        <p class="mb-4">Use the manual filters below to filter the data:</p>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="flex flex-col">
                <label for="category" class="font-medium mb-1">Choose a Category:</label>
                <select v-model="filters.category" class="p-2 border rounded-md">
                    <option value="">All</option>
                    <option v-for="category in categories" :key="category" :value="category">{{ category }}</option>
                </select>
            </div>
            <div class="flex flex-col">
                <label for="year" class="font-medium mb-1">Choose a Year:</label>
                <select v-model="filters.year" class="p-2 border rounded-md">
                    <option value="">All</option>
                    <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
                </select>
            </div>
        </div>
        <button @click="updateMarkers" class="mt-4 p-2 bg-blue-500 text-white rounded-md">Submit Filters</button>
    </div>
</template>

<script setup>
import {{ ref, onMounted, watch }} from 'vue';
import {{ usePage }} from '@inertiajs/vue3';
import 'leaflet/dist/leaflet.css';
import * as L from 'leaflet';
import 'leaflet.markercluster/dist/MarkerCluster.css';
import 'leaflet.markercluster/dist/MarkerCluster.Default.css';
import 'leaflet.markercluster';

const {{ props }} = usePage();

const initialMap = ref(null);
const markers = ref(null);
const data = ref(props.data || []);
const filters = ref({{
    category: '',
    year: '',
}});
const naturalLanguageQuery = ref('');

axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

const categories = ref([...new Set(data.value.map(item => item.category))].sort());
const years = [2024, 2023, 2022, 2021, 2020, 2019, 2018, 2017];

onMounted(() => {{
    initialMap.value = L.map('map').setView([42.3601, -71.0589], 13);
    L.tileLayer('https://tile.openstreetmap.org/{{z}}/{{x}}/{{y}}.png', {{
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
    }}).addTo(initialMap.value);

    updateMarkers();
}});

const updateMarkers = async () => {{
    try {{
        const response = await axios.post('/api/{dataset_name}', {{ filters: filters.value }});
        const responseData = response.data;

        initialMap.value.eachLayer((layer) => {{
            if (layer instanceof L.Marker) {{
                initialMap.value.removeLayer(layer);
            }}
        }});

        responseData.data.forEach((item) => {{
            if (item.lat && item.long) {{
                const popupContent = `
                    <div>
                        <strong>ID:</strong> ${{item.id}}<br>
                        <strong>Category:</strong> ${{item.category}}<br>
                        <strong>Year:</strong> ${{item.year}}
                    </div>
                `;
                const marker = L.marker([item.lat, item.long]);
                marker.bindPopup(popupContent);
                marker.addTo(initialMap.value);
            }}
        }});
    }} catch (error) {{
        console.error("Failed to fetch data", error);
    }}
}};

const submitQuery = async () => {{
    try {{
        const response = await axios.post('/api/{dataset_name}/natural-language-query', {{ query: naturalLanguageQuery.value }});
        const responseData = response.data;
        data.value = responseData.data;

        Object.keys(filters.value).forEach(key => {{
            if (responseData.filters.hasOwnProperty(key)) {{
                filters.value[key] = responseData.filters[key];
            }} else {{
                filters.value[key] = '';
            }}
        }});

        updateMarkers();
    }} catch (error) {{
        console.error("Failed to process natural language query", error);
    }}
}};

watch(filters, updateMarkers, {{ deep: true }});
</script>

<style scoped>
#map {{
    height: 70vh;
}}
</style>
"""

    webphp_update_content = f"""
Route::post('/api/{dataset_name}', [{controller_name}::class, 'getData'])->name('{dataset_name}.api');
Route::get('/{dataset_name}-map', [{controller_name}::class, 'index'])->name('{dataset_name}-map');
Route::post('/api/{dataset_name}/natural-language-query', [{controller_name}::class, 'naturalLanguageQuery'])->name('{dataset_name}-map.natural-language-query');
"""

    os.makedirs(os.path.join(laravel_project_dir, "database/migrations"), exist_ok=True)
    os.makedirs(os.path.join(laravel_project_dir, "app/Models"), exist_ok=True)
    os.makedirs(os.path.join(laravel_project_dir, "database/seeders"), exist_ok=True)
    os.makedirs(os.path.join(laravel_project_dir, "app/Http/Controllers"), exist_ok=True)
    os.makedirs(os.path.join(laravel_project_dir, "resources/js/Pages"), exist_ok=True)
    
    with open(os.path.join(laravel_project_dir, "database/migrations", f"create_{dataset_name}_table.php"), "w") as file:
        file.write(migration_content)

    with open(os.path.join(laravel_project_dir, "app/Models", f"{model_name}.php"), "w") as file:
        file.write(model_content)

    with open(os.path.join(laravel_project_dir, "database/seeders", f"{model_name}Seeder.php"), "w") as file:
        file.write(seeder_content)

    with open(os.path.join(laravel_project_dir, "app/Http/Controllers", f"{controller_name}.php"), "w") as file:
        file.write(controller_content)

    with open(os.path.join(laravel_project_dir, "resources/js/Pages", f"{vue_component_name}.vue"), "w") as file:
        file.write(vue_map_component)

    with open(os.path.join(laravel_project_dir, "webphp_update.txt"), "w") as file:
        file.write(webphp_update_content)

    print("Laravel files generated successfully.")

if __name__ == "__main__":
    data_dir = "./data"
    laravel_project_dir = "."
    base_url = "https://data.boston.gov/dataset/approved-building-permits"
    dataset_name = "BuildingPermits"

    df_all = load_data_from_urls(data_dir, base_url)
    generate_laravel_files(df_all, laravel_project_dir, dataset_name)
