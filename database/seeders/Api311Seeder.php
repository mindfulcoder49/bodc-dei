<?php

use Illuminate\Database\Seeder;
use App\Models\Boston311Data;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Carbon\Carbon;
use Carbon\CarbonInterval;

class Api311Seeder extends Seeder
{
    public function run()
    {
        $client = new Client();
        $startDate = $this->getLastDateInDB() ?? Carbon::create(2023, 1, 1, 0, 0, 0, 'UTC');
        $endDate = Carbon::create(2023, 9, 20, 0, 0, 0, 'UTC');
        $dayDelta = CarbonInterval::days(1);
        $minuteDelta = CarbonInterval::minutes(1);

        while ($startDate->lessThanOrEqualTo($endDate)) {
            $formattedStartDate = $startDate->toIso8601String();
            $formattedEndDate = $startDate->copy()->add($dayDelta)->toIso8601String();

            try {
                $response = $client->get("https://311.boston.gov/open311/v2/requests.json?start_date=$formattedStartDate&end_date=$formattedEndDate");

                if ($response->getStatusCode() === 200) {
                    $data = json_decode($response->getBody(), true);
                    echo "Number of requests: " . count($data) . "\n";

                    foreach ($data as $record) {
                        Boston311Data::updateOrInsert(
                            ['service_request_id' => $record['service_request_id']],
                            $record
                        );
                    }

                    if (count($data) > 0) {
                        $lastDate = Carbon::parse($data[array_key_last($data)]['requested_datetime']);
                        if ($lastDate->greaterThan($startDate)) {
                            $startDate = $lastDate;
                        } else {
                            $startDate->add($minuteDelta);
                        }
                    } else {
                        $startDate->add($dayDelta);
                    }
                }
            } catch (RequestException $e) {
                echo "Failed to fetch data for $formattedStartDate to $formattedEndDate\n";
            } catch (JsonException $e) {
                echo "Failed to decode JSON for $formattedStartDate to $formattedEndDate\n";
            }

            sleep(6);  // Rate limiting
        }
    }

    private function getLastDateInDB()
    {
        $record = Boston311Data::latest('requested_datetime')->first();
        return $record ? Carbon::parse($record->requested_datetime) : null;
    }
}
