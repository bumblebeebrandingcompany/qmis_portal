<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Lead;
use App\Models\CallRecord;
use App\Models\User;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use PDF;

class QrCodeController extends Controller
{

    public function generatePdfWithQrCode($no)
    {

        try {
            $qrCodeSvg = QrCode::format('svg')->size(200)->generate($no);
            $application = Application::where('application_no', $no)->first();
            // Convert the SVG to base64
            $qrCode = 'data:image/svg+xml;base64,' . base64_encode($qrCodeSvg);
            // $kg1 = public_path('assets/images/pdf_kg1.png');
            // $kg_1 = 'data:image/png;base64,' . base64_encode(file_get_contents($kg1));
            $pdf = PDF::loadView('pdf.qr', ['qrCode' => $qrCode, 'application' => $application]);

            // return view('pdf.qr', compact('qrCode', 'application'));
            // return $pdf->stream('qr-code.pdf');

            return $pdf->download('qr-code.pdf');
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

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

        $itemsPerPage = request('perPage', 10);

        $callRecords = CallRecord::paginate($itemsPerPage);
        $data = $response->json();
        return view('admin.callog.index', compact('agencies', 'campaigns', 'lead', 'callRecords', 'data'));
    }
}
