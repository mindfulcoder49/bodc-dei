<?php

namespace App\Http\Controllers;

use App\Models\ThreeOneOneCase;
use Inertia\Inertia;

class ThreeOneOneCaseController extends Controller
{
    public function index()
    {
        $allCases = ThreeOneOneCase::with('predictions')->get();

        $cases = $allCases->take(400);

        return Inertia::render('ThreeOneOneCaseList', [
            'cases' => $cases,
        ]);
    }

    

}
