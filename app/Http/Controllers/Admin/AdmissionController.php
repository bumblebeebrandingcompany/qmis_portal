<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Application;
use App\Models\Admission;
use App\Models\LeadTimeline;
use App\Utils\Util;
use Illuminate\Http\Request;



class AdmissionController extends Controller
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
        $admissions=Admission::all();
        $application=Application::all();
        return view('admin.admission.index', compact( 'lead','admissions','application',));
    }
    public function store(Request $request)
    {
        $lead = Lead::find($request->lead_id);

        if ($lead) {
            $parentStageId = $request->input('stage_id');
            $admission = new Admission();
            $admission->lead_id = $lead->id;
            $admission->application_id = $lead->application->id;
            $admission->notes = $request->input('notes');
            $admission->stage_id = $parentStageId;
            $admission->save();
            // Update the lead's parent_stage_id directly after saving the admission
            $lead->update(['parent_stage_id' => $parentStageId]);
            $this->logTimeline($lead, $admission, 'Stage Changed', "Stage was updated to {$parentStageId}");

            return redirect()->back()->with('success', 'Form submitted successfully!');
        } else {
            return redirect()->back()->with('error', 'Lead not found!');
        }
    }
    public function logTimeline($lead, $admission, $activityType, $description)
{
    $timeline = new LeadTimeline();
    $timeline->lead_id = $lead->id;
    $timeline->activity_type = $activityType;
    $payload = [
        'lead' => $lead->toArray(),
        'admission' => $admission->toArray()
    ];
    $timeline->payload = json_encode($payload); // Convert array to JSON
    $timeline->description = $description;

    $timeline->created_at = now();
    $timeline->save();
}

}
