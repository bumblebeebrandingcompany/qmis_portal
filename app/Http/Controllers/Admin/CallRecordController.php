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

        // Check if the 'results' key exists
        if (isset($callRecords['results']) && is_array($callRecords['results'])) {
            foreach ($callRecords['results'] as $record) {
                // Check if the necessary keys are present in each record
                if (isset($record['recording_url'], $record['agent_number'], $record['date'], $record['time'], $record['answered_seconds'], $record['status'])) {
                    $recordingUrl = $record['recording_url'];
                    $audioData = file_get_contents($recordingUrl);

                    // Check if 'called_by' is not null or empty
                    $calledBy = $record['agent_number'] ?? null;

                    if ($calledBy !== null) {
                        // Generate a unique file name
                        $fileName = 'audio_' . time() . '.mp3';
                        $path = 'audio/' . $fileName;
                        $absolutePath = public_path($path);

                        // Ensure the directory exists
                        File::makeDirectory(dirname($absolutePath), 0755, true, true);

                        // Save the audio data to the server
                        if (file_put_contents($absolutePath, $audioData) !== false) {
                            // Store the call record in the database
                            CallRecord::create([
                                'called_by' => $calledBy,
                                'called_on' => $record['date'],
                                'call_start_time' => $record['time'],
                                'call_duration' => $record['answered_seconds'],
                                'call_recordings' => $path,
                                'status' => $record['status'],
                            ]);
                        } else {
                            // Log or handle the case where file_put_contents fails
                        }
                    } else {
                        // Log or handle the case where 'called_by' is null
                    }
                } else {
                    // Log or handle the case where required keys are missing in the record
                }
            }
        } else {
            // Log or handle the case where 'results' key is missing in the API response
        }

        return redirect()->route('admin.callog.store')->with('success', 'Call records stored successfully.');
    }





}
