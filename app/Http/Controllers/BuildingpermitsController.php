<?php

namespace App\Http\Controllers;

use App\Models\Buildingpermits;
use Illuminate\Http\Request;
use Inertia\Inertia;
use GuzzleHttp\Client;

class BuildingpermitsController extends Controller
{
    public function index(Request $request)
    {
        $data = Buildingpermits::all();

        return Inertia::render('BuildingpermitsMap', [
            'data' => $data,
            'filters' => $request->all(),
        ]);
    }

    public function getData(Request $request)
    {
        $query = Buildingpermits::query();

        $filters = $request['filters'];

        foreach ($filters as $key => $value) {
            if (!empty($value)) {
                $query->where($key, $value);
            }
        }

        $query->limit(1500);
        $data = $query->get();

        return response()->json(['data' => $data, 'filters' => $filters]);
    }

    public function naturalLanguageQuery(Request $request)
    {
        $queryText = $request->input('query');
        $gptResponse = $this->queryGPT($queryText);

        $gptResponse = json_decode($gptResponse, true);

        if (isset($gptResponse['filters'])) {
            return $this->getData(Request::create('/api/BuildingPermits', 'POST', $gptResponse));
        }

        return response()->json(['error' => 'Could not parse query', 
                                 'query' => $queryText,
                                 'response' => $gptResponse], 400);
    }

    private function queryGPT($queryText)
    {
        $client = new Client();
        $apiKey = config('services.openai.api_key');

        $response = $client->post('https://api.openai.com/v1/chat/completions', [
            'headers' => [
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ],
            'json' => {
                'model': 'gpt-4o-mini',
                'messages': [
                    {'role': 'system', 'content': 'You are a helpful assistant.'},
                    {'role': 'user', 'content': 'The current datetime is ' . date('Y-m-d H:i:s')},
                    {'role': 'user', 'content': f"Convert this query into BuildingPermits filters: {queryText}"},
                ],
                'function_call': 'auto',
            }
        ]);

        $responseBody = json_decode($response->getBody()->getContents(), true);
        filters = $responseBody['choices'][0]['message']['function_call']['arguments'];

        return $filters;
    }
}
