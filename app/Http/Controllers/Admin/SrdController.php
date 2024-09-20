<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Srd;
use App\Models\Project;
use App\Models\Url;
use Illuminate\Http\Request;

class SrdController extends Controller
{
    public function index()
    {
        $srds = Srd::all();
        return view('admin.srds.index', compact('srds'));
    }

    public function create()
    {
        $projects = Project::all();
        return view('admin.srds.create', compact('projects'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'campaign_name' => 'required|string|max:255',
            'source_name',
        ]);
        Url::create($request->all());

        return redirect()->route('admin.urls.index')->with('success', 'SRD created successfully.');
    }

    public function show(Srd $srd)
    {
        return view('admin.srds.show', compact('srd'));
    }

    public function edit(Srd $srd)
    {
        $projects = Project::all();
        return view('admin.srds.edit', compact('srd', 'projects'));
    }

    public function update(Request $request, Srd $srd)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'campaign' => 'required|string|max:255',
            'source' => 'required|string|max:255',
        ]);

        $srd->update($request->all());

        return redirect()->route('admin.srd.index')->with('success', 'SRD updated successfully.');
    }

    public function destroy(Srd $srd)
    {
        $srd->delete();

        return redirect()->route('admin.srd.index')->with('success', 'SRD deleted successfully.');
    }
}
