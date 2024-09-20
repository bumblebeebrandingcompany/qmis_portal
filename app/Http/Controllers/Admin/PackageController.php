<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\Plan;


class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::with('plan')->get();
        return view('admin.packages.index', compact('packages'));
    }

    public function create()
    {
        $plans = Plan::all();
        return view('admin.packages.create', compact('plans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'type' => 'required|string',
            'amount' => 'required',
            'offer' => 'nullable|string',
            'transport_price' => 'nullable',
            'admission_fees' => 'nullable',
            'waiver_term2' => 'nullable',
            'waiver_term3' => 'nullable',
            'kid_gym_waiver' => 'nullable',
            'total_amount' => 'nullable',
            'benefits_availed' => 'nullable',
        ]);

        Package::create($request->all());

        return redirect()->route('admin.packages.index')->with('success', 'Package created successfully.');
    }

    public function edit(Package $package)
    {
        $plans = Plan::all();
        return view('admin.packages.edit', compact('package', 'plans'));
    }

    public function update(Request $request, Package $package)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'type' => 'required|string',
            'amount' => 'required|numeric',
            'offer' => 'nullable|string',
            'transport_price' => 'nullable|numeric',
            'admission_fees' => 'nullable|numeric',
            'waiver_term2' => 'nullable|numeric',
            'waiver_term3' => 'nullable|numeric',
            'kid_gym_waiver' => 'nullable|numeric',
        ]);

        $package->update($request->all());

        return redirect()->route('admin.packages.index')->with('success', 'Package updated successfully.');
    }

    public function destroy(Package $package)
    {
        $package->delete();

        return redirect()->route('admin.packages.index')->with('success', 'Package deleted successfully.');
    }
}
