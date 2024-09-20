<?php

namespace App\Http\Controllers\Walkin;
use App\Models\Application;
use App\Models\LeadTimeline;
use App\Utils\Util;
use Illuminate\Support\Facades\Hash;
use Exception;

use App\Exports\LeadsExport;
use App\Http\Controllers\Controller;

use App\Models\Lead;

use App\Models\Project;

use App\Models\Url;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Http;


class WalkinController extends Controller
{
   /**
     * All Utils instance.
     *
     */

     protected $util;
     protected $lead_view;
     /**
      * Constructor
      *
      */
     public function __construct(Util $util)
     {
         $this->util = $util;
         $this->lead_view = ['list', 'kanban'];
     }

     public function direct(Request $request)
     {
         // Get the URL ID from the query parameter or another logic
         $urlId = $request->query('url_id');
         $url = Url::find($urlId);

         // Fetch unique project IDs from the Url model
         $projectIds = Url::distinct()->pluck('project_id');

         // Fetch the project names corresponding to the project IDs
         $projects = Project::whereIn('id', $projectIds)->pluck('name', 'id');

         // Fetch distinct campaigns and sub-sources
         $campaigns = Url::distinct()->pluck('campaign_name');
         $subSources = Url::distinct()->pluck('sub_source_name');

         // Fetch lead ID if needed (e.g., from a query parameter)
         $lead_id = $request->query('lead_id');

         return view('walkin.walkin', compact('projects', 'campaigns', 'url', 'subSources', 'lead_id'));
     }

public function getLeadDetailsKeyValuePair($lead_details_arr)
{
    if (!empty($lead_details_arr)) {
        $lead_details = [];
        foreach ($lead_details_arr as $lead_detail) {
            if (isset($lead_detail['key']) && !empty($lead_detail['key'])) {
                $lead_details[$lead_detail['key']] = $lead_detail['value'] ?? '';
            }
        }
        return $lead_details;
    }
    return [];
}
private function logTimeline(Lead $lead, $type, $description)
{
    $timeline = new LeadTimeline();
    $timeline->activity_type = $type;
    $timeline->lead_id = $lead->id;
    $timeline->payload = json_encode($lead->toArray()); // Convert array to JSON
    $timeline->description = $description;

    $timeline->save();
}
public function store(Request $request)
{
    // Extract input data and handle lead details
    $input = $request->except(['_method', '_token', 'redirect_to']);
    $input['lead_details'] = $this->getLeadDetailsKeyValuePair($input['lead_details'] ?? []);

    $lead = Lead::create($input);

    // Handling selectedValue for subSource
    $selectedValue = $request->input('subSource');

    $lead->parent_stage_id;
    // $lead->ref_num = $this->util->generateLeadRefNum($lead);
    // dd($lead);

    $lead->save();
    // Now handle URL creation or retrieval based on validated data
    $validatedData = $request->validate([
        'project_id' => 'required',
        'campaign_name' => 'required',
        'source_name' => 'required',
        'sub_source_name' => 'sometimes'
    ]);

    $url = Url::where('project_id', $validatedData['project_id'] ?? null)
        ->where('campaign_name', $validatedData['campaign_name'] ?? null)
        ->where('source_name', $validatedData['source_name'] ?? null)
        ->where('sub_source_name', $validatedData['sub_source_name'] ?? null)
        ->first();
    // If no such Url record exists, create a new one
    if (!$url) {
        $url = new Url([
            'project_id' => $validatedData['project_id'] ?? null,
            'campaign_name' => $validatedData['campaign_name'] ?? null,
            'source_name' => $validatedData['source_name'] ?? null,
            'sub_source_name' => $validatedData['sub_source_name'] ?? null,
        ]);
        $url->save();
    }
    // $lead->project_id = $url->project_id;

    $lead->update();


    $this->logTimeline($lead, 'lead_created', 'Lead Created Successfully');
    return redirect()->back()->with('success', 'Lead created successfully.');
}
public function searchLead(Request $request)
{
    $primaryPhone = $request->input('primary_phone');
    $secondaryPhone = $request->input('secondary_phone');

    try {
        // Validate that at least one phone number is provided
        if (!$primaryPhone && !$secondaryPhone) {
            return response()->json([
                'status' => 'error',
                'message' => 'Please provide at least one phone number.'
            ], 400);
        }

        // Search for leads matching either phone number in any of the JSON fields
        $lead = Lead::where(function ($query) use ($primaryPhone, $secondaryPhone) {
            if ($primaryPhone) {
                $query->whereJsonContains('mother_details->phone', $primaryPhone)
                      ->orWhereJsonContains('father_details->phone', $primaryPhone)
                      ->orWhereJsonContains('guardian_details->phone', $primaryPhone);
            }
            if ($secondaryPhone) {
                $query->orWhereJsonContains('mother_details->phone', $secondaryPhone)
                      ->orWhereJsonContains('father_details->phone', $secondaryPhone)
                      ->orWhereJsonContains('guardian_details->phone', $secondaryPhone);
            }
        })->first();

        // If a lead is found, return success response
        if ($lead) {
            return response()->json([
                'status' => 'found',
                'disclaimer' => 'Lead found! Please review the details.',
                'lead' => $lead,
                'lead_id' => $lead->id // Include lead ID
            ]);
        } else {
            // Create a new lead and generate OTP
            $lead = new Lead();
            $lead->phone = $primaryPhone ?? $secondaryPhone; // Set the phone number
            $lead->otp = rand(1000, 9999); // Generate OTP
            $lead->save(); // Save first to get the ID

            // Generate and set the reference number
            $lead->ref_num = $this->util->generateLeadRefNum($lead);
            $lead->save(); // Save reference number

            // Store OTP in the database (no need to hash for comparison, but consider encrypting if sensitive)
            $lead->otp_walkin = Hash::make($lead->otp);
            $lead->save();

            // Send OTP via SMS
            $response = Http::post('https://www.smsalert.co.in/api/push.json', [
                'apikey' => '654dfc01824b7',
                'sender' => 'TBBBC',
                'mobileno' => $lead->phone,
                'text' => "One time password: {$lead->otp} is your verification code. Please enter this to complete your submission. Powered by The Bumblebee Branding Company",
            ]);

            if ($response->failed()) {
                throw new Exception('Failed to send OTP. Please try again later.');
            }

            return response()->json([
                'status' => 'not_found',
                'message' => 'No lead found with the provided phone numbers. An OTP has been sent to the provided phone number.',
                'show_otp_container' => true, // Signal to show OTP container
                'lead_id' => $lead->id // Include lead ID
            ]);
        }
    } catch (\Exception $e) {
        // Log the error for debugging purposes
        \Log::error('Error in searchLead: ' . $e->getMessage());

        return response()->json([
            'status' => 'error',
            'message' => 'Something went wrong! Please try again later.'
        ], 500);
    }
}

public function validateOtp(Request $request)
{
    $request->validate([
        'otp' => 'required|numeric',
        'lead_id' => 'required|exists:leads,id'
    ]);

    $lead = Lead::find($request->input('lead_id'));

    if (!$lead) {
        return response()->json(['status' => 'invalid', 'message' => 'Lead not found.']);
    }

    // Check if the OTP is valid
    if (Hash::check($request->input('otp'), $lead->otp_walkin)) {
        return response()->json(['status' => 'valid']);
    } else {
        return response()->json(['status' => 'invalid', 'message' => 'Invalid OTP.']);
    }
}

// WalkinController.php
// app/Http/Controllers/LeadController.php

public function updateLead(Request $request, $leadId)
{
    $user = auth()->user(); // Get the authenticated user

    $lead = Lead::find($leadId);

    if (!$lead) {
        return response()->json(['status' => 'error', 'message' => 'Lead not found.'], 404);
    }

    // Update lead details
    $lead->parent_stage_id = $request->input('parent_stage_id');

    // Update father details
    $lead->father_details = [
        'name' => $request->input('father_details.name'),
        'phone' => $request->input('father_details.phone'),
        'email' => $request->input('father_details.email'),
        'occupation' => $request->input('father_details.occupation'),
        'income' => $request->input('father_details.income'),
    ];

    // Update mother details
    $lead->mother_details = [
        'name' => $request->input('mother_details.name'),
        'phone' => $request->input('mother_details.phone'),
        'email' => $request->input('mother_details.email'),
        'occupation' => $request->input('mother_details.occupation'),
        'income' => $request->input('mother_details.income'),
    ];

    // Update guardian details
    $lead->guardian_details = [
        'name' => $request->input('guardian_details.name'),
        'phone' => $request->input('guardian_details.phone'),
        'email' => $request->input('guardian_details.email'),
        'occupation' => $request->input('guardian_details.occupation'),
        'income' => $request->input('guardian_details.income'),
    ];

    // Update student details
    $lead->student_details = $request->input('student_details'); // Assuming it's an array of students

    // Validate input
    $validatedData = $request->validate([
        'project_id' => 'required',
        'campaign_name' => 'required',
        'source_name' => 'required',
        'sub_source_name' => 'sometimes' // Optional field
    ]);

    // Find or create a URL record
    $url = Url::where('project_id', $validatedData['project_id'] ?? null)
        ->where('campaign_name', $validatedData['campaign_name'] ?? null)
        ->where('source_name', $validatedData['source_name'] ?? null)
        ->where('sub_source_name', $validatedData['sub_source_name'] ?? null)
        ->first();

    if (!$url) {
        $url = Url::create([
            'project_id' => $validatedData['project_id'],
            'campaign_name' => $validatedData['campaign_name'],
            'source_name' => $validatedData['source_name'],
            'sub_source_name' => $validatedData['sub_source_name'] ?? null,
        ]);
    }

    // Update lead's project_id and sub_source_name with the values from URL
    $lead->project_id = $url->project_id;
    $lead->sub_source_name = $url->sub_source_name;
    $lead->created_by = $user->id;

    $lead->save();

    // Get the maximum id from Application and generate application number
    $maxId = Application::max('id');
    $applicationNo = 'AP' . str_pad($maxId + 1, 4, '0', STR_PAD_LEFT);

    // Create a new Application
    $application = new Application();
    $application->lead_id = $lead->id;
    $application->application_no = $applicationNo;
    $application->stage_id = 13; // Assuming 13 is the stage you want to set
    $application->save();

    // If the lead's created_by is null, show success response; otherwise, redirect
    if (is_null($lead->created_by)) {
        return response()->json(['status' => 'success', 'message' => 'Registered successfully.']);
    } else {
        return redirect()->route('admin.leads.index');
    }
}

// public function update(Request $request, $lead)
// {
//     // Find the lead by ID
//     $lead = Lead::find($lead);

//     if (!$lead) {
//         return response()->json([
//             'status' => 'error',
//             'message' => 'Lead not found.'
//         ], 404);
//     }

//     // Update lead details
//     $lead->update($request->all());

//     return response()->json([
//         'status' => 'success',
//         'message' => 'Lead updated successfully'
//     ]);
// }
public function showUpdateForm($leadId, Request $request)
{
    // Find the lead by ID
    $lead = Lead::find($leadId);

    if (!$lead) {
        return abort(404);
    }

    // Get url_id from the request query string (or another source)
    $urlId = $request->query('url_id');

    // Fetch the corresponding URL
    $url = Url::find($urlId);

    // Fetch unique project IDs from the Url model
    $projectIds = Url::distinct()->pluck('project_id');

    // Fetch the project names corresponding to the project IDs
    $projects = Project::whereIn('id', $projectIds)->pluck('name', 'id');

    // Fetch distinct campaigns and sub-sources
    $campaigns = Url::distinct()->pluck('campaign_name');
    $subSources = Url::distinct()->pluck('sub_source_name');

    // Pass all variables using compact, including 'url'
    return view('walkin.form_details', compact('lead', 'projects', 'campaigns', 'subSources', 'url'));
}


}
