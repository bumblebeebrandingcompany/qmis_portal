<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Field;
use Illuminate\Http\Request;

class FieldController extends Controller
{
    public function index()
    {
        $fields = Field::all();
        return view('admin.fields.index', compact('fields'));
    }

    public function create()
    {
        return view('admin.fields.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'text' => 'required|string|max:255',
            'value' => 'required|string|max:255',
            'data_type' => 'required|string|max:255',
        ]);

        Field::create($request->all());

        return redirect()->route('admin.fields.index')->with('success', 'Field created successfully.');
    }

    public function edit(Field $field)
    {
        return view('admin.fields.edit', compact('field'));
    }

    public function update(Request $request, Field $field)
    {
        $request->validate([
            'text' => 'required|string|max:255',
            'value' => 'required|string|max:255',
            'data_type' => 'required|string|max:255',
        ]);

        $field->update($request->all());

        return redirect()->route('admin.fields.index')->with('success', 'Field updated successfully.');
    }

    public function destroy(Field $field)
    {
        $field->delete();
        return redirect()->route('admin.fields.index')->with('success', 'Field deleted successfully.');
    }
}
