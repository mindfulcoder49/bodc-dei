<?php

namespace App\Http\Controllers;

use App\Models\CrimeData;
use Illuminate\Http\Request;
use Inertia\Inertia;
use GuzzleHttp\Client;

class CrimeMapController extends Controller
{
    public function index(Request $request)
    {
        $crimeData = CrimeData::limit(1500)->get();

        return Inertia::render('CrimeMap', [
            'crimeData' => $crimeData,
            'filters' => $request->all(),
        ]);
    }

    public function getCrimeData(Request $request)
{
    $query = CrimeData::query();
    $filters = $request['filters'];

    // Offense Codes filter
    if (isset($filters['offense_codes']) && !empty($filters['offense_codes'])) {
        if (is_string($filters['offense_codes'])) {
            $offenseCodes = explode(',', $filters['offense_codes']);
        } else {
            $offenseCodes = (array) $filters['offense_codes'];
        }
        $query->whereIn('offense_code', $offenseCodes);
    }

    // Offense Category filter
    if (isset($filters['offense_category']) && !empty($filters['offense_category']) && $filters['offense_category'] !== [null]) {
        $query->whereIn('offense_category', $filters['offense_category']);
    }

    // District filter
    if (isset($filters['district']) && !empty($filters['district']) && $filters['district'] !== [null]) {
        $query->whereIn('district', $filters['district']);
    }

    // Date Range filter
    if (isset($filters['start_date']) && isset($filters['end_date']) && !empty($filters['start_date']) && !empty($filters['end_date'])) {
        $query->whereBetween('occurred_on_date', [$filters['start_date'], $filters['end_date']]);
    }

    // Year filter
    if (isset($filters['year']) && !empty($filters['year']) && $filters['year'] !== [null]) {
        $query->whereIn('year', $filters['year']);
    }

    // Shooting filter
    if (isset($filters['shooting']) && $filters['shooting'] !== null) {
        $query->where('shooting', $filters['shooting'] ? 1 : 0);
    }

    //record limit filter
    if (isset($filters['limit']) && !empty($filters['limit']) && $filters['limit'] > 0 && $filters['limit'] <= 10000) {
        $query->limit($filters['limit']);
    } else {
        $query->limit(1500);
    }

    // Fetch the filtered data
    $crimeData = $query->get();

    return response()->json(['crimeData' => $crimeData, 'filters' => $filters, 'query' => $query->toSql()]);
}


    public function naturalLanguageQuery(Request $request)
    {
        $queryText = $request->input('query');
        $gptResponse = $this->queryGPT($queryText);

        $gptResponse = json_decode($gptResponse, true);

        if (isset($gptResponse['filters'])) {
            return $this->getCrimeData(Request::create('/api/crime-data', 'POST', $gptResponse));
        }

        return response()->json(['error' => 'Could not parse query', 'query' => $queryText, 'response' => $gptResponse], 400);
    }

    private function queryGPT($queryText)
    {
        $client = new Client();
        $apiKey = config('services.openai.api_key');

        $description = 'offense_codes: Array of integers (examples: [3115, 3301, 423]).
        offense_category: Array of strings describing the offense category (examples: "INVESTIGATE PERSON", "ASSAULT - AGGRAVATED").
        district: Array of strings representing district codes (examples: "B3", "A1", "D4").
        start_date: String in "yyyy-MM-dd" format (example: "2023-01-27").
        end_date: String in "yyyy-MM-dd" format (example: "2023-02-15").
        year: Array of integers representing years (examples: [2023, 2022]).
        shooting: Boolean (true or false).';

        $response = $client->post('https://api.openai.com/v1/chat/completions', [
            'headers' => [
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'model' => 'gpt-4o-mini',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a helpful assistant.'],
                    ['role' => 'user', 'content' => 'The current datetime is' . date('Y-m-d H:i:s')],
                    ['role' => 'user', 'content' => "Convert this query into crime data filters: {$queryText}"],
                    ['role' => 'user', 'content' => "Here are the offense codes and names: \n" . $this->getCrimeDataContext()],
                ],
                'functions' => [
                    [
                        'name' => 'filter_crime_data',
                        'description' => 'Generate filters for crime data query: ' . $description,
                        'parameters' => [
                            'type' => 'object',
                            'properties' => [
                                'filters' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'offense_codes' => ['type' => 'array', 'items' => ['type' => 'integer']],
                                        'offense_category' => ['type' => 'array', 'items' => ['type' => 'string']],
                                        'district' => ['type' => 'array', 'items' => ['type' => 'string']],
                                        'start_date' => ['type' => 'string', 'format' => 'date-time'],
                                        'end_date' => ['type' => 'string', 'format' => 'date-time'],
                                        'year' => ['type' => 'array', 'items' => ['type' => 'integer']],
                                        'shooting' => ['type' => 'boolean'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                'function_call' => 'auto',
            ]
        ]);

        $responseBody = json_decode($response->getBody()->getContents(), true);
        $filters = $responseBody['choices'][0]['message']['function_call']['arguments'];

        return $filters;
    }

    private function getCrimeDataContext()
    {
        $crimeContext = CrimeData::CONTEXT_CRIME_DATA;
        $crimeContext = preg_replace('/\s+/', ' ', $crimeContext);
        return $crimeContext;
    }
}
