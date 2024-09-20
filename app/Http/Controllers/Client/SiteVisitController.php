<?php

namespace App\Http\Controllers\Client;
use App\Models\LeadTimeline;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SiteVisit;
use App\Models\Lead;
use Razorpay\Api\Api;
use App\Models\RazorLog;
use Illuminate\Support\Facades\Http;
use Exception;
use Illuminate\Support\Facades\Log;

class SiteVisitController extends Controller
{
    protected $razorpay;




    public function store(Request $request, $id)
    {
        $lead = Lead::findOrFail($id);
        \Log::info('Site visit store request:', $request->all());

        // Validate the request with correct input field names
        $validatedData = $request->validate([
            'date' => 'required|date',
            'time_slot' => 'required|string',
        ]);

        // Store the site visit
        $siteVisit = SiteVisit::create([
            
            'lead_id' => $lead->id,
            'date' => $validatedData['date'],
            'time_slot' => $validatedData['time_slot'],
            'stage_id' => 13,
        ]);
        $lead->url = "https://portal.qmis.edu.in/client/site-visit/$lead->id/$siteVisit->id";
        $lead->parent_stage_id = 33;
        $lead->save();
        $this->logTimeline($lead, 'Stage Changed', "Stage was updated to {$lead->parent_stage_id}");

        $siteVisit->ref_num =4000 + $siteVisit->id;
        $siteVisit->save();


        // Redirect to the site visit form page
        return redirect()->route('client.siteVisitForm', ['id' => $lead->id, 'sv_id' => $siteVisit->id]);
    }
    public function showForm($id, $sv_id)
    {
        $lead = Lead::findOrFail($id);
        $siteVisit = SiteVisit::findOrFail($sv_id);
        $timeSlots = [
            '09:00 AM - 10:00 AM',
            '10:00 AM - 11:00 AM',
            '11:00 AM - 12:00 PM',
            '12:00 PM - 01:00 PM',
            '01:00 PM - 02:00 PM',
            '02:00 PM - 03:00 PM',
            '03:00 PM - 04:00 PM',
            '04:00 PM - 05:00 PM',
            '05:00 PM - 06:00 PM',
            '06:00 PM - 07:00 PM',
            '07:00 PM - 08:00 PM',
            '08:00 PM - 09:00 PM',
            '09:00 PM - 10:00 PM'
        ];

        return view('client.site_visit_form', compact('timeSlots', 'lead', 'siteVisit'));
    }

    public function success($id)
    {
        $lead = Lead::findOrFail($id);
        return view('client.success', compact('lead'));
    }


    public function __construct()
    {
        $this->razorpay = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
    }

    public function createOrder(Request $request, $id, $sv_id)
    {
        $amount = $request->input(key: 'amount');
        // $amount =1;
        $order = $this->razorpay->order->create([
            'amount' => $amount * 100, // Amount in paise
            'currency' => 'INR',
            'payment_capture' => 1,
        ]);

        $razor = new RazorLog();
        $razor->lead_id = $id;
        $razor->page = 'SiteVisit-999';
        if ($order) {
            $razor->payment_created = 1;
        } else {
            $razor->error = 'Payment Not Created by RazorPay';
        }
        $razor->save();

        return response()->json($order);
    }

    public function payment(Request $request, $id, $sv_id)
    {
        $amount = $request->input('amount');
        // $amount = 1;
        $order = $this->razorpay->order->create([
            'amount' => $amount * 100, // Amount in paise
            'currency' => 'INR',
            'payment_capture' => 1,
        ]);

        $razor = new RazorLog();
        $razor->lead_id = $id;
        $razor->page = 'Application-299';
        if ($order) {
            $razor->payment_created = 1;
        } else {
            $razor->error = 'Payment Not Created by RazorPay';
        }
        $razor->save();
        $lead = Lead::findOrFail($id);
        $siteVisit = SiteVisit::findOrFail($sv_id);

        return view('client.payment', compact('order', 'lead', 'siteVisit'));
    }

    public function paymentCom($id, $svId)
    {
        $lead = Lead::findOrFail($id);
        $siteVisit = SiteVisit::findOrFail($svId);

        return view('client.success', compact('lead', "siteVisit"));
    }

