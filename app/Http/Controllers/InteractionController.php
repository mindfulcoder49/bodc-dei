<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use GuzzleHttp\Client;
use App\Models\Interaction; // Assuming you have this model

class InteractionController extends Controller
{

    protected const PRICING = [
        'gpt-4o' => ['input' => 5, 'output' => 15],
        'gpt-4-turbo' => ['input' => 10, 'output' => 30],
        'gpt-3.5-turbo' => ['input' => 0.50, 'output' => 1.50],
    ];
    protected const MODELS = ['gpt-4o', 'gpt-4-turbo', 'gpt-3.5-turbo'];

    public function index(Request $request)
    {
        // Get last ten interactions for this user if logged in
        $interactions = auth()->user()->interactions()->latest()->take(10)->get();
        $models = self::MODELS;
        $templates = auth()->user()->templates()->latest()->get();
        return Inertia::render('Interactions/Index', [
            'interactions' => $interactions,
            'currentInteraction' => null,
            'models' => $models,
            'templates' => $templates,
        ]);
    }

    public function store(Request $request)
    {
        $client = new Client();
        $apiKey = config('services.openai.api_key');
        //$fields = $request->input('fields');
        $prompt = $request->input('prompt');
        $maxTokens = 100;
        $model = 'gpt-3.5-turbo';

        try {
            $response = $client->request('POST', 'https://api.openai.com/v1/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'messages' => [
                        ['role' => 'system', 'content' => 'You are a helpful assistant.'],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'max_tokens' => $maxTokens,
                    'temperature' => 0.5,
                    'top_p' => 1,
                    'frequency_penalty' => 0,
                    'presence_penalty' => 0,
                    'model' => $model,
                ]
            ]);

            $completionData = json_decode($response->getBody()->getContents(), true);
            $completion = $completionData['choices'][0]['message']['content'] ?? '';

            // Save to database
            $interaction = auth()->user()->interactions()->create([
                'prompt' => $prompt,
                'model_name' => $model,
                'completion' => $completion,
                'prompt_tokens' => $completionData['usage']['prompt_tokens'],
                'completion_tokens' => $completionData['usage']['completion_tokens'],
                'prompt_token_price' => $this->calculateTokenPrice($completionData['usage']['prompt_tokens'], $model, 'input'),
                'completion_token_price' => $this->calculateTokenPrice($completionData['usage']['completion_tokens'], $model, 'output'),
                'total_cost' => $this->calculateCost($completionData['usage']['prompt_tokens'], $completionData['usage']['completion_tokens'], $model),
            ]);

            // Refresh and return
            return Inertia::render('Interactions/Index', [
                'interactions' => auth()->user()->interactions()->latest()->take(10)->get(),
                'currentInteraction' => $interaction,
                'models' => self::MODELS,
                'templates' => auth()->user()->templates()->latest()->get(),
            ]);

        } catch (\Exception $e) {
            return back()->withErrors(['message' => $e->getMessage()]);
        }
    }

    protected function combineFields($fields)
    {
        return collect($fields)->reduce(function ($carry, $field) {
            return $carry . " " . $field['content'];
        }, '');
    }

    //Also create two functions, one for prompt_token_price and one for completion_token_price
    //These functions should be similar to calculateCost, but should only calculate the cost for the prompt tokens and the completion tokens, respectively.
    //The prompt_token_price should be calculated using the prompt_tokens and the input pricing for the model.
    //The completion_token_price should be calculated using the completion_tokens and the output pricing for the model.
    //The functions should return the calculated cost.
    /*
    def calculate_tokens_and_cost(model, user_input, encoding_name, max_tokens):
    num_tokens = num_tokens_from_string(user_input, "cl100k_base")

    # Model pricing structure
    pricing = {
        'gpt-4o': {'input': 5, 'output': 15},
        'gpt-4-turbo': {'input': 10, 'output': 30},
        'gpt-3.5-turbo': {'input': 0.50, 'output': 1.50}
    }

    # Calculate input and output token costs based on the model's pricing
    input_cost = (num_tokens / 1_000_000) * pricing[model]['input']
    output_cost = (max_tokens / 1_000_000) * pricing[model]['output']

    # Total cost calculation
    total_cost = input_cost + output_cost

    return num_tokens, total_cost
    */
    protected function calculateTokenPrice($promptTokens, $model, $direction)
    {
        $pricing = self::PRICING;

        // Check if the model exists in the pricing array to avoid errors
        if (!isset($pricing[$model])) {
            throw new \Exception("Pricing for the specified model '{$model}' is not defined.");
        }
        // Check if $direction is input our output
        if (!in_array($direction, ['input', 'output'])) {
            throw new \Exception("Invalid direction '{$direction}'. Must be 'input' or 'output'.");
        }

        // Calculate the cost in dollars for the given number of prompt tokens
        $cost = ($promptTokens / 1_000_000) * $pricing[$model][$direction];

        return $cost;
    }

    protected function calculateCost($promptTokens, $completionTokens, $model)
    {
        $inputCost = $this->calculateTokenPrice($promptTokens, $model, 'input');
        $outputCost = $this->calculateTokenPrice($completionTokens, $model, 'output');

        return $inputCost + $outputCost;
    }
}
