<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;
use App\Models\Lead;

class SchemeController extends Controller
{
    public function schemeDetails($id)
    {
        $lead = Lead::findOrFail($id);
        $packages = Package::where('class', $lead->student_details[0]['grade'])->get();
        $previousPlanName = null;

        return view('client.scheme', compact('lead','packages','previousPlanName') );
    }

    public function chooseSchemeOption(Request $request, $id)
    {
        $scheme = $request->input('scheme');
        $option = $request->input('option');
        $lead = Lead::findOrFail($id);
        $studentDetails = $lead->student_details;
        if ($option == 'application_purchase') {
            $amount = 599 * count($studentDetails);
        } elseif ($option == 'site_visit_schedule') {
            $amount = 399 * count($studentDetails);
        } else {
            return response()->json(['error' => 'Invalid option selected'], 400);
        }

        return response()->json([
            'amount' => $amount,
            'scheme' => $scheme,
            'id' => $lead->id
        ]);
    }

}
