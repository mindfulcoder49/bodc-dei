<?php

namespace App\Http\Controllers;


use GuzzleHttp\Client;
use Illuminate\Http\Request;

class CrimeReportsController extends Controller
{
    public function handle(Request $request)
    {
        $client = new Client();
        try {
            $response = $client->request('GET', 'https://us-central1-crime-reports-422919.cloudfunctions.net/crimeReport', [
                'query' => $request->all()  // Forward all query parameters
            ]);
            return response()->json(json_decode($response->getBody()->getContents()));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch crime reports'], 502);
        }
    }
}
