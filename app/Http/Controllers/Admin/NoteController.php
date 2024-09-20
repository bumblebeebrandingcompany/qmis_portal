<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Lead;
use App\Models\LeadTimeline;
use App\Models\Note;
use App\Utils\Util;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    protected $util;

    /**
     * NoteController constructor.
     *
     * @param Util $util
     */
    public function __construct(Util $util)
    {
        $this->util = $util;
    }

    /**
     * Display a listing of notes.
     */
    public function index()
    {
        $itemsPerPage = request('perPage', 10);
        $notes = Note::paginate($itemsPerPage);
        $leads = Lead::all();
        $campaigns = Campaign::all();
        return view('admin.leads.partials.notes', compact('notes', 'leads', 'campaigns'));
    }

    /**
     * Store a newly created note in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $lead = Lead::findOrFail($input['lead_id']);

        if ($lead) {
            // Retrieve the stage_id correctly
            $stageId = $request->input('stage_id');

            // Create a new Note
            $note = new Note();
            $note->lead_id = $lead->id;
            $note->note_text = $input['note_text'];
            $note->stage_id = $stageId;
            $note->save();

            // Update the lead's parent_stage_id if a new stage is provided
            if ($stageId !== null && $lead->parent_stage_id !== $stageId) {
                $lead->update(['parent_stage_id' => $stageId]);

                // Log the stage change event
                $this->logTimeline($lead, $note, 'Stage Changed', "Stage was updated to {$stageId}");
            } else {
                // Log the note addition event
                $this->logTimeline($lead, $note, 'note_added', 'Note added');
            }

            return redirect()->back()->with('success', 'Form submitted successfully!');
        } else {
            return redirect()->back()->with('error', 'Lead not found!');
        }
    }

    /**
     * Log an event to the lead's timeline.
     *
     * @param Lead $lead
     * @param Note $note
     * @param string $activityType
     * @param string $description
     */
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
