<?php

namespace App\Http\Controllers;

use App\Models\CrimeData;
use App\Models\ThreeOneOneCase;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\BuildingPermit;

class GenericMapController extends Controller
{
    public function index(Request $request)
    {
        $crimeData = $this->getCrimeData($request);
        $caseData = $this->getThreeOneOneCaseData($request); // Get 311 case data
        $buildingPermits = $this->getBuildingPermits($request); // Get building permit data

        // Combine all datasets into a single collection
        $dataPoints = $crimeData->merge($caseData);
        $dataPoints = $dataPoints->merge($buildingPermits);

        return Inertia::render('GenericMap', [
            'dataPoints' => $dataPoints,
        ]);
    }

    public function getCrimeData(Request $request)
    {
        $query = CrimeData::query();
        $filters = $request->input('filters', []);

        // Apply filters based on the request
        if (!empty($filters)) {
            // Offense Codes filter
            if (!empty($filters['offense_codes'])) {
                $offenseCodes = is_string($filters['offense_codes']) ? explode(',', $filters['offense_codes']) : (array) $filters['offense_codes'];
                $query->whereIn('offense_code', $offenseCodes);
            }

            // Offense Category filter
            if (!empty($filters['offense_category'])) {
                $query->whereIn('offense_category', $filters['offense_category']);
            }

            // District filter
            if (!empty($filters['district'])) {
                $query->whereIn('district', $filters['district']);
            }

            // Date Range filter
            if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
                $query->whereBetween('occurred_on_date', [$filters['start_date'], $filters['end_date']]);
            }

            // Year filter
            if (!empty($filters['year'])) {
                $query->whereIn('year', $filters['year']);
            }

            // Shooting filter
            if (isset($filters['shooting'])) {
                $query->where('shooting', $filters['shooting'] ? 1 : 0);
            }

            // Record limit filter
            $limit = !empty($filters['limit']) && $filters['limit'] > 0 && $filters['limit'] <= 10000 ? $filters['limit'] : 1500;
            $query->limit($limit);
        } else {
            $query->limit(150);
        }

        // Fetch the filtered data
        $crimeData = $query->get();

        // Transform the data to the format needed for the generic map
        $dataPoints = $crimeData->map(function ($crime) {
            return [
                'latitude' => $crime->lat,
                'longitude' => $crime->long,
                'date' => $crime->occurred_on_date,
                'type' => 'Crime', // You can set a default or dynamic type
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

        return $dataPoints;
    }

    public function getThreeOneOneCaseData(Request $request)
    {
        $query = ThreeOneOneCase::query();
        $searchTerm = $request->get('searchTerm', '');

        // Apply search filters
        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                foreach (ThreeOneOneCase::SEARCHABLE_COLUMNS as $column) {
                    $q->orWhere($column, 'LIKE', "%{$searchTerm}%");
                }
            });
        }

        // Limit the number of records
        $query->limit(150);

        // Fetch the 311 cases
        $cases = $query->get();

        // Transform the data to the format needed for the generic map
        $dataPoints = $cases->map(function ($case) {
            return [
                'latitude' => $case->latitude, // Use correct model field for latitude
                'longitude' => $case->longitude, // Use correct model field for longitude
                'date' => $case->open_dt,
                'type' => '311 Case', // You can set a default or dynamic type
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

        return $dataPoints;
    }

    public function getBuildingPermits(Request $request)
    {
        // Implement the logic to fetch building permit data
        // This can be similar to the getCrimeData and getThreeOneOneCaseData methods
        // Return the data in the format needed for the generic map
        //for now just get the first 150 records
        $buildingPermits = BuildingPermit::limit(150)->get();

        $dataPoints = $buildingPermits->map(function ($permit) {
            return [
                'latitude' => $permit->y_latitude,
                'longitude' => $permit->x_longitude,
                'date' => $permit->issued_date,
                'type' => 'Building Permit', // You can set a default or dynamic type
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

        return $dataPoints;

    }
}
