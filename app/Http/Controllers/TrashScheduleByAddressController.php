<?php

namespace App\Http\Controllers;

use App\Models\TrashScheduleByAddress;
use Illuminate\Http\Request;

class TrashScheduleByAddressController extends Controller
{
    /**
     * Find closest matching addresses by address string.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'address' => 'required|string|min:3',
        ]);

        // Extract the address from the validated request
        $address = $validated['address'];

        // Find closest matching addresses using a "LIKE" query
        // You can use more advanced search techniques like full-text search or Levenshtein distance if needed
        $matches = TrashScheduleByAddress::where('full_address', 'LIKE', '%' . $address . '%')
            ->select('full_address', 'x_coord', 'y_coord')
            ->limit(10) // Limit the number of results
            ->get();

        // Return the results as JSON
        return response()->json([
            'status' => 'success',
            'data' => $matches,
        ]);
    }
}
