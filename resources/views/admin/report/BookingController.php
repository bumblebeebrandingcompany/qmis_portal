<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\PlotDetail;
use App\Models\Project;

use Illuminate\Http\Request;
class BookingController extends Controller
{
    protected $util;

    /**
     * Constructor
     *
     */
    public function index()
    {
       $booking=Booking::all();
       $project=Project::all();
       $plotdetails=PlotDetail::all();
        return view('admin.booking_new.index',compact('booking','project','plotdetails'));
    }
    public function store(Request $request)
    {
        try{
            $booking = new Booking();
            $booking->name= $request->name;
            $booking->aadhar_no= $request->aadhar_no;
            $booking->pan= $request->pan;
            $booking->phone= $request->phone;
            $booking->secondary_phone= $request->secondary_phone;
            $booking->email= $request->email;
            $booking->secondary_email= $request->secondary_email;
            $booking->plc_values= $request->plc_values;
            $booking->total_amount= $request->total_amount;
            $booking->advance_amount= $request->advance_amount;
            $booking->pending_amount= $request->pending_amount;
            $booking->discount_value_sqft_based= $request->discount_value_sqft_based;
            $booking->discount_amount_sqft_based= $request->discount_amount_sqft_based;
            $booking->discount_value_including_plc= $request->discount_value_including_plc;
            $booking->discount_amount_including_plc= $request->discount_amount_including_plc;
            $booking->advance_amount= $request->advance_amount;
            $booking->{'credit/not_credit'}= $request->{'credit/not_credit'};
            $booking->status_id= $request->status_id;
            $booking->plot_id= $request->plot_id;
            // $booking->plot_id = json_encode($request->plot_id);

            $booking->save();
            return redirect()->back()->with('success', 'Form submitted successfully!');
        } catch (\Exception $e) {
            // Log the error for debugging purposes
            \Log::error('Error saving booking: ' . $e->getMessage());
            // Optionally, return an error response
            return response()->json(['success' => false, 'message' => 'Error saving booking'], 500);
        }
    }
    public function update($id, Request $request)
    {
        $booking = Booking::findOrFail($id);
        $booking->payment_mode = $request->payment_mode;
        $booking->cheque_no = $request->cheque_no;
        $booking->bank_name = $request->bank_name;
        $booking->account_no = $request->account_no;
        $booking->cheque_date = $request->cheque_date;
        $booking->dd_name = $request->dd_name;
        $booking->dd_no = $request->dd_no;
        $booking->dd_date = $request->dd_date;
        $booking->dd_bank = $request->dd_bank;
        $booking->status_id = $request->status_id;
        $booking->{'credit/not_credit'}= $request->{'credit/not_credit'};
        \Log::info('Request Data: ' . json_encode($request->all()));
        $booking->update(); // Save the changes to the database
        try {
            $booking->update($request->all());
        } catch (\Exception $e) {
            \Log::error('Error updating booking: ' . $e->getMessage());
            // Handle the error appropriately, such as showing an error message to the user
        }
        return view('admin.booking_new.form', compact('booking'));
    }
    public function create(Request $request)
    {
        $booking = Booking::all();
        $projectId = $request->input('projectId');
        $plotdetail = PlotDetail::where('project_id', $projectId)->get();
        return view('admin.booking_new.create', compact('plotdetail','booking'));
    }
    public function booking($id)
    {
        $plotdetail = PlotDetail::findOrFail($id);
        $booking = Booking::where('plot_id', $id)->first();
        return view('admin.booking_new.booking', compact('plotdetail', 'booking'));
    }
    public function book(Request $request)
    {
        $projectId = $request->input('projectId');
        $plotdetail = PlotDetail::where('project_id', $projectId)->get();
        $booking=Booking::all();
        return view('admin.booking_new.view', compact('plotdetail','booking'));
    }
    public function booked(Request $request,$id)
    {
        $booking = Booking::findOrFail($id);
        return view('admin.booking_new.form', compact('booking'));
    }
    }
