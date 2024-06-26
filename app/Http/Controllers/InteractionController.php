<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use GuzzleHttp\Client;

class InteractionController extends Controller
{
    public function index(Request $request)
    {
        // get last ten interactions for this user if logged in
        $interactions = auth()->user()->interactions()->latest()->take(10)->get();
        return Inertia::render('Interactions/Index', [
            'interactions' => $interactions
        ]);
    }

    public function create(Request $request)
    {
        return Inertia::render('Interaction/Create');
    }

    public function generateCompletion(Request $request)
    {
        $client = new Client();
        $apiKey = config('services.openai.api_key');

        try {
            $response = $client->request('POST', 'https://api.openai.com/v1/engines/davinci/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'prompt' => $request->input('prompt'),
                    'max_tokens' => $request->input('max_tokens', 100),
                    'temperature' => 0.5,
                    'top_p' => 1,
                    'frequency_penalty' => 0,
                    'presence_penalty' => 0,
                    'model' => 'gpt-3.5-turbo',
                ]
            ]);
            return response()->json(json_decode($response->getBody()->getContents(), true));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch AI response'], 502);
        }
    }
}
