<?php

namespace App\Http\Controllers;

use App\Models\ThreeOneOneCase;
use Inertia\Inertia;

class SitePagesController extends Controller
{
    public function index()
    {
        $pages = SitePage::all();

        return Inertia::render('SitePageView', [
            'pages' => $pages,
        ]);
    }
}
