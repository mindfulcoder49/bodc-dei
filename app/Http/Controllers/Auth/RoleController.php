<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;

class RoleController extends Controller
{
    /**
     * Update the user's role.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'role' => ['required', Rule::in(['admin', 'user'])]
        ]);

        $request->user()->update([
            'role' => $validated['role'],
        ]);

        return Redirect::route('profile.edit')->with('success', 'Role updated successfully.');
    }
}
