<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AiAssistantController extends Controller
{
    public function handleRequest(Request $request)
    {
        // Validation
        try {
            $request->validate([
                'message' => 'required|string|max:255',
                'history' => 'array',
                'context' => 'string',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json($e->errors(), 400);
        }

        $userMessage = $request->input('message');
        $history = $request->input('history', []);
        $context = $request->input('context', []);


        // Add the context to the beginning of the conversation history
        $history = array_merge([['role' => 'system', 'content' => $context]], $history);

        // Add the user's message to the conversation history
        $history[] = ['role' => 'user', 'content' => $userMessage];

        return new StreamedResponse(function() use ($history) {
            $this->streamAiResponse($history);
        });
    }



    private function streamAiResponse($history)
    {
        $maxTokens = 4096;
        $temperature = 0.5;
        $model = 'gpt-4o-mini';
        $client = new Client();
        $apiKey = config('services.openai.api_key');

        //prepend the context to the history
        $history = array_merge([['role' => 'user', 'content' => $this->getContext()]], $history);

        $response = $client->request('POST', 'https://api.openai.com/v1/chat/completions', [
            'headers' => [
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'messages' => $history, // Send the entire conversation history
                'max_tokens' => $maxTokens,
                'temperature' => $temperature,
                'model' => $model,
                'stream' => true,
            ],
            'stream' => true,
        ]);

        $body = $response->getBody();
        $buffer = '';

        while (!$body->eof()) {
            $buffer .= $body->read(1024);

            while (($pos = strpos($buffer, "\n")) !== false) {
                $chunk = substr($buffer, 0, $pos);
                $buffer = substr($buffer, $pos + 1);

                if (strpos($chunk, 'data: ') === 0) {
                    $jsonData = substr($chunk, 6);

                    if ($jsonData === '[DONE]') {
                        break 2;
                    }

                    $decodedChunk = json_decode($jsonData, true);

                    if (isset($decodedChunk['choices'][0]['delta']['content'])) {
                        echo $decodedChunk['choices'][0]['delta']['content'];
                        ob_flush();
                        flush();
                    }
                }
            }
        }
    }

    private function getContext() {
        return 
        <<<EOT
        You are a chatbot assistant embedded in an application showing people data about city operations happening near them.
        EOT;

    }
}
