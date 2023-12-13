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
    
        $chirpsQuery = Chirp::query();
    
        // Always include public chirps
        $chirpsQuery->where('message', 'like', '%#public%');
    
        if ($user->email !== 'admin@mysite.com') {
            // For non-admin users, also include their own chirps
            $chirpsQuery->orWhere(function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });
        } else {
            // For admin users, include all Chirps where user_id is not null
            $chirpsQuery->orWhereNotNull('user_id');
        }
    
        $chirps = $chirpsQuery->with('user:id,name')->latest()->get();
    
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
    public function store(Request $request) : RedirectResponse
    {
        $validated = $request->validate([
            'message' => 'required|string|max:255',
        ]);
 
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
