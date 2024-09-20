<?php

namespace App\Http\Controllers\Admin;

use App\Exports\LeadsExport;
use App\Http\Controllers\Controller;



use App\Http\Requests\StoreFollowupRequest;
use App\Http\Requests\UpdateLeadRequest;
use App\Models\Application;
use App\Models\Campaign;
use App\Models\Document;
use App\Models\Lead;
use App\Models\LeadEvents;
use App\Models\LeadTimeline;
use App\Models\Project;
use App\Models\Source;
use App\Models\User;
use App\Models\Followup;
use App\Utils\Util;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use DateTimeInterface;

class AdmissionFollowUpController extends Controller
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
        $application = Application::all();
        $followUps = Followup::with('application')->get();
        return view('admin.admissionfollowup.index', compact('agencies', 'lead', 'followUps', 'application'));
    }
    public function store(StoreFollowupRequest $request)
    {
        $input = $request->validated();

        // Find the lead based on its ID
        $lead = Lead::findOrFail($input['lead_id']);

        // Check if the lead is not null before proceeding
        if ($lead) {
            $parentStageId = $request->input('parent_stage_id');

            $followup = new Followup();
            $followup->lead_id = $lead->id;
            // $followup->user_id = $input['user_id'];
            $followup->followup_date = $input['followup_date'];
            $followup->followup_time = $input['followup_time'];
            $followup->notes = $input['notes'];
            $followup->parent_stage_id = $parentStageId;
            $followup->save();

            // Check if $followup->lead is not null before updating
            if ($followup->lead) {
                $followup->lead->update(['parent_stage_id' => $followup->parent_stage_id]);
            }
            // $this->logTimeline($lead, $followup, 'Stage Changed', "Stage was updated to {$parentStageId}");
            return redirect()->back()->with('success', 'Form submitted successfully!');
        } else {
            // Handle the case where the lead is not found
            return redirect()->back()->with('error', 'Lead not found!');
        }
    }

    public function destroy(Followup $followup)
    {
        abort_if(!auth()->user()->is_superadmin, Response::HTTP_FORBIDDEN, '403 Forbidden');

        $followup->delete();

        return back();
    }
//     public function logTimeline($lead, $followup, $activityType, $description)
// {
//     $timeline = new LeadTimeline();
//     $timeline->lead_id = $lead->id;
//     $timeline->activity_type = $activityType;
//     $payload = [
//         'lead' => $lead->toArray(),
//         'followup' => $followup->toArray()
//     ];
//     $timeline->payload = json_encode($payload); // Convert array to JSON
//     $timeline->description = $description;

//     $timeline->created_at = now();
//     $timeline->save();
// }
}
