<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Session;
use App\Utils\Util;
use App\Models\LeadTimeline;

use Exception;

class EnquiryController extends Controller
{

    protected $util;
    public function __construct(Util $util)
    {
        $this->util = $util;
    }
    public function enquiry()
    {
    
        $lead = new Lead;

        return view('client.enquiry_form', compact('lead'));
    }

    public function storeEnquiry(Request $request)
    {
        try {
            // Validate and check for duplicates
            $this->validateAndCheckDuplicates($request);

            // Create lead and send OTP
            $response = $this->createLeadAndSendOtp($request);

            return $response;
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'message' => 'Validation error.', 'errors' => $e->errors()], 422);
        } catch (Exception $e) {
            // dd($e->getMessage());
            \Log::error('An unexpected error occurred: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Already Number or Email Registered.'], 500);
        }
    }

    protected function validateAndCheckDuplicates(Request $request)
    {
        // Define validation rules
        $validationRules = [
            'father.name' => 'nullable|string',
            'father.phone' => 'nullable|digits:10',
            'father.email' => 'nullable|email',
            'mother.name' => 'nullable|string',
            'mother.phone' => 'nullable|digits:10',
            'mother.email' => 'nullable|email',
            'guardian.name' => 'nullable|string',
            'guardian.relationship' => 'nullable|string',
            'guardian.phone' => 'nullable|digits:10',
            'guardian.email' => 'nullable|email',
        ];

        // Validate the request data
        $validatedData = $request->validate($validationRules);

        $fatherPhone = $validatedData['father']['phone'] ?? null;
        $motherPhone = $validatedData['mother']['phone'] ?? null;
        $guardianPhone = $validatedData['guardian']['phone'] ?? null;

        $existingLead = Lead::where(function ($query) use ($fatherPhone, $motherPhone, $guardianPhone) {
            if ($fatherPhone) {
                $query->where('father_details->phone', $fatherPhone)
                    ->orWhere('mother_details->phone', $fatherPhone)
                    ->orWhere('guardian_details->phone', $fatherPhone);
            }
            if ($motherPhone) {
                $query->where('father_details->phone', $motherPhone)
                    ->orWhere('mother_details->phone', $motherPhone)
                    ->orWhere('guardian_details->phone', $motherPhone);
            }
            if ($guardianPhone) {
                $query->where('father_details->phone', $guardianPhone)
                    ->orWhere('mother_details->phone', $guardianPhone)
                    ->orWhere('guardian_details->phone', $guardianPhone);
            }
        })->exists();


        if ($existingLead) {
            throw new Exception('One or more phone numbers are already in use.');
        }

        $fatherEmail = $validatedData['father']['email'] ?? null;
        $motherEmail = $validatedData['mother']['email'] ?? null;
        $guardianEmail = $validatedData['guardian']['email'] ?? null;

        $existingEmail = Lead::where(function ($query) use ($fatherEmail, $motherEmail, $guardianEmail) {
            if ($fatherEmail) {
                $query->where('father_details->email', $fatherEmail)
                    ->orWhere('mother_details->email', $fatherEmail)
                    ->orWhere('guardian_details->email', $fatherEmail);
            }
            if ($motherEmail) {
                $query->where('father_details->email', $motherEmail)
                    ->orWhere('mother_details->email', $motherEmail)
                    ->orWhere('guardian_details->email', $motherEmail);
            }
            if ($guardianEmail) {
                $query->where('father_details->email', $guardianEmail)
                    ->orWhere('mother_details->email', $guardianEmail)
                    ->orWhere('guardian_details->email', $guardianEmail);
            }
        })->exists();

        if ($existingEmail) {
            throw new Exception('Email addresses are already in use.');
        }
    }
    public function resendOtp(Request $request, $id)
    {
        // Assuming you have a Lead model and you can identify the lead by its ID
        $lead = Lead::find($id);

        if ($lead) {
            $otp = rand(1000, 9999);
            $lead->otp = Hash::make($otp);
            $lead->save();

            $fatherPhone = $lead['father_details']['phone'] ?? null;
            $motherPhone = $lead['mother_details']['phone'] ?? null;
            $guardianPhone = $lead['guardian_details']['phone'] ?? null;

            $phoneToSendOtp = $fatherPhone ?? $motherPhone ?? $guardianPhone;
            $response = Http::post('https://www.smsalert.co.in/api/push.json', [
                'apikey' => '654dfc01824b7',
                'sender' => 'TBBBC',
                'mobileno' => $phoneToSendOtp,
                'text' => "One time password: $otp is your verification code. Please enter this to complete your submission. Powered by The Bumblebee Branding Company",
            ]);


            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Lead not found.']);
    }
    protected function createLeadAndSendOtp(Request $request)
    {
        // Extract validated data
        $validatedData = $request->all();
        $fatherPhone = $validatedData['father']['phone'] ?? null;
        $motherPhone = $validatedData['mother']['phone'] ?? null;
        $guardianPhone = $validatedData['guardian']['phone'] ?? null;

        // Create a new lead
        $lead = new Lead;
        $lead->father_details = $validatedData['father'];
        $lead->mother_details = $validatedData['mother'];
        $lead->guardian_details = $validatedData['guardian'];

        // Generate and store OTPs
        $otp = rand(1000, 9999);
        // $otp =1234;
        $lead->otp = Hash::make($otp);
        $email_otp = rand(1000, 9999);
        // $email_otp =1234;
        $lead->email_otp = Hash::make($email_otp);
        $lead->project_id = 1;
        $lead->ip_address = $request->ip_address ?? $request->ip();
        $lead->campaign_id = $request->campaign;
        // $lead->source = $request->source;
        $lead->sub_source_name = $request->sub_source_name;
        $lead->parent_stage_id = 8;
        $additional_details["city"] = $request->city;
        $additional_details["campaign"] = $request->campaign;
        $additional_details["source"] = $request->source;
        $additional_details["browser"] = $request->browser;
        $additional_details["user_os"] = $request->user_os;
        $additional_details["sub_no"] = $request->sub_no;
        $additional_details["date_time"] = $request->date_time;
        $additional_details["landing_page"] = $request->landing_page;
        $additional_details["ref"] = $request->ref;
        $additional_details["traffic_src"] = $request->traffic_src;
        $lead->additional_details = json_encode($additional_details);
        $lead->save();
        $lead->ref_num = 1000 + $lead->id;
        $lead->save();
        $this->logTimeline($lead, 'lead_created', 'Lead Created Successfully');

        $this->util->sendGroupMsg($lead);
        // Send OTP via SMS
        $phoneToSendOtp = $fatherPhone ?? $motherPhone ?? $guardianPhone;
        $response = Http::post('https://www.smsalert.co.in/api/push.json', [
            'apikey' => '654dfc01824b7',
            'sender' => 'TBBBC',
            'mobileno' => $phoneToSendOtp,
            'text' => "One time password: $otp is your verification code. Please enter this to complete your submission. Powered by The Bumblebee Branding Company",
        ]);

        if ($response->failed()) {
            throw new Exception('Failed to send OTP. Please try again later.');
        }
        $this->createUserInExternalApi($request, $lead);

        try {
            $this->util->sendOtpToEmail($request, $lead->id, $email_otp);
        } catch (Exception $e) {
            // dd($e->getMessage());
            \Log::error('Failed to send client portal link: ' . $e->getMessage());
            throw new Exception('Lead created, but failed to send the client portal link.');
        }

        return response()->json(['success' => true, 'message' => 'OTP sent successfully. Please verify.', 'lead_id' => $lead->id]);
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
            ['field_name' => 'Lead_Campaign', 'value' => $lead->additional_details['campaign'] ?? ''],
            ['field_name' => 'lead_source', 'value' => $lead->additional_details['source'] ?? ''],
            ['field_name' => 'Lead_Sub_source', 'value' => $lead->additional_details['sub_source'] ?? ''],
            ['field_name' => 'lead_stage', 'value' => $lead->parentStage->name ?? ''],
            ['field_name' => 'lead_enquiry_number', 'value' => $lead->ref_num],
            ['field_name' => 'student_relationship', 'value' => !empty($lead->father_details) ? 'father' : (!empty($lead->mother_details) ? 'mother' : '')],
            ['field_name' => 'father_name', 'value' => $lead->father_details['name'] ?? ''],
            ['field_name' => 'mother_name', 'value' => $lead->mother_details['name'] ?? ''],
            ['field_name' => 'father_phone', 'value' => $lead->father_details['phone'] ?? ''],
            ['field_name' => 'mother_phone', 'value' => $lead->mother_details['phone'] ?? ''],
            ['field_name' => 'father_email', 'value' => $lead->father_details['email'] ?? ''],
            ['field_name' => 'mother_email', 'value' => $lead->mother_details['email'] ?? ''],
            ['field_name' => 'lead_browser', 'value' => $lead->additional_details['browser'] ?? ''],
            ['field_name' => 'lead_city', 'value' => $lead->additional_details['user_city'] ?? ''],
            ['field_name' => 'lead_device', 'value' => $lead->additional_details['user_device'] ?? ''],
            ['field_name' => 'lead_ip', 'value' => $lead->ip_address ?? ''],
            ['field_name' => 'lead_page_url', 'value' => $lead->additional_details['landing_page'] ?? ''],
            ['field_name' => 'lead_referral_page_url', 'value' => $lead->additional_details['ref'] ?? ''],
            ['field_name' => 'lead_traffic_source', 'value' => $lead->additional_details['traffic_src'] ?? ''],
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

    public function verifyOtp(Request $request, $id)
    {
        $request->validate([
            'otp' => 'required|numeric',
        ]);

        $lead = Lead::findOrFail($id);
        $isOtpValid = Hash::check($request->input('otp'), $lead->otp);

        if (!$isOtpValid) {
            return response()->json([
                'success' => false,
                'otp_error' => 'The Phone OTP is incorrect.',
            ], 400);
        }

        Session::put('client_logged_in', true);
        Session::put('lead_id', $lead->id);
        $relationship = !empty($lead->father_details) ? 'father' : (!empty($lead->mother_details) ? 'mother' : '');

        // Prepare the parameters for sending the flow
        $params = [
            'father_name' => $lead->father_details['name'] ?? null,
            'father_phone' => $lead->father_details['phone'] ?? null,
            'father_email' => $lead->father_details['email'] ?? null,
            'mother_name' => $lead->mother_details['name'] ?? null,
            'mother_phone' => $lead->mother_details['phone'] ?? null,
            'mother_email' => $lead->mother_details['email'] ?? null,
            'relationship' => $relationship,
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
        $lead->url = "https://portal.qmis.edu.in/client/parent-details/$lead->id";
        $lead->save();
        return response()->json([
            'success' => true,
            'redirect' => route('client.parentDetails', ['id' => $lead->id])
        ]);
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

}
