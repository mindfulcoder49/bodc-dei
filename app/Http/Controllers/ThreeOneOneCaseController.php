<?php

namespace App\Http\Controllers;

use App\Models\ThreeOneOneCase;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ThreeOneOneCaseController extends Controller
{
    /**
     * Display a listing of the cases with associated predictions.
     *
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        $searchTerm = $request->get('search', '');
        
        $cases = ThreeOneOneCase::with(['predictions' => function($query) use ($searchTerm) {
                $query->where('prediction', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('prediction_date', 'LIKE', "%{$searchTerm}%");
            }])
            ->where(function($query) use ($searchTerm) {
                foreach (ThreeOneOneCase::SEARCHABLE_COLUMNS as $column) {
                    $query->orWhere($column, 'LIKE', "%{$searchTerm}%");
                }
            })
            ->take(400)
            ->get();

        return Inertia::render('ThreeOneOneCaseList', [
            'cases' => $cases,
            'search' => $searchTerm
        ]);
    }
}
