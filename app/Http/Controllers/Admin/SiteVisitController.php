<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SiteVisitRequest;
use App\Models\Campaign;
use App\Models\Clients;
use App\Models\Lead;
use App\Models\LeadTimeline;
use App\Models\SiteVisit;
use App\Models\ParentStage;
use App\Models\Application;
use App\Models\StageNotes;
use App\Models\Tag;
use App\Models\User;
use App\Utils\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class SiteVisitController extends Controller
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
    }

    public function index(Request $request)
    {
        // Fetch leads that match the stage conditions
        $lead = Lead::whereIn('parent_stage_id', [11, 19])->get();
        $client = Clients::all();
        $agencies = User::all();
        $campaigns = Campaign::all();
        $parentStages = ParentStage::pluck('name', 'id');
        $tags = Tag::all();
    
        // Get the latest site visit for each lead_id
        $latestSiteVisits = SiteVisit::whereIn('stage_id', [11, 19])
            ->select('lead_id', DB::raw('MAX(created_at) as latest_visit'))
            ->groupBy('lead_id')
            ->pluck('latest_visit', 'lead_id')
            ->toArray();
    
        // Fetch the latest site visit details
        $sitevisits = SiteVisit::whereIn('lead_id', array_keys($latestSiteVisits))
            ->whereIn('created_at', array_values($latestSiteVisits))
            ->whereIn('stage_id', [11, 19])
            ->get();
    
        // Associate each lead with its site visit
        $leadWithSiteVisits = [];
        foreach ($sitevisits as $sitevisit) {
            $leadWithSiteVisits[] = [
                'lead' => $lead->where('id', $sitevisit->lead_id)->first(),
                'sitevisit' => $sitevisit,
            ];
        }
    
        $stages = ParentStage::pluck('name', 'id')->toArray();
    
        return view('admin.sitevisit.index', compact('campaigns', 'agencies', 'leadWithSiteVisits', 'client', 'parentStages', 'tags', 'stages','sitevisits','lead'));
    }
    

    public function store(SiteVisitRequest $request)
    {
        $input = $request->validated();

        // Fetch lead and assign values
        $lead = Lead::findOrFail($input['lead_id']);
        $parentStageId = $request->input('stage_id');

        $sitevisit = new SiteVisit();
        $sitevisit->lead_id = $lead->id;
        $maxId = Sitevisit::max('id') + 1;
        $sitevisit->ref_num = '40' . str_pad($maxId, 2, '0', STR_PAD_LEFT);
               $sitevisit->date = $input['date']; // Assign the date
        $sitevisit->time_slot = $input['time_slot']; // Assign the time slot
        $sitevisit->notes = $input['notes'];
        $sitevisit->user_id = auth()->user()->id;
        $sitevisit->stage_id = $parentStageId;
        $sitevisit->save();
        $sitevisit->lead->update(['parent_stage_id' => $sitevisit->stage_id]);
        $this->logTimeline($lead, $sitevisit, 'Site Visit created', 'sitevisit_created');
        $this->createUserInExternalApi($request, $lead, $sitevisit);
        $this->sendsitevisitFlow($request, $lead, $sitevisit->id);
        return redirect()->back()->with('success', 'Form submitted successfully!');
    }


    private function logTimeline(Lead $lead, $sitevisit, $type, $description)
    {
        $timeline = new LeadTimeline;
        $timeline->activity_type = $type;
        $timeline->lead_id = $lead->id;

        // Combine lead and site visit data into payload
        $payload = [
            'lead' => $lead->toArray(),
            'sitevisit' => $sitevisit->toArray()
        ];
        $timeline->description = $description;

        $timeline->payload = json_encode($payload); // Convert array to JSON
        // $timeline->description = $description;
        $timeline->save();
    }
    public function rescheduleSiteVisit(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'time_slot' => 'required|string',
            'lead_id' => 'required|integer',
            'date' => 'required|date',
            'notes' => 'required|string',
            'stage_id' => 'required|integer',
        ]);

        // Get the stage_id from the request
        $parentStageId = $request->input('stage_id');

        // Find and update existing site visits to mark as 'rescheduled'
        $siteVisitsToUpdate = SiteVisit::where('lead_id', $request->lead_id)
            ->where('stage_id', $parentStageId)
            ->get();

        foreach ($siteVisitsToUpdate as $siteVisit) {
            $siteVisit->update(['stage_id' => 19]);
        }

        // Create a new SiteVisit
        $newSiteVisit = new SiteVisit();
        $newSiteVisit->lead_id = $request->lead_id;
        $newSiteVisit->user_id = $request->user_id;
        $newSiteVisit->time_slot = $request->time_slot;  // Store the time_slot here
        $newSiteVisit->date = $request->date;
        $newSiteVisit->notes = $request->notes;
        $newSiteVisit->stage_id = $parentStageId;
        $newSiteVisit->save();

        // Update the lead
        $lead = Lead::find($request->lead_id);
        if ($lead) {
            $lead->update(['parent_stage_id' => 19]);
        }

        $this->logTimeline($lead, $newSiteVisit, 'Rescheduled', 'sitevisit_rescheduled');

        return redirect()->back()->with('success', 'Site visit rescheduled successfully.');
    }

    // public function reschedule(Request $request, $id)
    // {
    //     $request->validate([

    //        'date',
    //         'lead_id' => [
    //             'required',
    //             'integer',
    //         ],
    //         'notes' => 'nullable|string',
    //         'timeslot'
    //     ]);

    //     // Find the original SiteVisit record by ID
    //     // $originalSiteVisit = SiteVisit::find($id);
    //     // if (!$originalSiteVisit) {
    //     //     return redirect()->back()->with('error', 'Site visit not found.');
    //     // }

    //     $parentStageId = $request->input('stage_id');

    //     // Update the old schedule's stage_id to 19
    //     // $originalSiteVisit->update(['stage_id' => 19]);

    //     // Create a new SiteVisit
    //     $newSiteVisit = new SiteVisit();

    //     $newSiteVisit->lead_id = $request->lead_id;
    //     $newSiteVisit->user_id = $request->user_id;
    //     $newSiteVisit->notes = $request->notes;
    //     $newSiteVisit->date = $request->date;
    //     $newSiteVisit->time_slot = $request->time_slot;
    //     $newSiteVisit->stage_id = $request->stage_id;
    //     dd($newSiteVisit);
    //     $newSiteVisit->save();

    //     $lead = Lead::find($request->lead_id);
    //     if ($lead) {
    //         $lead->update(['parent_stage_id' => 19]);
    //     }
    //     $this->logTimeline($lead, $newSiteVisit, 'Rescheduled', 'sitevisit_rescheduled');
    //     return redirect()->back()->with('success', 'Site visit rescheduled successfully.');
    // }

    public function cancelSiteVisit(Request $request, $sitevisitId)
    {
        $sitevisit = SiteVisit::findOrFail($sitevisitId);
        $lead = Lead::find($sitevisit->lead_id);

        if ($lead) {
            $parentStageId = $request->input('stage_id');
            $sitevisit->update(['stage_id' => $parentStageId]);

            $lead->update(['stage_id' => $parentStageId]);

            $note = new StageNotes();
            $note->lead_id = $sitevisit->lead_id;
            $note->stage_id = $parentStageId;
            $note->notes = $request->input('notes');
            $note->stage = $request->input('stage');
            $note->save();

            // Call logTimeline with lead instance
            $this->logTimeline($lead, $sitevisit, 'Stage Changed', "Stage was updated to {$parentStageId}");

            return redirect()->back()->with('success', 'Site visit cancelled successfully!');
        }

        // Handle case where lead is not found
        // You might want to return a response indicating that the lead was not found.
    }


    public function conductedstage(Request $request)
    {
        $parentStageId = $request->input('stage_id');
        $lead = Lead::find($request->lead_id);

        if ($lead) {
            $parentStageId = $request->input('stage_id');

            $note = new StageNotes();
            $note->lead_id = $lead->id;
            $note->stage_id = $parentStageId;
            $note->notes = $request->input('notes');
            $note->stage = $request->input('stage');
            $note->save();

            // Update the stage_id of the latest SiteVisit
            $latestSiteVisit = $lead->siteVisits()->latest()->first();
            if ($latestSiteVisit) {
                $latestSiteVisit->update(['stage_id' => $parentStageId, 'user_id' => auth()->user()->id]);
            }

            $lead->parent_stage_id = $parentStageId;
            $lead->save();

            $this->logTimeline($lead, $latestSiteVisit, 'Stage Changed', "Stage was updated to {$parentStageId}");

            return redirect()->back()->with('success', 'Site visit conducted successfully!');
        }
    }

    public function notvisitedstage(Request $request)
    {
        $parentStageId = $request->input('stage_id');
        $lead = Lead::find($request->lead_id);

        if ($lead) {
            $parentStageId = $request->input('stage_id');

            $note = new StageNotes();
            $note->lead_id = $lead->id;
            $note->stage_id = $parentStageId;
            $note->notes = $request->input('notes');
            $note->stage = $request->input('stage');
            $note->save();

            // Update the stage_id of the latest SiteVisit
            $latestSiteVisit = $lead->siteVisits()->latest()->first();
            if ($latestSiteVisit) {
                $latestSiteVisit->update(['stage_id' => $parentStageId]);
            }

            $lead->stage_id = $parentStageId;
            $lead->save();

            $this->logTimeline($lead, $note, 'Stage Changed', "Stage was updated to {$parentStageId}");

            return redirect()->back()->with('success', 'Site visit conducted successfully!');
        }
    }
    public function cancelstage(Request $request)
    {
        $parentStageId = $request->input('stage_id');
        $lead = Lead::find($request->lead_id);

        if ($lead) {
            $parentStageId = $request->input('stage_id');

            $note = new StageNotes();
            $note->lead_id = $lead->id;
            $note->stage_id = $parentStageId;
            $note->notes = $request->input('notes');
            $note->stage = $request->input('stage');
            $note->save();

            // Update the stage_id of the latest SiteVisit
            $latestSiteVisit = $lead->siteVisits()->latest()->first();
            if ($latestSiteVisit) {
                $latestSiteVisit->update(['stage_id' => $parentStageId]);
            }

            $lead->stage_id = $parentStageId;
            $lead->save();

            $this->logTimeline($lead, $note, 'Stage Changed', "Stage was updated to {$parentStageId}");

            return redirect()->back()->with('success', 'Site visit conducted successfully!');
        }
    }
    public function conducted(Request $request, $sitevisitId)
    {
        $sitevisit = SiteVisit::findOrFail($sitevisitId);
        $lead = Lead::find($sitevisit->lead_id);
        if ($lead) {
            $parentStageId = $request->input('stage_id');
            $sitevisit->update([
                'stage_id' => $parentStageId,
                'created_by' => $request->input('created_by'),
            ]);
            $lead->update([
                'stage_id' => $parentStageId,

            ]);

            $note = new StageNotes();
            $note->lead_id = $sitevisit->lead_id;
            $note->stage_id = $parentStageId;
            $note->notes = $request->input('notes');
            $note->stage = $request->input('stage');
            $note->save();

            // Call logTimeline with lead instance
            $this->logTimeline($lead, $note, 'Stage Changed', "Stage was updated to {$parentStageId}");

            return redirect()->back()->with('success', 'Site visit cancelled successfully!');
        }

        // Handle case where lead is not found
        // You might want to return a response indicating that the lead was not found.
    }

    public function applicationpurchased(Request $request, $sitevisitId)
    {

        $sitevisit = SiteVisit::findOrFail($sitevisitId);

        $sitevisit->update([
            'stage_id' => $request->input('stage_id'),
            'notes' => $request->input('notes'),
            'user_id' => $request->input('user_id'),
            'application_no' => $request->input('application_no'),

        ]);
        if ($sitevisit->lead) {
            $sitevisit->lead->update([
                'stage_id' => $sitevisit->stage_id,
                'user_id' => $request->input('user_id'),
                'application_no' => $sitevisit->application_no,
                'notes' => $sitevisit->notes,
            ]);
            $parentStageId = $request->input('stage_id');
            $applicationpurchased = new Application();
            $applicationpurchased->lead_id = $sitevisit->lead_id;
            $applicationpurchased->who_assigned = auth()->user()->id; // Store current user_id
            $applicationpurchased->for_whom = $request->input('user_id');
            $applicationpurchased->application_no = $request->input('application_no');
            $applicationpurchased->follow_up_date = $request->input('follow_up_date');
            $applicationpurchased->notes = $request->input('notes');
            $applicationpurchased->follow_up_time = $request->input('follow_up_time');
            $applicationpurchased->stage_id = $parentStageId;
            $applicationpurchased->lead->update(['user_id' => $applicationpurchased->for_whom]);
            $applicationpurchased->save();
        }
        // Redirect or return a response as needed
        return redirect()->back()->with('success', 'application purchased successfully!');
    }



    public function notvisited(Request $request, $sitevisitId)
    {
        $sitevisit = SiteVisit::findOrFail($sitevisitId);
        $lead = Lead::find($sitevisit->lead_id);

        if ($lead) {
            $parentStageId = $request->input('stage_id');
            $sitevisit->update(['stage_id' => $parentStageId]);

            $lead->update(['stage_id' => $parentStageId]);

            $note = new StageNotes();
            $note->lead_id = $sitevisit->lead_id;
            $note->stage_id = $parentStageId;
            $note->notes = $request->input('notes');
            $note->stage = $request->input('stage');
            $note->save();

            // Call logTimeline with lead instance
            $this->logTimeline($lead, $note, 'Stage Changed', "Stage was updated to {$parentStageId}");

            return redirect()->back()->with('success', 'Site visit cancelled successfully!');
        }


    }
    protected function createUserInExternalApi(Request $request, Lead $lead, SiteVisit $sitevisit)
    {
        Log::info('Creating user in external API', ['lead' => $lead, 'sitevisit' => $sitevisit]);

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
            ['field_name' => 'lead_stage', 'value' => $sitevisit->parentStage->name ?? ''],
            ['field_name' => 'lead_sv_number', 'value' => $sitevisit->ref_num ?? ''],
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

    protected function sendsitevisitFlow(Request $request, Lead $lead, $id)
    {
        Log::info('Sending application flow data', ['lead' => $lead, 'id' => $id]);

        $sitevisit = SiteVisit::findOrFail($id);
        $data = [
            'lead_stage' => $sitevisit->parentStage->name ?? '',
            'lead_sv_number' => $sitevisit->ref_num ?? '',
        ];
        $chatRaceId = $lead->user_id;
        $flowResponse = Http::withHeaders([
            'X-Access-Token' => '1025340.dxtJ2Ma7me2STSkWkcF1u7JBICU4RhQ', // Replace with your actual access token
            'Content-Type' => 'application/json',
        ])->post("https://inbox.thebumblebee.in/api/users/$chatRaceId/send/1725964691854", $data);
        // Capture and store the flow response
        $flowSuccess = $flowResponse->successful();
        $lead->update(['flow_response' => $flowSuccess]);
        Log::info('Application flow response', ['flow_success' => $data]);
    }
}
