<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Razorpay\Api\Api;
use App\Models\Lead;
use App\Models\RazorLog;
use App\Models\Application; // Assuming you have an Application model
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Models\LeadTimeline;

class PaymentController extends Controller
{
    protected $razorpay;

    public function __construct()
    {
        $this->razorpay = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
    }

    public function createOrder(Request $request, $id)
    {
        $amount = $request->input('amount');
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

        return response()->json($order);
    }

    public function payment(Request $request, $id)
    {
        $amount = $request->input('amount');
        // $amount = 1;
        $order = $this->razorpay->order->create([
            'amount' => $amount * 100, // Amount in paise
            'currency' => 'INR',
            'payment_capture' => 1,
        ]);
        $lead = Lead::find($id);
        $razor = new RazorLog();
        $razor->lead_id = $id;
        $razor->page = 'Application-299';
        $razor->data = json_encode($order);
        if ($order) {
            $razor->payment_created = 1;
        } else {
            $razor->error = 'Payment Not Created by RazorPay';
        }
        $razor->save();

        return view('payment', compact('order', 'lead'));
    }

    public function paymentCom($id) {
        return view('client.payment_success');
    }

    public function paymentSuccess(Request $request, $id)
    {
        $payment_id = $request->input('razorpay_payment_id');
        $order_id = $request->input('razorpay_order_id');
        $signature = $request->input('razorpay_signature');

        // $razor = RazorLog::where('lead_id', $id)->where('page', 'Application-299')->orderBy('id', 'DESC')->first();
        // if (!$razor) {
        $razor = new RazorLog();
        // }
        try {
            // Verify the payment signature
            $attributes = [
                'razorpay_order_id' => $order_id,
                'razorpay_payment_id' => $payment_id,
                'razorpay_signature' => $signature
            ];
            try {
                $this->razorpay->utility->verifyPaymentSignature($attributes);
                $response = Http::withBasicAuth(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'))
                ->get('https://api.razorpay.com/v1/orders/', $order_id.'/payments');
        
                if ($response->successful()) {
                    $razor->lead_id = $id;
                    $razor->page = 'Application-299';
                    $razor->receiver_data = json_encode($response->json());
                    $razor->error = '';
                    $razor->save();
                } else {
                    
                    
                    $razor->lead_id = $id;
                    $razor->page = 'Application-299';
                    $razor->receiver_data = json_encode($response->json());
                    $razor->error = json_encode([
                        'error_type' => 'SignatureVerificationError',
                        'message' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'trace' => $e->getTraceAsString(),
                        'attributes' => $attributes
                    ]);
                    $razor->save();
                }
            } catch (\Razorpay\Api\Errors\SignatureVerificationError $e) {
                
                $razor->lead_id = $id;
                $razor->page = 'Application-299';
                $razor->receiver_data = json_encode($attributes);
                $razor->error = json_encode([
                    'error_type' => 'SignatureVerificationError',
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                    'attributes' => $attributes
                ]);
                $razor->save();
                return redirect()->back()->with('error', 'Payment verification failed or application creation failed. ' . $e->getMessage());
            }

            // Fetch the lead using the provided ID
            $lead = Lead::findOrFail($id); // Assuming the lead exists
            $lead->parent_stage_id = 13;

            $lead->url="https://portal.qmis.edu.in/client/paymentCom/complete/$lead->id";
            $lead->save();
            $this->logTimeline($lead, 'Stage Changed', "Stage was updated to {$lead->parent_stage_id}");

            // Generate the application number
            $applicationNumber = 2000+$lead->id; // Example format: AP0010001
            $waiting = 3000+$lead->id; // Example format: AP0010001

            $application = new Application();
            $application->lead_id = $lead->id;
            $application->application_no = $applicationNumber;
            $application->waiting_list = $waiting;
            $application->stage_id = 13; // Set appropriate status
            $application->save();
            $this->createUserInExternalApi($request, $lead, $application);
            $this->sendApplicationFlow($request, $lead, $application->id);
            return redirect()->route('client.paymentCom', ['id' => $id])->with('success', 'Payment successful and application created.');

        } catch (\Exception $e) {

            // $this->razorpay->utility->verifyPaymentSignature($attributes);
            $response = Http::withBasicAuth(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'))
            ->get('https://api.razorpay.com/v1/orders/', $order_id.'/payments');
    
            if ($response->successful()) {
                $razor->lead_id = $id;
                $razor->page = 'Application-299';
                $razor->receiver_data = json_encode($response->json());
                $razor->error = '';
                $razor->save();
            } else {
                
                
                $razor->lead_id = $id;
                $razor->page = 'Application-299';
                $razor->receiver_data = json_encode($response->json());
                $razor->error = json_encode([
                    'error_type' => 'SignatureVerificationError',
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                    'attributes' => $attributes
                ]);
                $razor->save();
            }
            $responseItems = $response->json()['items'];

            if (isset($responseItems[0]['status']) && $responseItems[0]['status'] === 'captured') {
                $lead = Lead::findOrFail($id); // Assuming the lead exists
                $lead->parent_stage_id = 13;
    
                $lead->url="https://portal.qmis.edu.in/client/paymentCom/complete/$lead->id";
                $lead->save();

                $application = Application::where('lead_id', $lead->id)->first();
                if (!$application){
                    $application = new Application();
                }
                $applicationNumber = 2000+$lead->id; // Example format: AP0010001
                $waiting = 3000+$lead->id; // Example format: AP0010001

                $application->lead_id = $lead->id;
                $application->application_no = $applicationNumber;
                $application->waiting_list = $waiting;
                $application->stage_id = 13; // Set appropriate status
                $application->save();
                return redirect()->route('client.paymentCom', ['id' => $id])->with('success', 'Payment successful and application created.');
            }
            // Handle errors such as verification failure or database errors
            return redirect()->back()->with('error', 'Payment verification failed or application creation failed. ' . $e->getMessage());
        }
    }

    protected function createUserInExternalApi(Request $request, Lead $lead, Application $application)
    {
        Log::info('Creating user in external API', ['lead' => $lead, 'application' => $application]);

        $chatRaceData = [
            'phone' => $lead->user_id,
            'first_name' => $lead->father_details['name'],
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
            ['field_name' => 'father_name', 'value' => $lead->father_details['name'] ?? ''],
            ['field_name' => 'mother_name', 'value' => $lead->mother_details['name'] ?? ''],
            ['field_name' => 'father_phone', 'value' => $lead->father_details['phone'] ?? ''],
            ['field_name' => 'mother_phone', 'value' => $lead->mother_details['phone'] ?? ''],
            ['field_name' => 'father_email', 'value' => $lead->father_details['email'] ?? ''],
            ['field_name' => 'mother_email', 'value' => $lead->mother_details['email'] ?? ''],
            ['field_name' => 'father_occupation', 'value' => $lead->father_details['occupation'] ?? ''],
            ['field_name' => 'father_income', 'value' => $lead->father_details['income'] ?? ''],
            ['field_name' => 'mother_occupation', 'value' => $lead->mother_details['occupation'] ?? ''],
            ['field_name' => 'mother_income', 'value' => $lead->mother_details['income'] ?? ''],
            ['field_name' => 'Sales_agent', 'value' => '' ?? ''],
            ['field_name' => 'Sales_agent_id', 'value' => '' ?? ''],
            ['field_name' => 'lead_stage', 'value' => $lead->parentStage->name ?? ''],
            ['field_name' => 'lead_wl_number', 'value' => $application->waiting_list ?? ""],
            ['field_name' => 'enquiry_number', 'value' => $lead->ref_num ?? ''],
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

    protected function sendApplicationFlow(Request $request, Lead $lead, $id)
    {
        Log::info('Sending application flow data', ['lead' => $lead, 'id' => $id]);

        $application = Application::findOrFail($id);
        $data = [
            'lead_wl_number' => $lead->waiting_list,
        ];
        $chatRaceId = $lead->user_id;
        $flowResponse = Http::withHeaders([
            'X-Access-Token' => '1025340.dxtJ2Ma7me2STSkWkcF1u7JBICU4RhQ', // Replace with your actual access token
            'Content-Type' => 'application/json',
        ])->post("https://inbox.thebumblebee.in/api/users/$chatRaceId/send/1725964559619", $data);

        // Capture and store the flow response
        $flowSuccess = $flowResponse->successful();
        $lead->update(['flow_response' => $flowSuccess]);
        Log::info('Application flow response', ['flow_success' => $flowSuccess]);
    }

    public function applicationProcess($id)
    {
        // Fetch the lead using the provided ID
        $lead = Lead::findOrFail($id);

        // Fetch the associated application using the lead ID
        $application = Application::where('lead_id', $id)->first();

        // Check if the application exists
        if (!$application) {
            return redirect()->route('client.structure', ['id' => $id])->with('message', 'Application not found.');
        }

        // Calculate the time difference between now and the application's created_at time
        $timeDifference = now()->diffInHours($application->created_at);

        // If more than 3 hours have passed, redirect to another page or show a different view
        if ($timeDifference > 3) {
            return redirect()->route('client.structure', ['id' => $id])->with('message', 'The application review period has expired.');
        }

        // Otherwise, show the application process view
        return view('client.application_process', compact('lead', 'application'));
    }
    public function logTimeline($lead, $activityType, $description)
    {
        $timeline = new LeadTimeline();
        $timeline->lead_id = $lead->id;
        $timeline->activity_type = $activityType;
        $payload = [
            'lead' => $lead->toArray(),
            'application' => $lead->toArray()
        ];
        $timeline->payload = json_encode($payload); // Convert array to JSON
        $timeline->description = $description;
    
        $timeline->created_at = now();
        $timeline->save();
    }
}

