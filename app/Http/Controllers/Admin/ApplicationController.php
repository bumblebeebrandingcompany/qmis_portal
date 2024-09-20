<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;

use App\Http\Requests\StoreFollowupRequest;

use App\Models\Lead;
use App\Models\LeadTimeline;
use App\Models\User;

use App\Models\Application;
use App\Utils\Util;
use App\Models\ParentStage;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

use Symfony\Component\HttpFoundation\Response;

class ApplicationController extends Controller
{
    /**
     * All Utils instance.
     *
     */
    protected $util;
    public function __construct(Util $util)
    {
        $this->util = $util;
    }

    public function index(Request $request)
    {
        $lead = Lead::all();
        $agencies = User::all();
        // Alternatively, if you want to get the IDs in a loop
        $applications = Application::all();

        return view('admin.applicationpurchased.index', compact('lead', 'applications', 'agencies'));
    }
    public function store(Request $request)
    {
        $lead = Lead::find($request->lead_id);

        if ($lead) {
            $parentStageId = $request->input('stage_id');
            $applicationpurchased = new Application();
            $applicationpurchased->lead_id = $lead->id;
            // $applicationpurchased->who_assigned = auth()->user()->id;
            // $applicationpurchased->for_whom = $request->input('user_id');
            $maxId = Application::max('id') + 1;
            $applicationpurchased->application_no = '20' . str_pad($maxId, 2, '0', STR_PAD_LEFT);            // $applicationpurchased->application_date = $request->input('follow_up_date');
            $applicationpurchased->notes = $request->input('notes');
            // $applicationpurchased->application_time = $request->input('follow_up_time');
            $applicationpurchased->stage_id = $parentStageId;
            // $applicationpurchased->lead->update(['user_id' => $applicationpurchased->for_whom]);
            $applicationpurchased->save();
            if ($applicationpurchased->lead) {
                $applicationpurchased->lead->update(['parent_stage_id' => $applicationpurchased->stage_id]);
                $latestSiteVisit = $applicationpurchased->lead->siteVisits()->latest()->first();
                if ($latestSiteVisit) {
                    $latestSiteVisit->update(['stage_id' => $applicationpurchased->stage_id]);
                }
            }
            $this->logTimeline($lead, $applicationpurchased, 'Stage Changed', "Stage was updated to {$parentStageId}");
            return redirect()->back()->with('success', 'Form submitted successfully!');
        } else {
            return redirect()->back()->with('error', 'Lead not found!');
        }
    }
    private function logTimeline(Lead $lead, $applications, $type, $description)
    {
        $timeline = new LeadTimeline();
        $timeline->activity_type = $type;
        $timeline->lead_id = $lead->id;

        // Combine lead and site visit data into payload
        $payload = [
            'lead' => $lead->toArray(),
            'application' => $applications->toArray()
        ];
        $timeline->description = $description;

        $timeline->payload = json_encode($payload); // Convert array to JSON
        // $timeline->description = $description;
        $timeline->save();
    }
    public function applicationaccepted(Request $request)
    {
        // $input = $request->validated();

        // Find the lead based on its ID
        $lead = Lead::findOrFail($request['lead_id']);
        $existingApplication = Application::where('lead_id', $lead->id)->first();
        $applicationexisit = $existingApplication->application_no;

        // Check if the lead is not null before proceeding
        if ($lead) {
            $parentStageId = $request->input('stage_id');

            $applicationpurchased = new Application();
            $applicationpurchased->lead_id = $lead->id;
            // $applicationpurchased->user_id = $input['user_id'];
            $applicationpurchased->application_no = $applicationexisit;

            $applicationpurchased->notes = $request['notes'];
            $applicationpurchased->stage_id = $parentStageId;
            $applicationpurchased->save();

            // Check if $applicationpurchased->lead is not null before updating
            if ($applicationpurchased->lead) {
                $applicationpurchased->lead->update(['parent_stage_id' => $applicationpurchased->stage_id   ]);
            }
            $this->logTimeline($lead, $applicationpurchased, 'Stage Changed', "Stage was updated to {$parentStageId}");
            $this->createUserInExternalApi($request, $lead, $applicationpurchased);

            // Call the sendApplicationFlow method after creating the user
            $this->sendApplicationFlow($request, $lead, $applicationpurchased->id);
            // dd('sendApplicationFlow');
            return redirect()->back()->with('success', 'Form submitted successfully!');
        } else {
            // Handle the case where the lead is not found
            return redirect()->back()->with('error', 'Lead not found!');
        }
    }
    protected function createUserInExternalApi(Request $request, Lead $lead, Application $applicationpurchased)
    {
        Log::info('Creating user in external API', ['lead' => $lead, 'application' => $applicationpurchased]);

        $chatRaceData = [
            'phone' => $lead->user_id,
            'last_name' => '',
            'gender' => 'unknown',
            'actions' => [
                [
                    'action' => 'send_flow',
                    'flow_id' => 11111, // Replace with the correct flow ID
                ],
                [
                    'action' => 'add_tag',
                    'tag_name' => 'YOUR_TAG_NAME', // Replace with the actual tag name
                ],
            ]
        ];

        // Add custom field values (CUF) to the actions array
        $customFields = [

            ['field_name' => 'lead_stage', 'value' => $lead->parentStage->name ?? ''],
            ['field_name' => 'lead_app_number', 'value' =>$applicationpurchased->application_no ?? ""],

        ];

        // Append each custom field to the actions array
        foreach ($customFields as $field) {
            $chatRaceData['actions'][] = [
                'action' => 'set_field_value',
                'field_name' => $field['field_name'],
                'value' => $field['value']
            ];
        }

        // Send request to the external API
        $response = Http::withHeaders([
            'X-Access-Token' => '1025340.dxtJ2Ma7me2STSkWkcF1u7JBICU4RhQ', // Replace with actual access token
            'Content-Type' => 'application/json',
        ])->post('https://inbox.thebumblebee.in/api/users', $chatRaceData);

        if ($response->successful()) {
            $responseData = $response->json();
            $chatRaceId = $responseData['data']['id'] ?? null;
            $lead->user_id = $chatRaceId;
            $lead->save();
            Log::info('User created via external API', ['chatRaceId' => $chatRaceId]);
        } else {
            Log::error('Failed to create user via external API: ' . $response->body());
            throw new Exception('Failed to create user via external API.');
        }
    }

