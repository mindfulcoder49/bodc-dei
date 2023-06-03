<?php

namespace App\Http\Controllers;

use App\Models\ThreeOneOneCase;
use Inertia\Inertia;

class ThreeOneOneCaseController extends Controller
{
    public function index()
    {
        $cases = ThreeOneOneCase::all();

        return Inertia::render('ThreeOneOneCaseList', [
            'cases' => $cases,
        ]);
    }
}
