<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;

class ClientPortalController extends Controller
{

    public function splash()
    {
        return view('client.splash.splash');
    }

    public function welcome()
    {
        return view('client.splash.welcome');
    }
    public function showLoginForm(Request $request)
    {
        return view('client.login');
    }

    public function application(Request $request, $id)
    {
        $lead = Lead::findOrFail($id);
        return view('client.application', compact('lead'));
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'mobile' => 'required|numeric',
        ]);

        $lead = Lead::where('father_details->phone', $request->mobile)
            ->orWhere('mother_details->phone', $request->mobile)
            ->orWhere('guardian_details->phone', $request->mobile)
            ->first();

        if (!$lead) {
            return response()->json(['success' => false, 'message' => 'The mobile number is not associated with this lead.'], 400);
        }

        $otp = rand(1000,9999);
        $lead->otp = Hash::make($otp);
        $lead->save();

        $response = Http::post('https://www.smsalert.co.in/api/push.json', [
            'apikey' => '654dfc01824b7',
            'sender' => 'TBBBC',
            'mobileno' => $request->mobile,
            'text' => "One time password : $otp is your verification code. Please enter this to complete your submission. Powered by The Bumblebee Branding Company",
        ]);

        if ($response->successful()) {
            return response()->json([
                'success' => true,
                'lead_id' => $lead->id,
            ]);
        } else {
            return response()->json(['success' => false, 'message' => 'Failed to send OTP.'], 500);
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

        // Mark user as logged in
        Session::put('client_logged_in', true);
        Session::put('lead_id', $lead->id);

        // Check if there is an intended URL in the session
        $redirectUrl = $lead->url ? url($lead->url) : route('client.parentDetails',['id'=>$lead->id]);

        return response()->json([
            'success' => true,
            'redirect' => $redirectUrl,
        ]);
    }
    public function logout()
    {
        Session::forget('client_logged_in');
        Session::forget('lead_id');

        return redirect()->route('client.login');
    }
}
