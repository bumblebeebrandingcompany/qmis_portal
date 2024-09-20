<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\CallLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CallLogController extends Controller
{
    public function storecallrecord(Request $request)
    {
        // Validate incoming request data
        $validatedData = $request->validate([
            'calldate' => 'required|date',
            'source' => 'required|string',
            'destination' => 'required|string',
            'call_duration_with_ringing' => 'nullable',
            'call_duration_talk_time' => 'nullable',
            'type'=>'required|string',
            'remote_id'=>'required|string',
            'call_disposition' => 'required|string',
            'recording_url' => 'nullable|url',

        ]);


        $callLog = CallLog::create($validatedData);

        return response()->json([
            'success' => true,
            'data' => $callLog
        ]);
    }
    // public function index()
    // {
    //     return view('admin.calltable.index', compact('agencies', 'campaigns', 'lead', 'callRecords', 'data'));
    // }
    public function index()
    {
        $callLogs = CallLog::all();
        return view('admin.callog.index', compact('callLogs'));
    }

}
