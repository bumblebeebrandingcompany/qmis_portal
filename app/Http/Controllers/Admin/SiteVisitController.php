<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SiteVisitRequest;
use App\Models\Campaign;
use App\Models\Client;
use App\Models\Lead;
use App\Models\SiteVisit;
use App\Models\User;
use App\Utils\Util;
use Illuminate\Http\Request;

class SiteVisitController extends Controller
{
    /**
     * All Utils instance.
     *
     */
    public function __construct(Util $util)
    {
        $this->util = $util;
    }
    public function index(Request $request)
    {
        $lead = Lead::all();
        $client = Client::all();
        $agencies = User::all();
        $campaigns = Campaign::all();
        $sitevisits = SiteVisit::all(); // Fixed variable name to $sitevisits
        $ids = $sitevisits->pluck('id'); // Get an array of all sitevisit IDs
        // Alternatively, if you want to get the IDs in a loop
        $ids = [];
        foreach ($sitevisits as $sitevisit) {
            $ids[] = $sitevisit->id;
        }
        return view('admin.sitevisit.index', compact('campaigns', 'agencies', 'lead', 'sitevisits', 'ids', 'client'));
    }

    public function store(SiteVisitRequest $request)
    {
        $input = $request->validated(); // Use the validated input from the request
        // Find the lead and user based on their IDs
        $lead = Lead::findOrFail($input['lead_id']);
        // $user = User::findOrFail($input['user_id']);
        $sitevisit = new SiteVisit();
        $sitevisit->lead_id = $lead->id;
        $sitevisit->user_id = $input['user_id'];
        $sitevisit->follow_up_date = $input['follow_up_date']; // Assign the date
        $sitevisit->follow_up_time = $input['follow_up_time']; // Assign the time
        $sitevisit->notes = $input['notes'];
        $sitevisit->save();
        return redirect()->back()->with('success', 'Form submitted successfully!');

    }

    // app/Http/Controllers/SiteVisitController.php

    public function reschedule(Request $request, $id)
    {
        $request->validate([
            'follow_up_date' => 'date',
            'follow_up_time' => 'date_format:H:i:s',
            'deleted_at' => 'date',
            'lead_id' => [
                'required',
                'integer',
            ],
            'user_id' => [
                // Add your validation rules for user_id if needed
            ],
            'notes' => 'nullable|string',
        ]);

        // Find the original SiteVisit record by ID
        $originalSiteVisit = SiteVisit::find($id);
        if (!$originalSiteVisit) {
            return redirect()->back()->with('error', 'Site visit not found.');
        }

        // Create a new SiteVisit
        $newSiteVisit = new SiteVisit();
        $newSiteVisit->follow_up_date = $request->follow_up_date;
        $newSiteVisit->follow_up_time = $request->follow_up_time;
        $newSiteVisit->lead_id = $request->lead_id;
        $newSiteVisit->user_id = $request->user_id;
        $newSiteVisit->notes = $request->notes;
        $newSiteVisit->save();

        // Update the lead status to "rescheduled"
        $lead = Lead::find($request->lead_id);
        if ($lead) {
            $lead->update(['stage_id' => 10]);
        }

        // List old schedules
        $oldSchedules = SiteVisit::where('lead_id', $originalSiteVisit->lead_id)
            ->where('id', '!=', $id)
            ->get();

        return redirect()->back()->with('success', 'Site visit rescheduled successfully.');
    }

}
