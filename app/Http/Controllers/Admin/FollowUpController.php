<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFollowupRequest;
use App\Models\Campaign;
use App\Models\Lead;
use App\Models\LeadTimeline;
use App\Models\Project;
use App\Models\Source;
use App\Models\User;
use App\Models\Followup;
use App\Models\ParentStage;

use App\Utils\Util;
use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;

class FollowUpController extends Controller
{

    protected $util;
    public function __construct(Util $util)
    {
        $this->util = $util;
    }

    public function index(Request $request)
    {
        $lead = Lead::all();
        $agencies = User::all();
        $followUps = Followup::all();
        // $itemsPerPage = request('perPage', 1);
        // $followUps = Followup::paginate($itemsPerPage);
        // dd($lead);
        $parentstages = ParentStage::pluck('name', 'id');
        return view('admin.leads.followup.index', compact( 'agencies', 'lead', 'followUps','parentstages'));
    }
    public function store(StoreFollowupRequest $request)
    {
        $input = $request->validated();
        $lead = Lead::findOrFail($input['lead_id']);
        if ($lead) {
            $parentStageId = $request->input('parent_stage_id');
            $followup = new Followup();
            $followup->lead_id = $lead->id;
            $followup->followup_date = $input['followup_date'];
            $followup->followup_time = $input['followup_time'];
            $followup->notes = $input['notes'];
            $followup->created_by = auth()->user()->id;
            $followup->parent_stage_id = $parentStageId;
            $followup->save();
            if ($followup->lead) {
                $followup->lead->update(['parent_stage_id' => $followup->parent_stage_id]);
            }
            $this->logTimeline($lead, $followup, 'Stage Changed', "Stage was updated to {$parentStageId}");
            return redirect()->back()->with('success', 'Form submitted successfully!');
        } else {
            return redirect()->back()->with('error', 'Lead not found!');
        }
    }

    public function destroy(Followup $followup)
    {
        abort_if(!auth()->user()->is_superadmin, Response::HTTP_FORBIDDEN, '403 Forbidden');

        $followup->delete();

        return back();
    }
    public function logTimeline($lead, $followup, $activityType, $description)
{
    $timeline = new LeadTimeline();
    $timeline->lead_id = $lead->id;
    $timeline->activity_type = $activityType;
    $payload = [
        'lead' => $lead->toArray(),
        'enquiryfollowup' => $followup->toArray()
    ];
    $timeline->payload = json_encode($payload); // Convert array to JSON
    $timeline->description = $description;

    $timeline->created_at = now();
    $timeline->save();
}
}
