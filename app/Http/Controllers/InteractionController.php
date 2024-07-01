<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use GuzzleHttp\Client;
use App\Models\Interaction; // Assuming you have this model
use Yethee\Tiktoken\EncoderProvider;

class InteractionController extends Controller
{

    protected const PRICING = [
        'gpt-4o' => ['input' => 5, 'output' => 15],
        'gpt-4-turbo' => ['input' => 10, 'output' => 30],
        'gpt-3.5-turbo' => ['input' => 0.50, 'output' => 1.50],
        'claude-3-5-sonnet-20240620' => ['input' => 3.00, 'output' => 15.00],
        'claude-3-opus-20240229' => ['input' => 15.00, 'output' => 75.00],
        'claude-3-sonnet-20240229' => ['input' => 3.00, 'output' => 15.00],
        'claude-3-haiku-20240307' => ['input' => 0.25, 'output' => 1.25],
    ];
    protected const MODELS = ['gpt-4o', 'gpt-4-turbo', 'gpt-3.5-turbo', 'claude-3-5-sonnet-20240620', 'claude-3-opus-20240229', 'claude-3-sonnet-20240229', 'claude-3-haiku-20240307'];
    protected const OPENAIMODELS = ['gpt-4o', 'gpt-4-turbo', 'gpt-3.5-turbo'];
    protected const ANTHROPICMODELS = ['claude-3-5-sonnet-20240620', 'claude-3-opus-20240229', 'claude-3-sonnet-20240229', 'claude-3-haiku-20240307'];


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

        $request->validate([
            'fields' => 'required|array',
            'maxTokens' => 'required|integer',
            'model' => 'required|string|in:' . implode(',', self::MODELS),
            'temperature' => 'required|numeric',
        ]);

        
        $fields = $request->input('fields');
        $prompt = $request->input('prompt');
        $maxTokens = $request->input('maxTokens');
        $model = $request->input('model');
        $temperature = $request->input('temperature');


        $prompt = $this->combineFields($fields);

        try {
            //use the OPENAIMODELS constant to check if the model is an OpenAI model
            if (in_array($model, self::OPENAIMODELS)) {
                $interaction = $this->queryOpenAI($fields, $prompt, $maxTokens, $model, $temperature);
            } else if (in_array($model, self::ANTHROPICMODELS)) {
                // Call the function to query the Anthropic API
                $interaction = $this->queryAnthropic($fields, $prompt, $maxTokens, $model);
            } else {
                throw new \Exception("Model '{$model}' is not supported.");
            }

            

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

    //create a function logInteraction that acts identical to the store function but doesn't query an API, just saves the interaction to the database
    public function logInteraction(Request $request)
    {
        $request->validate([
            'fields' => 'required|array',
            'maxTokens' => 'required|integer',
            'model' => 'required|string|in:' . implode(',', self::MODELS),
            'temperature' => 'required|numeric',
        ]);

        $fields = $request->input('fields');
        $prompt = $request->input('prompt');
        $maxTokens = $request->input('maxTokens');
        $model = $request->input('model');
        $temperature = $request->input('temperature');

        $prompt = $this->combineFields($fields);

        $interaction = auth()->user()->interactions()->create([
            'prompt' => $prompt,
            'fields' => json_encode($fields),
            'model_name' => $model,
            'completion' => 'No completion available.',
            'prompt_tokens' => 0,
            'completion_tokens' => 0,
            'prompt_token_price' => 0,
            'completion_token_price' => 0,
            'total_cost' => 0,
        ]);

        return Inertia::render('Interactions/Index', [
            'interactions' => auth()->user()->interactions()->latest()->take(10)->get(),
            'currentInteraction' => $interaction,
            'models' => self::MODELS,
            'templates' => auth()->user()->templates()->latest()->get(),
        ]);
    }

    public function estimateCost(Request $request)
    {
        $provider = new EncoderProvider();
        /*
        request()->validate([
            'prompt' => 'required|string',
            'maxTokens' => 'required|integer',
            'model' => 'required|string|in:' . implode(',', self::MODELS),
        ]); */
        $model = $request->input('model');

        try {
            $encoder = $provider->getForModel($model);
        } catch (\Exception $e) {
            $encoder = $provider->getForModel('gpt-3.5-turbo');
        }
        $tokens = $encoder->encode($request->input('prompt'));
        $inputTokenCount = count($tokens);

        $promptCost = $this->calculateTokenPrice($inputTokenCount, $model, 'input');
        $completionCost = $this->calculateTokenPrice($request->input('maxTokens'), $model, 'output');
        $totalCost = $promptCost + $completionCost;

        return response()->json([
            'prompt_cost' => $promptCost,
            'completion_cost' => $completionCost,
            'total_cost' => $totalCost,
        ]);

    }

    protected function queryOpenAI($fields, $prompt, $maxTokens, $model, $temperature)
    {
        $client = new Client();
        $apiKey = config('services.openai.api_key');
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
                'temperature' => $temperature,
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
            'fields' => json_encode($fields),
            'model_name' => $model,
            'completion' => $completion,
            'prompt_tokens' => $completionData['usage']['prompt_tokens'],
            'completion_tokens' => $completionData['usage']['completion_tokens'],
            'prompt_token_price' => $this->calculateTokenPrice($completionData['usage']['prompt_tokens'], $model, 'input'),
            'completion_token_price' => $this->calculateTokenPrice($completionData['usage']['completion_tokens'], $model, 'output'),
            'total_cost' => $this->calculateCost($completionData['usage']['prompt_tokens'], $completionData['usage']['completion_tokens'], $model),
        ]);

