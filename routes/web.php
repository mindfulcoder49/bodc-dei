<?php

use App\Http\Controllers\ChirpController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ThreeOneOneCaseController;
use App\Http\Controllers\MlModelController;
use App\Http\Controllers\CrimeReportsController;
use App\Http\Controllers\GithubAnalysisController;
use App\Http\Controllers\InteractionController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\UsageController;
use App\Http\Controllers\Auth\RoleController;

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('home');

/* Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');  */

/* add route to dashboard that goes through middleware but doesn't require user to be logged in */
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

Route::get('/api/crime-reports', [CrimeReportsController::class, 'handle']);
Route::get('/api/github-analysis', [GithubAnalysisController::class, 'handle']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::put('/profile', [RoleController::class, 'update'])->name('role.update');


    Route::resource('interactions', InteractionController::class);
    Route::resource('templates', TemplateController::class)->except(['show', 'destroy']);
    Route::resource('usage', UsageController::class);


    //Route::get('/api/AI', [AIController::class, 'handle']);
});

// Add a route for fetching a template by name
Route::get('/templates/{name}', [TemplateController::class, 'getTemplateByName'])->name('templates.getByName')->middleware('auth');

// Update the delete route to use name instead of ID
Route::delete('/templates/{name}', [TemplateController::class, 'destroyByName'])->name('templates.destroyByName')->middleware('auth');

// Add a route for the estimateCost function in the InteractionController
Route::post('/interact/estimate', [InteractionController::class, 'estimateCost'])->name('interact.estimate')->middleware('auth');

// Add a route for the logInteraction function in the InteractionController
Route::post('/interact/log', [InteractionController::class, 'logInteraction'])->name('interact.log')->middleware('auth');

//Route::post('/interact/generate', [InteractionController::class, 'generateCompletion'])->middleware('auth');


Route::middleware(['auth', 'admin'])->group(function () {
    //
});


require __DIR__.'/auth.php';

Route::get('/cases', [ThreeOneOneCaseController::class, 'index'])->name('cases.index');

Route::get('/scatter', [ThreeOneOneCaseController::class, 'indexnofilter'])->name('cases.indexnofilter');

Route::get('/311models', [MlModelController::class, 'index'])->name('models.index');

Route::inertia('/about', "About")->name('about');
Route::inertia('/contact', "Contact")->name('contact');
Route::inertia('/projects', "Projects")->name('projects');
Route::inertia('/311demo', "ThreeOneOneDemo")->name('311demo');
Route::inertia('/thebostonappdemo', "TheBostonAppDemo")->name('thebostonappdemo');

Route::resource('chirps', ChirpController::class)
    ->only(['index', 'store', 'destroy'])
    ->middleware(['auth', 'verified']);

