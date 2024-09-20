<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Lost;
use App\Models\Note;
use App\Models\LeadTimeline;
use Illuminate\Http\Request;

class LostController extends Controller
{
    public function store(Request $request)
    {
        $lead = Lead::find($request->lead_id);
        if ($lead) {
            $parentStageId = $request->input('tag_id');
            $notintrested = new Lost();
            $notintrested->lead_id = $lead->id;
            $notintrested->reason = $request->input('reason');
            $notintrested->notes = $request->input('notes');
            $notintrested->tag_id = $request->input('tag_id');
            $notintrested->save();

            $lead->parent_stage_id = $parentStageId;
            $lead->save();

            $note = new Note();
            $note->lead_id = $lead->id;
            $note->note_text = $notintrested->reason;
            $note->save();

            // Call the logTimeline method directly
            $this->logTimeline($lead, $note, 'Stage Changed', "Stage was updated to {$parentStageId}");

            return redirect()->back()->with('success', 'Form submitted successfully!');
        } else {
            return redirect()->back()->with('error', 'Lead not found!');
        }
    }

    protected function logTimeline(Lead $lead, Note $note, string $activityType, string $description)
    {
        $timeline = new LeadTimeline();
        $timeline->lead_id = $lead->id;
        $timeline->activity_type = $activityType;
        $payload = [
            'lead' => $lead->toArray(),
            'notes' => $note->toArray()
        ];
        $timeline->payload = json_encode($payload);
        $timeline->description = $description;
        $timeline->created_at = now();
        $timeline->save();
    }
}
