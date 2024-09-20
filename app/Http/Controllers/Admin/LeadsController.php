<?php

namespace App\Http\Controllers\Admin;

use App\Exports\LeadsExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyLeadRequest;
use App\Http\Requests\StoreLeadRequest;
use App\Models\Agency;
use App\Models\Application;
use App\Models\CallRecord;
use App\Models\Campaign;
use App\Models\Clients;
use App\Models\Document;
use App\Models\Followup;
use App\Models\Lead;
use App\Models\LeadEvents;
use App\Models\LeadTimeline;
use App\Models\Lost;
use App\Models\Note;
use App\Models\NoteNotInterested;
use App\Models\ParentStage;
use App\Models\Project;
use App\Models\SiteVisit;
use App\Models\Stage;
use App\Models\SubSource;
use App\Models\Tag;
use App\Models\Url;
use App\Models\User;
use App\Models\RazorLog;
use App\Models\EmailLog;
use App\Notifications\LeadDocumentShare;
use App\Utils\Util;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;


class LeadsController extends Controller
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


    public function initiateCall(Lead $lead)
    {
        $user = auth()->user();
        $agentNumber = $user->contact_number_1;
        $source = $user->source;

        $customerNumber = $lead->father_details['phone']
            ?? $lead->mother_details['phone']
            ?? $lead->guardian_details['phone'];

        if (!$customerNumber) {
            return response()->json(['message' => 'No valid customer phone number found'], 400);
        }

        $apiKey = '4e4173ce50aaae6da770dd5cb3729e33';
        $remoteId = '12345';
        $apiUrl = 'https://0xq3m25xe2.execute-api.ap-south-1.amazonaws.com/V1/selldo_c2c';

        $response = Http::get($apiUrl, [
            'agent_number' => $agentNumber,
            'customer_number' => $customerNumber,
            'apikey' => $apiKey,
            'remote_id' => $remoteId,
        ]);

        Log::info('Source value:', ['source' => $source]);

        $lead->source = $source;
        $lead->save();

        if ($response->successful()) {
            return response()->json(['message' => 'Call initiated successfully'], 200);
        } else {
            return response()->json(['message' => 'Failed to initiate call'], $response->status());
        }
    }
    public function projectViseLeads(Request $request, $id)
    {
        // Find the project by ID
        $project = Project::find($id);

        // If project not found, redirect with an error message
        if (!$project) {
            return redirect()->route('admin.leads.index')->with('error', 'Project not found.');
        }

        // Get all URL records
        $urls = Url::all();

        // Get the search term from the request
        $search = $request->input('search');

        // Start the query for leads based on project_id
        $query = Lead::where('project_id', $project->id);

        // Define the stages for front office or admission team
        $frontOfficeStages = [13, 11, 14, 19, 29, 25, 30,9];

        // Check if the user is a front office user or admission team
        if (auth()->user()->is_admissionteam || auth()->user()->is_frontoffice) {
            // Filter the leads to show only specified stages
            $query->whereIn('parent_stage_id', $frontOfficeStages);
        }

        // Apply search filter if a search term is provided
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('ref_num', 'LIKE', "%{$search}%")
                  ->orWhere('payload', 'LIKE', "%{$search}%");
            });
        }

        // Execute the query and get the leads
        $leads = $query->get();

        // Map the matching campaign and source based on sub_source_name
        foreach ($leads as $lead) {
            // Initialize campaign and source as empty
            $lead->campaign_name = '';
            $lead->source_name = '';

            // Decode additional_details JSON if it exists
            if (!empty($lead->additional_details)) {
                $additionalDetails = json_decode($lead->additional_details, true);
                $lead->campaign_name = $additionalDetails['campaign'] ?? '';
                $lead->source_name = $additionalDetails['source'] ?? '';
            }

            // Check for sub_source_name match in URLs
            if ($lead->sub_source_name) {
                foreach ($urls as $url) {
                    if (isset($url->sub_source_name) && $url->sub_source_name == $lead->sub_source_name) {
                        // Update campaign and source if URL match found
                        $lead->campaign_name = $url->campaign_name ?? $lead->campaign_name;
                        $lead->source_name = $url->source_name ?? $lead->source_name;
                        break; // Stop if match is found
                    }
                }
            }
        }

        // Retrieve stages and group leads by stage
        $lead_stages = Lead::getStages($project->id);
        $stages = ParentStage::whereIn('id', $frontOfficeStages)->pluck('name', 'id')->toArray(); // Only retrieve the defined stages
        $stage_wise_leads = $leads->groupBy(function ($lead) {
            return $lead->parent_stage_id ?? 'no_stage';
        });

        // Determine view type (list or kanban)
        $lead_view = $request->input('view', 'list');

        // Return the view with the necessary data
        return view('admin.leads.index', compact('leads', 'lead_view', 'stage_wise_leads', 'lead_stages', 'stages', 'project'));
    }
    
    public function index(Request $request)
    {

        $projects = Project::all();

        return view('admin.leads.project_leads', compact('projects'));
    }

    public function create(Request $request)
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
        $lead = Lead::find($lead_id); // Find the lead based on the lead_id

        // Fetch the stage name using the parent_stage_id from the lead
        $stageName = null;
        if ($lead && $lead->parent_stage_id) {
            $stage = ParentStage::find($lead->parent_stage_id);
            $stageName = $stage ? $stage->name : null; // Get the stage name or null if not found
        }

        // Fetch all parent stages for the dropdown selection
        $stage = ParentStage::pluck('name', 'id');

        return view('admin.leads.create', compact('projects', 'campaigns', 'url', 'subSources', 'lead_id', 'stage', 'stageName'));
    }


    public function store(Request $request)
    {
        // Extract input data and handle lead details
        $input = $request->except(['_method', '_token', 'redirect_to']);
        $input['lead_details'] = $this->getLeadDetailsKeyValuePair($input['lead_details'] ?? []);
        $input['created_by'] = auth()->user()->id;

        // Initialize payload array
        $payload = [];
        $inputPayload = $request->input('payload', []);
        foreach ($inputPayload as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $subKey => $subValue) {
                    $payload["{$key}][{$subKey}"] = $subValue;
                }
            } else {
                $payload[$key] = $value;
            }
        }

        // Create the lead
        $lead = Lead::create($input);
        \Log::info('Lead created:', ['lead' => $lead]);

        if (!$lead) {
            \Log::error('Lead creation failed.', ['input' => $input]);
            return redirect()->back()->withErrors('Lead creation failed.');
        }

        // Handling selectedValue for subSource
        $selectedValue = $request->input('subSource');
        \Log::info('Selected SubSource:', ['selectedValue' => $selectedValue]);

        if ($selectedValue) {
            list($srdId, $subSourceName) = explode('|', $selectedValue);
            $srd = Url::find($srdId);
            if ($srd) {
                \Log::info('Found Url:', ['srd' => $srd]);
                // Set sub_source_name and sub_source_id based on ip_source
                if ($srd->ip_source == 0) {
                    $lead->sub_source_name = $subSourceName;
                } else if ($srd->ip_source == 1) {
                    $lead->sub_source_name = auth()->user()->name;
                }
                $lead->sub_source_id = $srdId;
                $payload['bbc_lms[lead][sub_source]'] = $subSourceName;
                $payload['bbc_lms[lead][project]'] = $srd->project->name;
                $payload['bbc_lms[lead][campaign]'] = $srd->campaign;
                $payload['bbc_lms[lead][source]'] = $srd->source;
                $payload['bbc_lms[lead][mother_name]'] = $request->input('name');
                $payload['bbc_lms[lead][mother_phone]'] = $request->input('phone');
                $payload['bbc_lms[lead][mother_email]'] = $request->input('secondary_phone');
                $payload['bbc_lms[lead][email]'] = $request->input('email');
                $payload['bbc_lms[lead][addl_email]'] = $request->input('addl_email');
                $lead->project_id = $srd->project_id;
            } else {
                \Log::error('Url not found:', ['srdId' => $srdId]);
            }
        }

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

        $lead->project_id = $url->project_id;
        $lead->parent_stage_id;
        $lead->ref_num = $this->util->generateLeadRefNum($lead);
        \Log::info('Generated Reference Number:', ['ref_num' => $lead->ref_num]);

        $lead->save();
        \Log::info('Lead saved:', ['lead' => $lead]);

        $this->logTimeline($lead, 'lead_created', 'Lead Created Successfully');
        return redirect()->back()->with('success', 'Lead created successfully.');
    }

    // public function store(Request $request)
    // {
    //     // Log incoming request data
    //     Log::info('Incoming request data:', $request->all());

    //     $name = null;
    //     $email = null;

    //     // Check if it's a POST request with JSON data
    //     if ($request->isMethod('post')) {
    //         $validatedData = $request->validate([
    //             'api' => 'required|array',
    //             'api.*.request_body' => 'required|array',
    //             'api.*.request_body.*.key' => 'required|array',
    //             'api.*.request_body.*.value' => 'required|array',
    //         ]);

    //         // Log validated data for debugging
    //         Log::info('Validated data:', $validatedData);

    //         // Process the JSON data
    //         foreach ($validatedData['api'] as $webhookKey => $apiData) {
    //             foreach ($apiData['request_body'] as $rbKey => $requestBody) {
    //                 $keys = $requestBody['key'];
    //                 $values = $requestBody['value'];

    //                 foreach ($keys as $index => $key) {
    //                     $value = $values[$index];

    //                     // Log each key-value pair
    //                     Log::info("Processing key: {$key} with value: {$value}");

    //                     // Store data based on the specific key
    //                     if ($key === 'bbc_lms[lead][mother_name]') {
    //                         $name = $value;
    //                     } elseif ($key === 'bbc_lms[lead][mother_email]') {
    //                         $email = $value;
    //                     }
    //                 }
    //             }
    //         }
    //     } else {
    //         // Handle query string data
    //         $queryData = $request->query();

    //         // Log query data
    //         Log::info('Query data:', $queryData);

    //         if (!empty($queryData)) {
    //             if (isset($queryData['bbc_lms[lead][mother_name]'])) {
    //                 $name = $queryData['bbc_lms[lead][mother_name]'];
    //             }
    //             if (isset($queryData['bbc_lms[lead][mother_email]'])) {
    //                 $email = $queryData['bbc_lms[lead][mother_email]'];
    //             }
    //         }
    //     }

    //     // Log the final values to be stored
    //     Log::info('Final data to be stored:', ['name' => $name, 'email' => $email]);

    //     // At this point, you have the `name` and `email` variables filled with the correct values.
    //     // You can now store these in your database or process them as needed.

    //     // For example, storing them in the database (assuming you have a model called `Lead`):
    //     $lead = new Lead();
    //     $lead->name = $name;
    //     $lead->email = $email;
    //     $lead->save();

    //     return response()->json(['message' => 'Data received and stored successfully'], 200);
    // }

    public function showTimeline($leadId)
    {
        $lead = Lead::findOrFail($leadId);
        $allActivities = $this->getLeadActivities($lead);

        return view('leads.timeline', compact('lead', 'allActivities'));
    }
    public function edit(Lead $lead)
    {
        // if (!auth()->user()->is_superadmin) {
        //     abort(403, 'Unauthorized.');
        // }
        $urls = Url::all();
        $leads = Lead::all();
        return view('admin.leads.edit', compact('lead', 'leads', 'urls'));
    }

    private function logTimeline(Lead $lead, $type, $description)
    {
        $timeline = new LeadTimeline;
        $timeline->activity_type = $type;
        $timeline->lead_id = $lead->id;
        $timeline->payload = json_encode($lead->toArray()); // Convert array to JSON
        $timeline->description = $description;
        $timeline->save();
    }
    public function update(Request $request, Lead $lead)
    {
        // Extract input data
        $input = $request->except(['_method', '_token']);
        $input['parent_stage_id'] = $request->input('parent_stage_id');

        // Map fields to JSON columns
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
        $lead->student_details = $request->input('student_details'); // Assuming it's an array of students
        $lead->common_details = $request->input('common_details');
        $lead->parent_stage_id = $input['parent_stage_id'];
        $lead->save();

        // Redirect with success message
        return redirect()->back()->with('success', 'Form submitted successfully!');
    }

    public function show(Lead $lead, Request $request)
    {
        if (auth()->user()->is_channel_partner && ($lead->created_by != auth()->user()->id)) {
            abort(403, 'Unauthorized.');
        }

        // Fetch the lead events for the timeline
        $lead_events = LeadEvents::where('lead_id', $lead->id)
            ->select('event_type', 'webhook_data', 'created_at as added_at', 'source')
            ->orderBy('added_at', 'desc')
            ->get();
        $timelineItems = LeadTimeline::where('lead_id', $lead->id)->get();

        // Other necessary data
        $notintrested = Lost::all();
        $parentStages = ParentStage::all();
        $stages = Stage::all();
        $tags = Tag::all();
        $leads = Lead::all();
        $sitevisits = SiteVisit::all();
        $allActivities = $this->getLeadActivities($lead);
        $noteNotInterested = NoteNotInterested::all();
        $client = Clients::all();

        // Eager load the `url` relationship
        $lead->load('url');
        $url = $lead->url;

        // Match sub_source_name and get campaign_name and source_name from Url model
        $matchedUrl = Url::where('sub_source_name', $lead->sub_source_name)->first();
        $campaignName = $matchedUrl ? $matchedUrl->campaign_name : null;
        $sourceName = $matchedUrl ? $matchedUrl->source_name : null;

        // Check in additional_details if campaign and source names are missing
        if (is_null($campaignName) || is_null($sourceName)) {
            if (!empty($lead->additional_details)) {
                $additionalDetails = json_decode($lead->additional_details, true);
                $campaignName = $campaignName ?? ($additionalDetails['campaign'] ?? '');
                $sourceName = $sourceName ?? ($additionalDetails['source'] ?? '');

            }
        }
        $lead->additional_details = json_decode($lead->additional_details, true);

        // Time slots and other data
        $timeSlots = [
            '09:00 AM - 10:00 AM', '10:00 AM - 11:00 AM', '11:00 AM - 12:00 PM',
            '12:00 PM - 01:00 PM', '01:00 PM - 02:00 PM', '02:00 PM - 03:00 PM',
            '03:00 PM - 04:00 PM', '04:00 PM - 05:00 PM', '05:00 PM - 06:00 PM',
            '06:00 PM - 07:00 PM', '07:00 PM - 08:00 PM', '08:00 PM - 09:00 PM',
            '09:00 PM - 10:00 PM'
        ];
        $agencies = Agency::all();
        $users = User::all();
        $user_id = $request->get('user_id');

        // Fetch follow-ups, call records, site visit, and notes filtered by user_id if provided
        $followUps = Followup::where('lead_id', $lead->id)
            ->when($user_id, function ($query) use ($user_id) {
                return $query->where('user_id', $user_id);
            })->get();

        $callRecords = CallRecord::where('lead_id', $lead->id)
            ->when($user_id, function ($query) use ($user_id) {
                return $query->where('user_id', $user_id);
            })->get();

        $sitevisit = SiteVisit::where('lead_id', $lead->id)
            ->when($user_id, function ($query) use ($user_id) {
                return $query->where('user_id', $user_id);
            })->first();

        $note = Note::where('lead_id', $lead->id)
            ->when($user_id, function ($query) use ($user_id) {
                return $query->where('user_id', $user_id);
            })->get();
            $logs = RazorLog::where('lead_id', $lead->id)
            ->when($user_id, function ($query) use ($user_id) {
                return $query->where('user_id', $user_id);
            })->get();

        $applications = Application::where('lead_id', $lead->id)->get();

        // Pagination for notes
        $itemsPerPage = $request->input('perPage', 10);
        $notes = Note::paginate($itemsPerPage);

        $campaigns = Campaign::all();
        $url = Url::all();

        // Pass all necessary data to the view
        return view('admin.leads.show', compact(
            'lead',
            'lead_events',
            'notintrested',
            'parentStages',
            'stages',
            'tags',
            'agencies',
            'user_id',
            'followUps',
            'campaigns',
            'sitevisit',
            'client',
            'leads',
            'note',
            'sitevisits',
            'callRecords',
            'notes',
            'allActivities',
            'noteNotInterested',
            'users',
            'timeSlots',
            'timelineItems',
            'applications',
            'campaignName', // Pass campaign name to the view
            'sourceName' ,   // Pass source name to the view
            'logs'
        ));
    }




    public function destroy(Lead $lead)
    {
        if (!auth()->user()->is_superadmin) {
            abort(403, 'Unauthorized.');
        }
        $lead->delete();

        return back();
    }
    public function massDestroy(MassDestroyLeadRequest $request)
    {
        if (!auth()->user()->is_superadmin) {
            abort(403, 'Unauthorized.');
        }

        $leads = Lead::find(request('ids'));

        foreach ($leads as $lead) {
            $lead->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function getLeadDetailHtml(Request $request)
    {
        if ($request->ajax()) {
            $index = $request->get('index') + 1;
            if (empty($request->get('sub_source_id'))) {
                return view('admin.leads.partials.lead_detail')
                    ->with(compact('index'));
            } else {
                $project = Project::findOrFail($request->get('sub_source_id'));
                $webhook_fields = $project->webhook_fields ?? [];
                return view('admin.leads.partials.lead_detail')
                    ->with(compact('index', 'webhook_fields'));
            }
        }
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

    public function getLeadDetailsRows(Request $request)
    {
        if ($request->ajax()) {
            $lead_details = [];
            $sub_source_id = $request->input('sub_source_id');
            $lead_id = $request->input('lead_id');
            $subsource = SubSource::findOrFail($sub_source_id);
            $webhook_fields = $subsource->webhook_fields ?? [];
            if (!empty($lead_id)) {
                $lead = Lead::findOrFail($lead_id);
                $lead_details = $lead->lead_info;
            }
            $html = View::make('admin.leads.partials.lead_details_rows')
                ->with(compact('webhook_fields', 'lead_details'))
                ->render();

            return [
                'html' => $html,
                'count' => !empty($webhook_fields) ? count($webhook_fields) - 1 : 0,
            ];
        }
    }

    public function sendMassWebhook(Request $request)
    {
        if ($request->ajax()) {
            $lead_ids = $request->input('lead_ids');
            if (!empty($lead_ids)) {
                $response = [];
                foreach ($lead_ids as $id) {
                    $response = $this->util->sendApiWebhook($id);
                }
                return $response;
            }
        }
    }

    public function export(Request $request)
    {
        if (!auth()->user()->is_superadmin) {
            abort(403, 'Unauthorized.');
        }

        return Excel::download(new LeadsExport($request), 'leads.xlsx');
    }

    public function shareDocument(Request $request, $lead_id, $doc_id)
    {
        $lead = Lead::findOrFail($lead_id);
        $document = Document::findOrFail($doc_id);
        $note = $request->input('note');
        try {
            $mails = [];
            if (!empty($lead->email)) {
                $mails[$lead->email] = $lead->father_name ?? $lead->ref_num;
            }

            if (!empty($lead->secondary_email)) {
                $mails[$lead->secondary_email] = $lead->father_name ?? $lead->ref_num;
            }

            if (!empty($mails)) {
                Notification::route('mail', $mails)->notify(new LeadDocumentShare($lead, $document, auth()->user(), $note));
                $this->util->logActivity($lead, 'document_sent', ['sent_by' => auth()->user()->id, 'document_id' => $doc_id, 'status' => 'sent', 'datetime' => Carbon::now()->toDateTimeString(), 'note' => $note]);
            }
            $output = ['success' => true, 'msg' => __('messages.success')];
        } catch (Exception $e) {
            $this->util->logActivity($lead, 'document_sent', ['sent_by' => auth()->user()->id, 'document_id' => $doc_id, 'status' => 'failed', 'datetime' => Carbon::now()->toDateTimeString(), 'note' => $note]);
            $output = ['success' => false, 'msg' => __('messages.something_went_wrong')];
        }
        return $output;
    }
    private function getLeadActivities(Lead $lead)
    {
        $leadActivities = $lead->timeline()->get();

        return collect([])
            ->merge($leadActivities)

            ->sortByDesc('created_at');
    }
    public function getLeads(Request $request)
    {
        $phone = $request->input('phone');
        $email = $request->input('email');

        // Query the database to find leads based on phone or email
        $leads = Lead::where('phone', $phone)->orWhere('email', $email)->get();

        return response()->json($leads);
    }
    public function getCampaignsByProject(Request $request)
    {
        $project = $request->input('project');
        $campaigns = Url::where('project', $project)->distinct()->pluck('campaign_name');

        return response()->json($campaigns);
    }
    public function getSubSources(Request $request)
    {
        // Retrieve all unique sub_source_name values
        $subSources = Url::distinct()->pluck('sub_source_name');

        return response()->json($subSources);
    }
    public function getLeadDetails(Request $request)
    {
        $leadId = $request->input('lead_id');

        $lead = Lead::find($leadId);

        if ($lead) {
            return response()->json([
                'status' => 'success',
                'lead' => $lead
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Lead not found.'
            ], 404);
        }
    }
    public function updateLead(Request $request, $leadId)
    {
        $user = auth()->user(); // Get the authenticated user

        $lead = Lead::find($leadId);

        if (!$lead) {
            return response()->json(['status' => 'error', 'message' => 'Lead not found.'], 404);
        }

        // Update lead details
        $lead->parent_stage_id = $request->input('parent_stage_id');
        $fatherCountryCode = $request->input('country_code');
        $fatherPhone = $request->input('father_details.phone');
        $fullFatherPhoneNumber = $fatherCountryCode . $fatherPhone;

        // Concatenate country code and phone number for mother
        $motherCountryCode = $request->input('mother_country_code');
        $motherPhone = $request->input('mother_details.phone');
        $fullMotherPhoneNumber = $motherCountryCode . $motherPhone;

        // Concatenate country code and phone number for guardian
        $guardianCountryCode = $request->input('guardian_country_code');
        $guardianPhone = $request->input('guardian_details.phone');
        $fullGuardianPhoneNumber = $guardianCountryCode . $guardianPhone;

        // Update father details
        $lead->father_details = [
            'name' => $request->input('father_details.name'),
            'phone' => $fullFatherPhoneNumber,  // Store the full phone number with country code
            'email' => $request->input('father_details.email'),
            'occupation' => $request->input('father_details.occupation'),
            'income' => $request->input('father_details.income'),
        ];

        // Update mother details
        $lead->mother_details = [
            'name' => $request->input('mother_details.name'),
            'phone' => $fullMotherPhoneNumber,  // Store the full phone number with country code
            'email' => $request->input('mother_details.email'),
            'occupation' => $request->input('mother_details.occupation'),
            'income' => $request->input('mother_details.income'),
        ];

        // Update guardian details
        $lead->guardian_details = [
            'name' => $request->input('guardian_details.name'),
            'phone' => $fullGuardianPhoneNumber,  // Store the full phone number with country code
            'email' => $request->input('guardian_details.email'),
            'occupation' => $request->input('guardian_details.occupation'),
            'income' => $request->input('guardian_details.income'),
        ];

        // Update student details
        $lead->student_details = $request->input('student_details'); // Assuming it's an array of students

        // Update WhatsApp number (user_id) with country code

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
        $lead->walkin_no=1;

        $lead->save();
        $this->createUserInExternalApi($request, $lead);

        $params = [
            'father_name' => $lead->father_details['name'] ?? null,
            'father_phone' => $lead->father_details['phone'] ?? null,
            'father_email' => $lead->father_details['email'] ?? null,
            'mother_name' => $lead->mother_details['name'] ?? null,
            'mother_phone' => $lead->mother_details['phone'] ?? null,
            'mother_email' => $lead->mother_details['email'] ?? null,
            // 'relationship' => $relationship,
            'enquiry_number' => $lead->ref_num,
        ];
        $chatRaceId = $lead->user_id;
        // Send flow request to ChatRace API
        $flowResponse = Http::withHeaders([
            'X-Access-Token' => '1025340.dxtJ2Ma7me2STSkWkcF1u7JBICU4RhQ', // Replace with your actual access token
            'Content-Type' => 'application/json',
        ])->post("https://inbox.thebumblebee.in/api/users/$chatRaceId/send/1725964346073", $params);

        // Capture and store the flow response
        $flowSuccess = $flowResponse->successful();

        $lead->update(['flow_response' => $flowSuccess]);
        // $lead->url = "https://portal.qmis.edu.in/client/parent-details/$lead->id";
        $lead->save();

        // Get the maximum id from Application and generate application number
        $maxId = Application::max('id');
        $applicationNo = '20' . str_pad($maxId + 1, 2, '0', STR_PAD_LEFT);

        // Create a new Application
        $application = new Application();
        $application->lead_id = $lead->id;
        $application->application_no = $applicationNo;
        $application->stage_id = 13; // Assuming 13 is the stage you want to set
        $application->save();
        // $this->createUserInExternalApplication($request, $lead, $application);

        // Call the sendApplicationFlow method after creating the user
        // $this->sendApplicationFlow($request, $lead, $application->id);
        // Redirect to the lead details page
        return redirect()->route('admin.leads.show', ['lead' => $lead->id]);
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
    return view(view: 'admin.leads.walkin.form_details', data: compact( 'lead', 'projects', 'campaigns', 'subSources', 'url'));
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
protected function createUserInExternalApi(Request $request, Lead $lead)
    {
        $chatRaceData = [
            'phone' => $lead->father_details['phone'] ?? $lead->mother_details['phone'] ?? $lead->guardian_details['phone'],
            'first_name' => $lead->father_details['name'] ?? $lead->mother_details['name'] ?? $lead->guardian_details['name'],
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
            ['field_name' => 'lead_project', 'value' => $lead->project->name ?? ''],
            ['field_name' => 'Lead_Campaign', 'value' => $lead->common_details['utm_campaign'] ?? ''],
            ['field_name' => 'lead_source', 'value' => $lead->common_details['utm_source'] ?? ''],
            ['field_name' => 'Lead_Sub_source', 'value' => $lead->common_details['sub_source'] ?? ''],
            ['field_name' => 'lead_stage', 'value' => $lead->stage->name ?? ''],
            ['field_name' => 'lead_enquiry_number', 'value' => $lead->ref_num],
            ['field_name' => 'student_relationship', 'value' => !empty($lead->father_details) ? 'father' : (!empty($lead->mother_details) ? 'mother' : '')],
            ['field_name' => 'father_name', 'value' => $lead->father_details['name'] ?? ''],
            ['field_name' => 'mother_name', 'value' => $lead->mother_details['name'] ?? ''],
            ['field_name' => 'father_phone', 'value' => $lead->father_details['phone'] ?? ''],
            ['field_name' => 'mother_phone', 'value' => $lead->mother_details['phone'] ?? ''],
            ['field_name' => 'father_email', 'value' => $lead->father_details['email'] ?? ''],
            ['field_name' => 'mother_email', 'value' => $lead->mother_details['email'] ?? ''],
            ['field_name' => 'lead_browser', 'value' => $lead->common_details['browser'] ?? ''],
            ['field_name' => 'lead_city', 'value' => $lead->common_details['user_city'] ?? ''],
            ['field_name' => 'lead_device', 'value' => $lead->common_details['user_device'] ?? ''],
            ['field_name' => 'lead_ip', 'value' => $lead->common_details['user_ip'] ?? ''],
            ['field_name' => 'lead_page_url', 'value' => $lead->common_details['landing_page'] ?? ''],
            ['field_name' => 'lead_referral_page_url', 'value' => $lead->common_details['org_ref'] ?? ''],
            ['field_name' => 'lead_traffic_source', 'value' => $lead->common_details['traffic_src'] ?? ''],
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
        } else {
            \Log::error('Failed to create user via external API: ' . $response->body());
            throw new Exception('Failed to create user via external API.');
        }
    }

    public function razorLog(Request $request) {
        $logs = RazorLog::get();
        return view('admin.razor.index', compact('logs'));
    }

    public function emailLog(Request $request) {
        $logs = EmailLog::get();
        return view('admin.email.index', compact('logs'));
    }

}
