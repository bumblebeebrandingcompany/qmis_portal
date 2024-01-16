<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\NoteNotInterested;

use Illuminate\Http\Request;

class NoteNotInterestedController extends Controller
{
    public function index()
    {
        $noteNotInterested = NoteNotInterested::all();
        return view('admin.note_notinterested.index', compact('noteNotInterested'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'notes' => 'required|string',

        ]);

        NoteNotInterested::create([
            'notes' => $request->notes,

        ]);

        return redirect()->route('admin.notenotinterested.index')->with('success', 'Parent Stage created successfully.');
    }

    public function edit($id)
    {
        $noteNotInterested = NoteNotInterested::findOrFail($id);

        return view('admin.notenotinterested.index', compact('noteNotInterested'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'notes' => 'required|string',

        ]);

        $noteNotInterested = NoteNotInterested::findOrFail($id);

        $noteNotInterested->notes = $request->input('notes');

        $noteNotInterested->save();

        return redirect()->route('admin.notenotinterested.index')->with('success', 'Parent Stage updated successfully');
    }

    public function destroy($id)
    {
        $noteNotInterested = NoteNotInterested::findOrFail($id);
        $noteNotInterested->delete();

        return redirect()->route('admin.notenotinterested.index')->with('success', 'Stage deleted successfully.');
    }

}
