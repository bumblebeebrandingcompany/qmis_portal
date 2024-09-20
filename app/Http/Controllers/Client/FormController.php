<?php

namespace App\Http\Controllers\Client;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lead;
use Illuminate\Validation\ValidationException;
use App\Models\LeadTimeline;

use Exception;

class FormController extends Controller
{


    public function parentDetails($id)
    {

        $lead = Lead::findOrFail($id);
        return response()->view('client.parent_details_form', compact('lead'))->header('Cache-Control', 'no-store, no-cache, must-revalidate')
        ->header('Pragma', 'no-cache')
        ->header('Expires', '0');
    }

    public function studentDetails($id)
    {

        $lead = Lead::findOrFail($id);
        return view('client.student_details_form', compact('lead'));
    }
    public function updateParentDetails(Request $request, $id)
    {
        $lead = Lead::findOrFail($id);

        $request->validate([
            'father.name' => 'nullable|string',
            'father.phone' => 'nullable|digits:10',
            'father.email' => 'nullable|email',
            'father.occupation' => 'nullable|string',
            'father.income' => 'nullable|string',
            'mother.name' => 'nullable|string',
            'mother.phone' => 'nullable|digits:10',
            'mother.email' => 'nullable|email',
            'mother.occupation' => 'nullable|string',
            'mother.income' => 'nullable|string',
            'guardian.name' => 'nullable|string',
            'guardian.relationship' => 'nullable|string',
            'guardian.phone' => 'nullable|digits:10',
            'guardian.email' => 'nullable|email',
            'guardian.occupation' => 'nullable|string',
            'guardian.income' => 'nullable|string',
        ]);

        $lead->father_details = [
            'name' => $request->input('father.name'),
            'phone' => $request->input('father.phone'),
            'email' => $request->input('father.email'),
            'occupation' => $request->input('father.occupation'),
            'income' => $request->input('father.income'),
        ];

        $lead->mother_details = [
            'name' => $request->input('mother.name'),
            'phone' => $request->input('mother.phone'),
            'email' => $request->input('mother.email'),
            'occupation' => $request->input('mother.occupation'),
            'income' => $request->input('mother.income'),
        ];

        $lead->guardian_details = [
            'name' => $request->input('guardian.name'),
            'relationship' => $request->input('guardian.relationship'),
            'phone' => $request->input('guardian.phone'),
            'email' => $request->input('guardian.email'),
            'occupation' => $request->input('guardian.occupation'),
            'income' => $request->input('guardian.income'),
        ];
        $lead->url="https://portal.qmis.edu.in/client/student-details/$lead->id";

        $lead->save();

        return redirect()->route('client.studentDetails', ['id' => $id])->with('success', 'Details updated successfully!');
    }

    protected function validateAndCheckDuplicates(array $validatedData, $leadId)
    {
        $phones = [
            $validatedData['father']['phone'] ?? null,
            $validatedData['mother']['phone'] ?? null,
            $validatedData['guardian']['phone'] ?? null,
        ];

        $emails = [
            $validatedData['father']['email'] ?? null,
            $validatedData['mother']['email'] ?? null,
            $validatedData['guardian']['email'] ?? null,
        ];

        $existingLeadPhone = Lead::where(function ($query) use ($phones) {
            foreach ($phones as $phone) {
                if ($phone) {
                    $query->orWhere('father_details->phone', $phone)
                        ->orWhere('mother_details->phone', $phone)
                        ->orWhere('guardian_details->phone', $phone);
                }
            }
        })->where('id', '!=', $leadId)->exists();

        if ($existingLeadPhone) {
            throw new Exception('One or more phone numbers are already in use.');
        }

        $existingLeadEmail = Lead::where(function ($query) use ($emails) {
            foreach ($emails as $email) {
                if ($email) {
                    $query->orWhere('father_details->email', $email)
                        ->orWhere('mother_details->email', $email)
                        ->orWhere('guardian_details->email', $email);
                }
            }
        })->where('id', '!=', $leadId)->exists();

        if ($existingLeadEmail) {
            throw new Exception('One or more email addresses are already in use.');
        }
    }



