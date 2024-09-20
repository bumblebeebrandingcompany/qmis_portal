<?php
namespace App\Http\Controllers\Pulse;

use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\Pulse;

class LeadsController extends Controller
{
    /**
     * Search for a lead by phone number.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function searchByPhone(Request $request)
    {
        // Handle phone and source from both GET and POST requests
        $phoneNumber = $request->input('phone', $request->query('phone'));
        $source = $request->input('source', $request->query('source'));

        if (!$phoneNumber) {
            return response()->json([
                'error' => 'Phone number is required'
            ], Response::HTTP_BAD_REQUEST);
        }

        // Build the query to search by phone number
        $query = Lead::where(function ($query) use ($phoneNumber) {
            $query->whereJsonContains('mother_details->phone', $phoneNumber)
                ->orWhereJsonContains('father_details->phone', $phoneNumber)
                ->orWhereJsonContains('guardian_details->phone', $phoneNumber);
        });

        // Add source to query if provided
        if ($source) {
            $query->where('source', $source);
        }

        // Find first matching lead
        $lead = $query->first();

        // Save the request log in Pulse
        $pulse = new Pulse;
        $pulse->log = json_encode($request->all()); // Store the entire request as JSON
        $pulse->save();

        // Check if lead was found
        if ($lead) {
            return response()->json([
                'message' => 'Phone number exist',
                'source' => $lead->source,
            ], Response::HTTP_OK);
        }

        // If no lead was found
        return response()->json([
            'message' => 'Phone number does not exist',
        ], Response::HTTP_NOT_FOUND);
    }

}