    protected function sendApplicationFlow(Request $request, Lead $lead, $id)
{
    // Log the incoming request
    Log::info('Sending application flow data', ['lead' => $lead, 'id' => $id]);

    // Retrieve the `stage_id` from the request
    $parentStageId = $request->input('stage_id');

    // Find the stage by the given `stage_id`
    $stage = ParentStage::find($parentStageId);

    // Check if the stage exists
    if (!$stage) {
        Log::error('Stage not found', ['stage_id' => $parentStageId]);
        return response()->json(['error' => 'Stage not found'], 404);
    }

    // Find the application by ID
    $applicationpurchased = Application::findOrFail($id);

    // Prepare the data to be sent in the flow
    $data = [
        'lead_app_number' => $applicationpurchased->application_no,
        'lead_stage' => $stage->name, // Send the stage name
    ];

    // Send the flow to the ChatRace API
    $chatRaceId = $lead->user_id;
    $flowResponse = Http::withHeaders([
        'X-Access-Token' => '1025340.dxtJ2Ma7me2STSkWkcF1u7JBICU4RhQ', // Replace with your actual access token
        'Content-Type' => 'application/json',
    ])->post("https://inbox.thebumblebee.in/api/users/$chatRaceId/send/1725964635001", $data);

    // Check if the flow response was successful and store the result
    $flowSuccess = $flowResponse->successful();
    $lead->update(['flow_response' => $flowSuccess]);

    // Log the response data
    Log::info('Application flow response', ['flow_success' => $flowSuccess, 'data_sent' => $data]);
}

}

