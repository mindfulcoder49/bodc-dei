<?php

namespace App\Http\Controllers;

use App\Models\Chirp;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ChirpController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $user = auth()->user();

        $quarantineChirpExists = Chirp::where('message', 'like', '%#quarantinechirps%')
                                    ->whereHas('user', function ($query) {
                                        $query->where('email', 'admin@mysite.com');
                                    })
                                    ->exists();

        if ($quarantineChirpExists) {
            // Regular logic for fetching chirps
            $chirpsQuery = Chirp::query();

            // Admin user can see all chirps
            if ($user->email === 'admin@mysite.com') {
                $chirps = $chirpsQuery->with('user:id,name')->latest()->get();
            } else {
                // Non-admin users see their own chirps and public chirps
                $chirps = $chirpsQuery->with('user:id,name')
                                    ->where(function ($query) use ($user) {
                                        $query->where('user_id', $user->id);
                                    })
                                    ->latest()
                                    ->get();
            }
        } else {
            // Regular logic for fetching chirps
            $chirpsQuery = Chirp::query();

            // Admin user can see all chirps
            if ($user->email === 'admin@mysite.com') {
                $chirps = $chirpsQuery->with('user:id,name')->latest()->get();
            } else {
                // Non-admin users see their own chirps and public chirps
                $chirps = $chirpsQuery->with('user:id,name')
                                    ->where(function ($query) use ($user) {
                                        $query->where('user_id', $user->id)
                                                ->orWhere('message', 'like', '%#public%');
                                    })
                                    ->latest()
                                    ->get();
            }
        }

        return Inertia::render('Chirps/Index', [
            'chirps' => $chirps,
        ]);
    }

    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'message' => 'required|string|max:500',
        ]);

        // Check if there's a chirp from admin@mysite.com with #stopchirps
        $stopChirpExists = Chirp::where('message', 'like', '%#stopchirps%')
                                ->whereHas('user', function ($query) {
                                    $query->where('email', 'admin@mysite.com');
                                })
                                ->exists();

        if ($stopChirpExists) {
            // Redirect back with an error message if chirp submissions are stopped
            return back()->withErrors(['message' => 'Chirp submissions are currently stopped.']);
        }

        $request->user()->chirps()->create($validated);

        return redirect(route('chirps.index'));
    }


    /**
     * Display the specified resource.
     */
    public function show(Chirp $chirp)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chirp $chirp)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chirp $chirp)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chirp $chirp)
    {
        $user = auth()->user();
    
        // Check if the user is the owner of the chirp or is an admin
        if ($user->id === $chirp->user_id || $user->email === 'admin@mysite.com') {
            $chirp->delete();
            return redirect()->route('chirps.index')->with('message', 'Chirp deleted successfully.');
        }
    
        // If the user is not authorized to delete the chirp
        return back()->withErrors(['message' => 'You do not have permission to delete this chirp.']);
    }
}
