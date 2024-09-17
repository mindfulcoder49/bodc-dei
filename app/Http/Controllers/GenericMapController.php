<?php

namespace App\Http\Controllers;

use App\Models\CrimeData;
use App\Models\ThreeOneOneCase;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\BuildingPermit;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class GenericMapController extends Controller
{
    public function index(Request $request)
    {
        Log::info('Index method accessed.', ['request' => $request->all()]);
        return redirect()->route('map.radial'); // Redirect to the radial map
    }

    public function getRadialMap(Request $request)
    {
        Log::info('getRadialMap accessed.', ['request' => $request->all()]);

        $defaultLatitude = 42.3601;
        $defaultLongitude = -71.0589;

        $centralLocation = $request->input('centralLocation', [
            'latitude' => $defaultLatitude,
            'longitude' => $defaultLongitude
        ]);

        Log::info('Central location determined.', ['centralLocation' => $centralLocation]);

        $radius = 0.25;

        $boundingBox = $this->getBoundingBox($centralLocation['latitude'], $centralLocation['longitude'], $radius);

        Log::info('Bounding box calculated.', ['boundingBox' => $boundingBox]);

        // Get the 'days' parameter from the request, default to 7 days if not provided
        $days = $request->input('days', 7);

        $crimeData = collect($this->getCrimeDataForBoundingBox($boundingBox, $days));
        Log::info('Crime data fetched.', ['crimeDataCount' => $crimeData->count()]);

        $caseData = collect($this->getThreeOneOneCaseDataForBoundingBox($boundingBox, $days));
        Log::info('311 case data fetched.', ['caseDataCount' => $caseData->count()]);

        $buildingPermits = collect($this->getBuildingPermitsForBoundingBox($boundingBox, $days));
        Log::info('Building permits data fetched.', ['buildingPermitsCount' => $buildingPermits->count()]);

        $dataPoints = $crimeData->merge($caseData)->merge($buildingPermits);
        Log::info('Data points merged.', ['totalDataPointsCount' => $dataPoints->count()]);

        //add central location to data points with type center, todays date, and info about how it's the center
        $dataPoints->push([
            'latitude' => $centralLocation['latitude'],
            'longitude' => $centralLocation['longitude'],
            'type' => 'Center',
            'date' => Carbon::now()->toDateString(),
            'info' => [
                'description' => 'Center of the map',
                'radius' => $radius,
            ],
        ]);

        return Inertia::render('RadialMap', [
            'dataPoints' => $dataPoints,
            'centralLocation' => $centralLocation,
        ]);
    }

    // Function to calculate a bounding box (latitude and longitude range)
    private function getBoundingBox($lat, $lon, $radius)
    {
        Log::info('Calculating bounding box.', ['latitude' => $lat, 'longitude' => $lon, 'radius' => $radius]);

        $earthRadius = 3959; // Radius of Earth in miles

        $latDelta = rad2deg($radius / $earthRadius);
        $lonDelta = rad2deg($radius / ($earthRadius * cos(deg2rad($lat))));

        $boundingBox = [
            'minLat' => $lat - $latDelta,
            'maxLat' => $lat + $latDelta,
            'minLon' => $lon - $lonDelta,
            'maxLon' => $lon + $lonDelta,
        ];

        Log::info('Bounding box calculated.', ['boundingBox' => $boundingBox]);

        return $boundingBox;
    }

    public function getCrimeDataForBoundingBox($boundingBox, $days)
    {
        Log::info('Fetching crime data within bounding box.', ['boundingBox' => $boundingBox, 'days' => $days]);

        $startDate = Carbon::now()->subDays($days)->toDateString();

        $query = CrimeData::whereBetween('lat', [$boundingBox['minLat'], $boundingBox['maxLat']])
                          ->whereBetween('long', [$boundingBox['minLon'], $boundingBox['maxLon']])
                          ->where('occurred_on_date', '>=', $startDate);

        $crimeData = $query->get();

        Log::info('Crime data query executed.', ['rowsFetched' => $crimeData->count()]);

        // Transform data for the map
        return $crimeData->map(function ($crime) {
            return [
                'latitude' => $crime->lat,
                'longitude' => $crime->long,
                'date' => $crime->occurred_on_date,
                'type' => 'Crime',
                'info' => [
                    'category' => $crime->offense_category,
                    'description' => $crime->offense_description,
                    'district' => $crime->district,
                    'reporting_area' => $crime->reporting_area,
                    'shooting' => $crime->shooting ? 'Yes' : 'No',
                    'street' => $crime->street,
                ],
            ];
        });
    }

    public function getThreeOneOneCaseDataForBoundingBox($boundingBox, $days)
    {
        Log::info('Fetching 311 case data within bounding box.', ['boundingBox' => $boundingBox, 'days' => $days]);

        $startDate = Carbon::now()->subDays($days)->toDateString();

        $query = ThreeOneOneCase::whereBetween('latitude', [$boundingBox['minLat'], $boundingBox['maxLat']])
                                ->whereBetween('longitude', [$boundingBox['minLon'], $boundingBox['maxLon']])
                                ->where('open_dt', '>=', $startDate);

        $cases = $query->get();

        Log::info('311 case data query executed.', ['rowsFetched' => $cases->count()]);

        // Transform data for the map
        return $cases->map(function ($case) {
            return [
                'latitude' => $case->latitude,
                'longitude' => $case->longitude,
                'date' => $case->open_dt,
                'type' => '311 Case',
                'info' => [
                    'description' => $case->case_title,
                    'status' => $case->case_status,
                    'closure_reason' => $case->closure_reason,
                    'location' => $case->location,
                    'department' => $case->department,
                    'subject' => $case->subject,
                ],
            ];
        });
    }

    public function getBuildingPermitsForBoundingBox($boundingBox, $days)
    {
        Log::info('Fetching building permits within bounding box.', ['boundingBox' => $boundingBox, 'days' => $days]);

        $startDate = Carbon::now()->subDays($days)->toDateString();

        $buildingPermits = BuildingPermit::whereBetween('y_latitude', [$boundingBox['minLat'], $boundingBox['maxLat']])
                                         ->whereBetween('x_longitude', [$boundingBox['minLon'], $boundingBox['maxLon']])
                                         ->where('issued_date', '>=', $startDate)
                                         ->limit(150)
                                         ->get();

        Log::info('Building permits data query executed.', ['rowsFetched' => $buildingPermits->count()]);

        // Transform data for the map
        return $buildingPermits->map(function ($permit) {
            return [
                'latitude' => $permit->y_latitude,
                'longitude' => $permit->x_longitude,
                'date' => $permit->issued_date,
                'type' => 'Building Permit',
                'info' => [
                    'permit_number' => $permit->permitnumber,
                    'work_type' => $permit->worktype,
                    'description' => $permit->description,
                    'applicant' => $permit->applicant,
                    'status' => $permit->status,
                    'address' => $permit->address,
                    'city' => $permit->city,
                    'state' => $permit->state,
                    'zip' => $permit->zip,
                ],
            ];
        });
    }
}