<?php

use App\Http\Controllers\ChirpController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ThreeOneOneCaseController;
use App\Http\Controllers\MlModelController;
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

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
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

