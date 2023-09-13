<?php

namespace App\Http\Controllers;

use App\Models\ThreeOneOneCase;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;


class ThreeOneOneCaseController extends Controller
{
    /**
     * Display a listing of the cases with associated predictions.
     *
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        $searchTerm = $request->get('searchTerm', '');
       //Log::debug("doing a search for $searchTerm");
        $cases = ThreeOneOneCase::with(['predictions'])
            ->where(function($query) use ($searchTerm) {
                foreach (ThreeOneOneCase::SEARCHABLE_COLUMNS as $column) {
                    $query->orWhere($column, 'LIKE', "%{$searchTerm}%");
                }
            })
            ->take(500)
            ->get(); 

        return Inertia::render('ThreeOneOneCaseList', [
            'cases' => $cases,
            'search' => $searchTerm
        ]);
    }

    public function indexnofilter(Request $request)
    {
        $searchTerm = $request->get('searchTerm', '');
       //Log::debug("doing a search for $searchTerm");
        $cases = ThreeOneOneCase::with(['predictions'])
            ->where(function($query) use ($searchTerm) {
                foreach (ThreeOneOneCase::SEARCHABLE_COLUMNS as $column) {
                    $query->orWhere($column, 'LIKE', "%{$searchTerm}%");
                }
            })
            ->take(7000)->get(); 

        return Inertia::render('ThreeOneOneProject', [
            'cases' => $cases,
            'search' => $searchTerm
        ]);
    }
}
