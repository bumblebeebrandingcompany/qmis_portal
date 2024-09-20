@extends('layouts.admin')

<table
class="table table-bordered table-striped table-hover table-head-fixed text-nowrap datatable-Lead">
<thead>
    <tr>
        <th width="10">
            {{-- <input type="checkbox" id="select-all"> --}}
        </th>
        <th>#</th>
        <th>Lms Ref No</th>
        <th>Father Name</th>
        <th>Father Phone</th>
        <th>Father Email</th>
        <th>Mother Name</th>
        <th>Mother Phone</th>
        <th>Mother Email</th>
        <th>Guardian Name</th>
        <th>Guardian Relationship</th>
        <th>Guardian Phone</th>
        <th>Guardian Email</th>
        <th>Child Details</th>
        <th>Project</th>
        <th>Campaign</th>
        <th>Source</th>
        <th>Subsource</th>
        <th>Stage</th>
        <th>Created at</th>
        <th>Actions</th>
    </tr>
</thead>
<tbody id="leadTable">
    @php
        $counter = 1;
    @endphp
    @foreach ($leads as $lead)
        @php
            // Get the URL associated with the project_id
            $url = \App\Models\Url::find($lead->project_id);
        @endphp
        <tr>
            <td>
                <input type="checkbox" name="lead_ids[]" value="{{ $lead->id }}">
            </td>
            <td>{{ $counter++ }}</td>
            <td>
                <a href="{{ route('admin.leads.show', ['lead' => $lead->id]) }}">{{ $lead->ref_num }}</a>

            </td>

            <!-- Display Father Details -->
            <td>{{ $lead->father_details['name'] ?? '' }}</td>
            <td>{{ $lead->father_details['phone'] ?? '' }}</td>
            <td>{{ $lead->father_details['email'] ?? '' }}</td>

            <!-- Display Mother Details -->
            <td>{{ $lead->mother_details['name'] ?? '' }}</td>
            <td>{{ $lead->mother_details['phone'] ?? '' }}</td>
            <td>{{ $lead->mother_details['email'] ?? '' }}</td>

            <!-- Display Guardian Details -->
            <td>{{ $lead->guardian_details['name'] ?? '' }}</td>
            <td>{{ $lead->guardian_details['relationship'] ?? '' }}</td>
            <td>{{ $lead->guardian_details['phone'] ?? '' }}</td>
            <td>{{ $lead->guardian_details['email'] ?? '' }}</td>
            <td>
                @if ($lead->student_details)
                    @php
                        $studentDetails = json_decode($lead->student_details, true);
                    @endphp
                    @foreach ($studentDetails as $child)
                        <div>
                            <strong>Name:</strong> {{ $child['name'] ?? '' }}<br>
                            <strong>Date of Birth:</strong> {{ $child['dob'] ?? '' }}<br>
                            <strong>Grade:</strong> {{ $child['grade'] ?? '' }}<br>
                            <strong>Old School:</strong> {{ $child['old_school'] ?? '' }}<br>
                            <strong>Reason for Quitting:</strong>
                            {{ $child['reason_for_quit'] ?? '' }}<br>
                        </div>
                        <hr> <!-- Separator between each child's details -->
                    @endforeach
                @else
                    No child details available.
                @endif
            </td>
            <td>{{ $url->project ?? '' }}</td>
            <td>{{ $url->campaign_name ?? '' }}</td>
            <td>{{ $url->source_name ?? '' }}</td>
            <td>{{ $url->sub_source_name ?? '' }}</td>
            <td>{{ $stages[$lead->parent_stage_id] ?? '' }}</td>
            <td>{{ $url->created_at ?? '' }}</td>
            <td>
                <a class="btn btn-xs btn-primary"
                    href="{{ route('admin.leads.show', $lead->id) }}">
                    {{ trans('global.view') }}
                </a>
                {{-- Uncomment below if permissions are required --}}
                {{-- @if (auth()->user()->checkPermission('lead_edit')) --}}
                <a class="btn btn-xs btn-info" href="{{ route('admin.leads.edit', $lead->id) }}">
                    {{ trans('global.edit') }}
                </a>
                {{-- @endif --}}
                {{-- @if (auth()->user()->checkPermission('lead_delete')) --}}
                <form action="{{ route('admin.leads.destroy', $lead->id) }}" method="POST"
                    onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                    style="display: inline-block;">
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="submit" class="btn btn-xs btn-danger"
                        value="{{ trans('global.delete') }}">
                </form>
                {{-- @endif --}}
            </td>
        </tr>
    @endforeach
</tbody>
</table>
