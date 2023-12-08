<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyLeadRequest;
use App\Http\Requests\StoreFollowupRequest;
use App\Http\Requests\UpdateLeadRequest;
use App\Models\Campaign;
use App\Models\Document;
use App\Models\Lead;
use App\Models\LeadEvents;
use App\Models\Project;
use App\Models\Source;
use App\Models\CallRecord;
use App\Models\User;
use App\Notifications\LeadDocumentShare;
use App\Utils\Util;
use App\Models\Note;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use FFMpeg;
use FFProbe;
use Illuminate\Support\Facades\File;

use DateTimeInterface;

class CallRecordController extends Controller
{
    public function index()
    {
        $lead = Lead::all();
        $agencies = User::all();
        $campaigns = Campaign::all();
        $callRecords = CallRecord::all();
        $apiKey = config('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczovL2N1c3RvbWVyLnNlcnZldGVsLmluL2FwaS92MS9hdXRoL2xvZ2luIiwiaWF0IjoxNjk4NjY0NTAxLCJleHAiOjE2OTg2NjgxMDEsIm5iZiI6MTY5ODY2NDUwMSwianRpIjoiaFBDRUIwblllUjBjU2N2MCIsInN1YiI6IjE3MDQ0MCJ9.V_qQ_Vtm9d2ojWyqR1ZBfxjQRt2JJnz3YHXgXJ3WIxQ');
        $apiKey = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiIxNzA0NDAiLCJpc3MiOiJodHRwczovL2N1c3RvbWVyLnNlcnZldGVsLmluL3Rva2VuL2dlbmVyYXRlIiwiaWF0IjoxNjk4NjYxMjYwLCJleHAiOjE5OTg2NjEyNjAsIm5iZiI6MTY5ODY2MTI2MCwianRpIjoiTWtYY0h0OXlpNG5Ea2FuaSJ9.L23vhUJ0UIGc3nffLeMK0NMczroLgwwkECFnaCaY-A8';
        $apiUrl = 'https://api.servetel.in/v1/call/records';

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
        ])->get($apiUrl);

        if ($response->successful()) {
            $data = $response->json();
        } else {
            $errorResponse = $response->json();
        }
        $data = $response->json();
        return view('admin.callog.index',compact('agencies','campaigns','lead','callRecords'),['data' => $data]);
    }
    public function store(Request $request)
    {
        $apiKey = config('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczovL2N1c3RvbWVyLnNlcnZldGVsLmluL2FwaS92MS9hdXRoL2xvZ2luIiwiaWF0IjoxNjk4NjY0NTAxLCJleHAiOjE2OTg2NjgxMDEsIm5iZiI6MTY5ODY2NDUwMSwianRpIjoiaFBDRUIwblllUjBjU2N2MCIsInN1YiI6IjE3MDQ0MCJ9.V_qQ_Vtm9d2ojWyqR1ZBfxjQRt2JJnz3YHXgXJ3WIxQ');
        $apiKey = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiIxNzA0NDAiLCJpc3MiOiJodHRwczovL2N1c3RvbWVyLnNlcnZldGVsLmluL3Rva2VuL2dlbmVyYXRlIiwiaWF0IjoxNjk4NjYxMjYwLCJleHAiOjE5OTg2NjEyNjAsIm5iZiI6MTY5ODY2MTI2MCwianRpIjoiTWtYY0h0OXlpNG5Ea2FuaSJ9.L23vhUJ0UIGc3nffLeMK0NMczroLgwwkECFnaCaY-A8';

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
        ])->get('https://api.servetel.in/v1/call/records');
        $callRecords = $response->json();
        foreach ($callRecords['results'] as $record) {

            $recordingUrl = $record['recording_url'];

            $audioData = file_get_contents($recordingUrl);

            // Generate a unique file name
            $fileName = 'audio_' . time() . '.mp3';
            $path = 'audio/' . $fileName;
            $absolutePath = public_path($path);
                        echo "Path: " . $path;
                        File::makeDirectory(dirname($absolutePath), 0755, true, true);
                        // Save the audio data to the server
                        file_put_contents($absolutePath, $audioData);
            // Store the call record in the database
            CallRecord::create([
                'called_by' => $record['agent_number'],
                'called_on' => $record['date'],
                'call_start_time' => $record['time'],
                'call_duration' => $record['answered_seconds'],
                'call_recordings' => $path,
                'status' => $record['status'], // Ensure this line is present and correct
            ]);
        }

        return redirect()->route('admin.callog.store')->with('success', 'Call records stored successfully.');
    }

    private function validateCallRecord(array $record)
    {
        validator($record, [
            'called_by' => 'required|string',
            'called_on' => 'required|string',
            'call_start_time' => 'required|date',
            'call_duration' => 'required|integer',
            'call_recordings' => 'required', // Assuming this is correct based on your structure
            'status' => 'required',
        ])->validate();
    }


}
