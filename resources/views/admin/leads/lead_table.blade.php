<div class="table-responsive fixed-header-table">
    <table class="table table-bordered table-striped table-hover ajaxTable datatable datatable-lead">
        <thead>
            <tr>
                <th width="10"></th>
                <th>#</th>
                <th>Ref No</th>
                <th>Submission No</th>
                    <th>Created At</th>
                    <th>Stage</th>
                <th>Father Name</th>
                <th>Mother Name</th>
                <th>Campaign</th>
                <th>Source</th>
                <th>Subsource</th>
                <th>Father Phone</th>
                <th>Mother Phone</th>
                <th>Father Email</th>
                <th>Mother Email</th>
                <th>Guardian Name</th>
                <th>Guardian Relationship</th>
                <th>Guardian Phone</th>
                <th>Guardian Email</th>
                <th>Child Details</th>
                <th>Project</th>

                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="leadTable">
            @php
            // Sort leads by 'created_at' in descending order
            $leads = $leads->sortByDesc('created_at');
            $counter = 1;
        @endphp
@foreach ($leads as $lead)
<tr>
                    <td><input type="checkbox" name="lead_ids[]" value="{{ $lead->id }}"></td>
                    <td>{{ $counter++ }}</td>
                    <td><a href="{{ route('admin.leads.show', ['lead' => $lead->id]) }}">{{ $lead->ref_num }}</a></td>
                    <td>{{$lead->additional_details['sub_no']?? ''}}</td>
                    <td>{{$lead->created_at ?? ''}}</td>
                    <td>{{ $stages[$lead->parent_stage_id] ?? '' }}</td>
                    <td>{{ $lead->father_details['name'] ?? '' }}</td>
                    <td>{{ $lead->mother_details['name'] ?? '' }}</td>
                    <td>{{ $lead->campaign_name ?? '' }}</td>
                    <td>{{ $lead->source_name ?? '' }}</td>
                    <td>{{ $lead->sub_source_name??'' }}</td>
                    <td>{{ $lead->father_details['phone'] ?? '' }}</td>
                    <td>{{ $lead->mother_details['phone'] ?? '' }}</td>
                    <td>{{ $lead->father_details['email'] ?? '' }}</td>
                    <td>{{ $lead->mother_details['email'] ?? '' }}</td>
                    <td>{{ $lead->guardian_details['name'] ?? '' }}</td>
                    <td>{{ $lead->guardian_details['relationship'] ?? '' }}</td>
                    <td>{{ $lead->guardian_details['phone'] ?? '' }}</td>
                    <td>{{ $lead->guardian_details['email'] ?? '' }}</td>
                    <td>
                        @if ($lead->student_details)
                            @php
                                // Decode the student details
                                $studentDetails = is_array($lead->student_details)
                                    ? $lead->student_details
                                    : json_decode($lead->student_details, true);
                                // If it's a single object, wrap it in an array
          if (isset($studentDetails['name'])) {
                                    $studentDetails = [$studentDetails];
                                }
                            @endphp

                            @if (!empty($studentDetails))
                                @foreach ($studentDetails as $child)
                                    <div>
                                        <strong>Name:</strong> {{ $child['name'] ?? 'N/A' }}<br>
                                        {{-- <strong>Date of Birth:</strong> {{ $child['dob'] ?? 'N/A' }}<br>
                                        <strong>Grade:</strong> {{ $child['grade'] ?? 'N/A' }}<br>
                                        <strong>Old School:</strong>
                                        {{ $child['old_school'] ?? 'N/A' }}<br>
                                        <strong>Reason for Quitting:</strong>
                                        {{ $child['reason_for_quit'] ?? 'N/A' }}<br> --}}
                                    </div>
                                    <hr> <!-- Separator between each child's details -->
                                @endforeach
                            @else
                                No valid student details available.
                            @endif
                        @else

                        @endif
                    </td>
                            <td>{{ $lead->project->name ?? '' }}</td>
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
                        @if (auth()->user()->is_superadmin)
                            {{-- @if (auth()->user()->checkPermission('lead_delete')) --}}
                            <form action="{{ route('admin.leads.destroy', $lead->id) }}" method="POST"
                                onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                style="display: inline-block;">
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="submit" class="btn btn-xs btn-danger"
                                    value="{{ trans('global.delete') }}">
                            </form>
                        @endif
                    </td>                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<style>
/* Ensure vertical scrollbar appears only in the table-responsive container */
.fixed-header-table {
    max-height: 500px; /* Adjust this as needed */
    overflow-y: auto; /* Ensure the vertical scrollbar is only here */
    overflow-x: auto;
}

/* Hide the overflow on the body to avoid dual vertical scrolls */
body {
    overflow-y: hidden;
}

/* Fixed header styling to stay at the top when scrolling */
.fixed-header-table thead th {
    position: sticky;
    top: 0;
    background-color: #fff;
    z-index: 10;
    box-shadow: 0 2px 2px rgba(0, 0, 0, 0.1);
}

    </style>
@section('scripts')
@parent
<script>
    $(function() {
        let table = $('.datatable-lead').DataTable({
            scrollY: '370px', // Adjust based on your height requirements
            scrollCollapse: true,
            paging: false, // Enable/disable pagination based on your needs
            info: false,
            ordering: false // Enable/disable ordering
        });
    });
</script>
<script>
$(document).ready(function() {
        let table = $('.datatable-lead');
        let topScrollbar = $('.horizontal-scroll-wrapper');

        // Set the width of the top scrollbar equal to the width of the table
        topScrollbar.find('.top-scrollbar').width(table.find('table').width());

        // Sync the scrolling of both top and bottom scrollbars
        topScrollbar.scroll(function() {
            $('.fixed-header-table').scrollLeft($(this).scrollLeft());
        });

        $('.fixed-header-table').scroll(function() {
            topScrollbar.scrollLeft($(this).scrollLeft());
        });
    });
</script>
@endsection