    public function updateStudentDetails(Request $request, $id)
    {
        $validator = $request->validate([
            'student_details.*.name' => 'required|string',
            'student_details.*.dob' => 'required|date',
            'student_details.*.grade' => 'required|string',
            'student_details.*.old_school' => 'nullable|string',
            'student_details.*.reason_for_quit' => 'nullable|string',
        ]);
        $studentDetails = $request->input('student_details');
        $lead = Lead::findOrFail($id);
        $lead->student_details = $studentDetails;
        $lead->parent_stage_id = 9;
        $lead->url="https://portal.qmis.edu.in/client/onboard/$lead->id";
        $lead->save();
        $this->logTimeline($lead, 'Stage Changed', "Stage was updated to {$lead->parent_stage_id}");

        $grades = [
            '1',
            '2',
            '3',
            '4',
            '5',
            '6',
            '7',
            '8',
            '9',
            '10',
            '11',
            '12',
        ];
        if (in_array($studentDetails[0]['grade'], $grades)) {
            return redirect()->route('client.success_g', ['id' => $lead->id])->with('success', 'Details updated successfully!');
        }
        return redirect()->route('client.onboard', ['id' => $lead->id])->with('success', 'Details updated successfully!');
    }

    public function structure_detail($type) {
        return view('client.structure_detail', compact('type'));
    }
    public function onboard($id)
    {
        $lead = Lead::findOrFail($id);
        return response()->view('client.onboard', compact('lead'))->header('Cache-Control', 'no-store, no-cache, must-revalidate')
        ->header('Pragma', 'no-cache')
        ->header('Expires', '0');
    }
    public function academic($id)
    {
        $lead = Lead::findOrFail($id);
        return view('client.academic', compact('lead'));
    }
    public function school($id)
    {
        $lead = Lead::findOrFail($id);
        return view('client.school', compact('lead'));
    }
    public function program($id)
    {
        $lead = Lead::findOrFail($id);
        return view('client.program', compact('lead'));
    }

    public function play($id)
    {
        $lead = Lead::findOrFail($id);
        return view('client.play', compact('lead'));
    }
    public function oneStep($id)
    {
        $lead = Lead::findOrFail($id);
        return view('client.one_step', compact('lead'));
    }
    public function structure($id)
    {
        $lead = Lead::findOrFail($id);
        return view('client.structure', compact('lead'));
    }
    public function accreditions($id)
    {
        $lead = Lead::findOrFail($id);
        return view('client.accreditions', compact('lead'));
    }
    public function initiatives($id)
    {
        $lead = Lead::findOrFail($id);
        return view('client.initiatives', compact('lead'));
    }
    public function qmisLearning($id)
    {
        $lead = Lead::findOrFail($id);
        return view('client.qmis_learning', compact('lead'));
    }
    public function earlyChild($id)
    {
        $lead = Lead::findOrFail($id);
        return view('client.early_child', compact('lead'));
    }
    public function eligiblity_criteria($id)
    {
        $lead = Lead::findOrFail($id);
        return view('client.eligiblity_criteria', compact('lead'));
    }
    public function admission_process($id)
    {
        $lead = Lead::findOrFail($id);
        return view('client.admission_process', compact('lead'));
    }
    public function kg_program($id)
    {
        $lead = Lead::findOrFail($id);
        return view('client.kg_program', compact('lead'));
    }
    public function global_standards($id)
    {
        $lead = Lead::findOrFail($id);
        return view('client.global_standards', compact('lead'));
    }
    public function logTimeline($lead, $activityType, $description)
    {
        $timeline = new LeadTimeline();
        $timeline->lead_id = $lead->id;
        $timeline->activity_type = $activityType;
        $payload = [
            'lead' => $lead->toArray(),
            'qualified' => $lead->toArray()
        ];
        $timeline->payload = json_encode($payload); // Convert array to JSON
        $timeline->description = $description;
    
        $timeline->created_at = now();
        $timeline->save();
    }
}
