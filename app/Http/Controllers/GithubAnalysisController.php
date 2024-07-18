<?php

namespace App\Http\Controllers;


use GuzzleHttp\Client;
use Illuminate\Http\Request;

class GithubAnalysisController extends Controller
{
    public function handle(Request $request)
    {
        $client = new Client();
        try {
            $response = $client->request('GET', 'https://us-central1-github-analysis-423017.cloudfunctions.net/githubChangeAnalysis', [
                'query' => $request->all()  // Forward all query parameters
            ]);
            return response()->json(json_decode($response->getBody()->getContents()));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch github analysis'], 502);
        }
    }
}