        return $interaction;
    }

    protected function queryAnthropic($fields, $prompt, $maxTokens, $model)
    {
        $client = new Client();
        $apiKey = config('services.anthropic.api_key');
        $response = $client->request('POST', 'https://api.anthropic.com/v1/messages', [
            'headers' => [
                'x-api-key' => $apiKey,
                'content-type' => 'application/json',
                'anthropic-version' => '2023-06-01',
            ],
            'json' => [
                'messages' => [
                    ['role' => 'user', 'content' => $prompt],
                ],
                'max_tokens' => $maxTokens,
                'model' => $model,
            ]
        ]);

        $completionData = json_decode($response->getBody()->getContents(), true);

        /* Claude response format:

            {
            "content": [
                {
                "text": "Hi! My name is Claude.",
                "type": "text"
                }
            ],
            "id": "msg_013Zva2CMHLNnXjNJJKqJ2EF",
            "model": "claude-3-5-sonnet-20240620",
            "role": "assistant",
            "stop_reason": "end_turn",
            "stop_sequence": null,
            "type": "message",
            "usage": {
                "input_tokens": 10,
                "output_tokens": 25
            }
            }
        */

        $completion = $completionData['content'][0]['text'] ?? '';

        // Save to database
        $interaction = auth()->user()->interactions()->create([
            'prompt' => $prompt,
            'fields' => json_encode($fields),
            'model_name' => $model,
            'completion' => $completion,
            'prompt_tokens' => $completionData['usage']['input_tokens'],
            'completion_tokens' => $completionData['usage']['output_tokens'],
            'prompt_token_price' => $this->calculateTokenPrice($completionData['usage']['input_tokens'], $model, 'input'),
            'completion_token_price' => $this->calculateTokenPrice($completionData['usage']['output_tokens'], $model, 'output'),
            'total_cost' => $this->calculateCost($completionData['usage']['input_tokens'], $completionData['usage']['output_tokens'], $model),
        ]);

        return $interaction;

    }

    protected function combineFields($fields)
    {
        $prompt = '';
        foreach ($fields as $field) {
            $prompt .= $field['name'] . ' : ' . $field['value'] . '\n';
        }
        return $prompt;
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
