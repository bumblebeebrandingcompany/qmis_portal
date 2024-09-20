<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lead;
use Illuminate\Support\Facades\Log;
use App\Models\Url;

class LeadsController extends Controller
{
    public function store(Request $request)
    {
        // Log incoming request for debugging
        Log::info('Request data:', $request->all());

        // Validate the incoming request data
        $validatedData = $request->validate([
            'father_name' => 'nullable|string',
            'father_phone' => 'nullable|string',
            'father_email' => 'nullable|email',
            'mother_name' => 'nullable|string',
            'mother_phone' => 'nullable|string',
            'mother_email' => 'nullable|email',
            'guardian_name' => 'nullable|string',
            'guardian_relationship' => 'nullable|string',
            'guardian_phone' => 'nullable|string',
            'guardian_email' => 'nullable|email',
            'project' => 'nullable|string',
            'campaign' => 'nullable|string',
            'source' => 'nullable|string',
            'sub_source' => 'nullable|string',
            'ref_num'=>'nullable|string'
        ]);

        // Check if a Url record already exists with the same project, campaign, source, and sub_source
        $url = Url::where('project', $validatedData['project'] ?? null)
            ->where('campaign_name', $validatedData['campaign'] ?? null)
            ->where('source_name', $validatedData['source'] ?? null)
            ->where('sub_source_name', $validatedData['sub_source'] ?? null)
            ->first();

        // If no such Url record exists, create a new one
        if (!$url) {
            $url = new Url([
                'project' => $validatedData['project'] ?? null,
                'campaign_name' => $validatedData['campaign'] ?? null,
                'source_name' => $validatedData['source'] ?? null,
                'sub_source_name' => $validatedData['sub_source'] ?? null,
            ]);
            $url->save();
        }
        // Create a new Lead model instance and assign the project_id as the Url model's id
        $lead = new Lead();

        $lead->father_details = [
            'name'  => $validatedData['father_name'] ?? null,
            'phone' => $validatedData['father_phone'] ?? null,
            'email' => $validatedData['father_email'] ?? null,
        ];

        $lead->mother_details = [
            'name'  => $validatedData['mother_name'] ?? null,
            'phone' => $validatedData['mother_phone'] ?? null,
            'email' => $validatedData['mother_email'] ?? null,
        ];

        $lead->guardian_details = [
            'name'         => $validatedData['guardian_name'] ?? null,
            'relationship' => $validatedData['guardian_relationship'] ?? null,
            'phone'        => $validatedData['guardian_phone'] ?? null,
            'email'        => $validatedData['guardian_email'] ?? null,
        ];

        $lead->lead_ref_no = $validatedData['ref_num'] ?? null;
        // Assign the Url model's id to the Lead's project_id field
        $lead->project_id = $url->id;
      $lead->parent_stage_id=8;
        // Save the lead data
        $lead->save();

        // Log the validated data
        Log::info('Validated data:', $validatedData);
        return response()->json(['message' => 'Data received and stored successfully'], 200);
    }
}



