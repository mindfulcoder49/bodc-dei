<?php

namespace App\Http\Controllers;

use App\Models\ThreeOneOneCase;
use Inertia\Inertia;

class SiteProjectsController extends Controller
{
    public function index()
    {
        $projects = SiteProject::all();

        return Inertia::render('SiteProjectView', [
            'projects' => $projects,
        ]);
    }
}
