<?php

namespace App\Http\Controllers\Admin;

use App\Models\Lead;
use App\Models\Srd;
use App\Models\Url;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Utils\Util;
use Illuminate\Support\Facades\Log;


class UrlController extends Controller
{
    protected $util;

    public function __construct(Util $util)
    {
        $this->util = $util;
    }

    public function index()
    {
        // $urls = Url::with('project')->get();
        $srds = Url::with('project')->get();
        return view('admin.urls.index', compact('srds'));
    }

    public function create()
    {
        // Fetch distinct project IDs with project names
        $projects = Url::with('project')
            ->select('project_id')
            ->distinct()
            ->get()
            ->map(function ($srd) {
                // Assuming project relationship is properly defined and eager-loaded
                return [
                    'project_id' => $srd->project_id,
                    'project_name' => $srd->project->name
                ];
            })
            ->unique('project_id'); // Ensure unique project_id

        return view('admin.urls.create', [
            'projects' => $projects
        ]);
    }



    // YourController.php
    public function getCampaignsAndSources(Request $request, $projectId)
    {
        // Fetch all campaigns for the given project
        $srds = Url::where('project_id', $projectId)->get();

        // Filter campaigns
        $campaigns = $srds->pluck('campaign')->unique()->values();

        $campaignFilter = $request->input('campaign');
        if ($campaignFilter) {
            $sources = $srds->where('campaign', $campaignFilter)
                ->pluck('source')
                ->unique()
                ->values();
        } else {
            $sources = $srds->pluck('source')->unique()->values();
        }

        return response()->json([
            'campaigns' => $campaigns->mapWithKeys(fn($name) => [$name => $name]),
            'sources' => $sources->mapWithKeys(fn($name) => [$name => $name]),
        ]);
    }

    public function store(Request $request)
{
    $validatedData = $request->validate([
        // 'project_id' => 'required|integer',
        'campaign_name' => 'required|string',
        'source_name' => 'required|string',
        'sub_sources' => 'nullable|array',
        'sub_sources.*' => 'nullable|string',
        'sell_do_sub_source' => 'nullable|array',
        'sell_do_sub_source.*' => 'nullable|string',
        'srd_id' => 'required|integer',
        // 'srd' => 'required|string'
    ]);

    $srd = Url::findOrFail($validatedData['srd_id']);
    $existingSubSources = json_decode($srd->sub_source_name, true) ?? [];

    // Get new sub-sources and additional info
    $newSubSources = $validatedData['sub_sources'] ?? [];
    $additionalInfo = $validatedData['sell_do_sub_source'] ?? [];

    // Merge sub-sources and additional info into a single array
    $mergedSubSources = [];
    foreach ($newSubSources as $index => $subSource) {
        $mergedSubSources[] = [
            'sub_source_name' => $subSource,
            'sell_do_sub_source' => $additionalInfo[$index] ?? '', // Use empty string if additional info is not provided
            'webhook_secret' => null // Placeholder for webhook secret
        ];
    }

    // Merge with existing sub-sources, ensuring uniqueness
    $mergedSubSources = array_merge($existingSubSources, $mergedSubSources);
    $mergedSubSources = array_map('unserialize', array_unique(array_map('serialize', $mergedSubSources)));

    // Update the `sub_source_name` field in the Srd model
    $srd->sub_source_name = json_encode($mergedSubSources);
    $srd->save();

    return redirect()->route('admin.urls.index')->with('success', 'URLs and sub-sources updated successfully!');
}


    public function edit(Url $url)
    {
        $srds = Url::all();
        return view('admin.urls.edit', compact('url', 'srds'));
    }

    public function generateUrl(Request $request, $id)
    {
        $srd = Url::findOrFail($id);
        $subSourceName = $request->input('sub_source_name');
        $webhookSecret = $this->util->generateWebhookSecret();

        $subSources = json_decode($srd->sub_source_name, true) ?? [];

        foreach ($subSources as &$subSource) {
            if ($subSource['sub_source_name'] === $subSourceName) {
                $subSource['webhook_secret'] = $webhookSecret;
                break;
            }
        }

        $srd->sub_source_name = json_encode($subSources);
        $srd->save();

        return redirect()->route('admin.urls.index')->with('success', 'Webhook secret generated successfully.');
    }




    public function update(Request $request, Url $url)
    {
        $validatedData = $request->validate([
            'project_id' => 'required|integer',
            'campaign_name' => 'required|string',
            'source_name' => 'required|string',
            'sub_source_name' => 'nullable|string',
        ]);

        $url->update([
            'project_id' => $validatedData['project_id'],
            'campaign_name' => $validatedData['campaign_name'],
            'source_name' => $validatedData['source_name'],
            'sub_source_name' => $validatedData['sub_source_name'],
        ]);

        return redirect()->route('admin.urls.index')->with('success', 'URL updated successfully!');
    }
    public function getCampaignsAndSource($url_id, $project_id)
    {
        \Log::info('getCampaignsAndSources called with project_id: ' . $project_id);

        // Find the URL model by URL ID if needed
        $url = Url::find($url_id);
        if (!$url) {
            return response()->json(['error' => 'URL not found'], 404);
        }

        // Fetch the SRDs based on project_id
        $srds = Url::where('project_id', $project_id)->get();

        $campaigns = $srds->pluck('campaign')->unique()->values();
        $sources = $srds->pluck('source')->unique()->values();

        return response()->json([
            'campaigns' => $campaigns->mapWithKeys(fn($name) => [$name => $name]),
            'sources' => $sources->mapWithKeys(fn($name) => [$name => $name]),
        ]);
    }

    public function destroy(Url $url)
    {
        $url->delete();
        return redirect()->route('admin.urls.index')->with('success', 'URL deleted successfully.');
    }

    public function getWebhookDetails($srd_id, Request $request, $sub_source_name)
    {

        $srd = Url::with(['project'])->findOrFail($srd_id);

        $lead = Lead::where('sub_source_name', $sub_source_name)->latest()->first();

        $webhook_secret = $request->input('secret');
        $webhook_key = $request->get('webhook_key');
        $rb_key = $request->get('rb_key');

        $lead_info = $lead ? $lead->lead_details : [];
        $existing_keys = $lead ? $this->util->getNestedKeys($lead_info) : [];

        $payload = $lead && $lead->payload ? (is_string($lead->payload) ? json_decode($lead->payload, true) : $lead->payload) : [];

        if (!is_array($payload) && !is_object($payload)) {
            $payload = [];
        }

        return view('admin.urls.webhook', compact('srd', 'lead', 'webhook_key', 'rb_key', 'existing_keys', 'sub_source_name', 'webhook_secret', 'payload'));
    }

public function updatePhoneAndEmailKey(Request $request)
{
    $url = Url::findOrFail($request->input('sub_source_id'));
    $mappedFields = $request->input('mapped_fields', []);

    $url->mapped_fields = json_encode($mappedFields);
    $url->save();

    $sub_source_name = $request->input('sub_source_name'); // Ensure this value is available
    $webhook_secret = $request->input('webhook_secret'); // Replace this with the actual value

    return redirect()->route('admin.urls.index', [
        'url_id' => $url->id,
        'sub_source_name' => $sub_source_name,
        'webhook_secret' => $webhook_secret
    ]);
}
}