    public function paymentSuccess(Request $request, $id, $sv_id)
    {
        $payment_id = $request->input('razorpay_payment_id');
        $order_id = $request->input('razorpay_order_id');
        $signature = $request->input('razorpay_signature');

        try {
            // Verify the payment signature
            $attributes = [
                'razorpay_order_id' => $order_id,
                'razorpay_payment_id' => $payment_id,
                'razorpay_signature' => $signature
            ];

            $this->razorpay->utility->verifyPaymentSignature($attributes);

            $razor = new RazorLog();
            $razor->lead_id = $id;
            $razor->page = 'Application-299';
            $razor->payment_verified = 1;
            $razor->error = '';
            $razor->save();
            // Fetch the lead using the provided ID
            $lead = Lead::findOrFail($id); // Assuming the lead exists
            $lead->parent_stage_id = 11;
            $lead->save();

            $siteVisit = SiteVisit::findOrFail($sv_id);
            $siteVisit->stage_id = 11;
            $siteVisit->save();

            $lead->url = "https://portal.qmis.edu.in/client/site-visit/paymentCom/complete/$lead->id/$siteVisit->id";
            $lead->save();
            $this->logTimeline($lead, 'Stage Changed', "Stage was updated to {$lead->parent_stage_id}");
            $this->createUserInExternalApi($request, $lead, $siteVisit);
            $this->sendsitevisitFlow($request, $lead, $siteVisit->id);
           
            return redirect()->route('client.siteVisit.paymentCom', ['id' => $id,'sv_id'=>$sv_id])->with('success', 'Payment successful and application created.');

        } catch (\Exception $e) {
            
            $razor = new RazorLog();
            $razor->lead_id = $id;
            $razor->page = 'SiteVisit-999';
            $razor->error = json_encode($e->getMessage());
            $razor->save();
            // Handle errors such as verification failure or database errors
            return redirect()->route('client.siteVisit.paymentCom', ['id' => $id,'sv_id'=>$sv_id])->with('error', 'Payment verification failed or application creation failed. ' . $e->getMessage());
        }
    }
    protected function createUserInExternalApi(Request $request, Lead $lead, SiteVisit $siteVisit)
    {
        Log::info('Creating user in external API', ['lead' => $lead, 'sitevisit' => $siteVisit]);

        $chatRaceData = [
            'phone' => $lead->user_id,
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
            ['field_name' => 'lead_stage', 'value' => $siteVisit->parentStage->name ?? ''],
            ['field_name' => 'lead_sv_number', 'value' => $siteVisit->ref_num ?? ''],
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
            Log::info('User created via external API', ['chatRaceId' => $chatRaceId]);
        } else {
            Log::error('Failed to create user via external API: ' . $response->body());
            throw new Exception('Failed to create user via external API.');
        }
    }

    protected function sendsitevisitFlow(Request $request, Lead $lead, $id)
    {
        Log::info('Sending application flow data', ['lead' => $lead, 'id' => $id]);

        $siteVisit = SiteVisit::findOrFail($id);
        $data = [
            'lead_stage' => $siteVisit->parentStage->name ?? '',
            'lead_sv_number' => $siteVisit->ref_num ?? '',
        ];
        $chatRaceId = $lead->user_id;
        $flowResponse = Http::withHeaders([
            'X-Access-Token' => '1025340.dxtJ2Ma7me2STSkWkcF1u7JBICU4RhQ', // Replace with your actual access token
            'Content-Type' => 'application/json',
        ])->post("https://inbox.thebumblebee.in/api/users/$chatRaceId/send/1725964691854", $data);

        // Capture and store the flow response
        $flowSuccess = $flowResponse->successful();
        $lead->update(['flow_response' => $flowSuccess]);
        Log::info('Application flow response', ['flow_success' => $flowSuccess]);
    }

    public function structure($id, $sv_id)
    {
        $lead = Lead::findOrFail($id);
        $siteVisit = SiteVisit::findOrFail($sv_id);
        return view('client.sv_structure', compact('lead', 'siteVisit'));
    }
    public function logTimeline($lead, $activityType, $description)
    {
        $timeline = new LeadTimeline();
        $timeline->lead_id = $lead->id;
        $timeline->activity_type = $activityType;
        $payload = [
            'lead' => $lead->toArray(),
            'site visit initiated' => $lead->toArray()
        ];
        $timeline->payload = json_encode($payload); // Convert array to JSON
        $timeline->description = $description;
    
        $timeline->created_at = now();
        $timeline->save();
    }
}
