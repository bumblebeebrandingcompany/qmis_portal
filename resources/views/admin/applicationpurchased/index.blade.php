@extends('layouts.admin')

@section('content')
    <h2>Application Purchased</h2>
    @php
    // Get the current URL fragment to determine the active tab
    $activeTab = request()->has('tab') ? request('tab') : 'all-applications';
    @endphp
    <ul class="nav nav-tabs" id="applicationTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link {{ $activeTab === 'all-applications' ? 'active' : '' }}"
               href="{{ url('admin/applications?tab=all-applications') }}">All Applications</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link {{ $activeTab === 'filtered-applications' ? 'active' : '' }}"
               href="{{ url('admin/applications?tab=filtered-applications') }}">Current Applications</a>
        </li>

        <li class="nav-item" role="presentation">
            <a class="nav-link {{ $activeTab === 'accepted-applications' ? 'active' : '' }}"
               href="{{ url('admin/applications?tab=accepted-applications') }}">Accepted Applications</a>
        </li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <!-- All Applications Tab -->
        <div class="tab-pane {{ $activeTab === 'all-applications' ? 'active' : '' }}" id="all-applications">
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered table-striped table-hover ajaxTable datatable datatable-applicationpurchased"
                           id="allApplicationsTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Reference Number</th>
                                <th>Application No</th>
                                <th>Waiting list No</th>
                                <th>Student Name</th>
                                <th>Father Name</th>
                                <th>Mother Name</th>
                                <th>Notes</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $counter = 1;
                            @endphp
                            @foreach ($applications->where('stage_id', 13) as $applicationpurchased)
                                <tr>
                                    <td>{{ $counter++ }}</td>
                                    <td>
                                        @foreach ($lead as $leads)
                                            @if ($leads->id === $applicationpurchased->lead_id)
                                                <a href="{{ route('admin.leads.show', ['lead' => $leads->id]) }}">
                                                    {{ $leads->ref_num }}
                                                </a>
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>{{ $applicationpurchased->application_no ?? '' }}</td>
                                    <td>{{ $applicationpurchased->waiting_list ?? '' }}</td>

                                    <td>
                                        @if ($applicationpurchased->lead && is_array($applicationpurchased->lead->student_details) && count($applicationpurchased->lead->student_details) > 0)
                                            <ul>
                                                @foreach ($applicationpurchased->lead->student_details as $student)
                                                    <li>{{ $student['name'] ?? 'No name' }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            No children listed.
                                        @endif
                                    </td>


                                    <td>
                                        @foreach ($lead as $leads)
                                            @if ($leads->id === $applicationpurchased->lead_id)
                                                {{ $leads->father_details['name'] ?? '' }}
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach ($lead as $leads)
                                            @if ($leads->id === $applicationpurchased->lead_id)
                                                {{ $leads->mother_details['name'] ?? '' }}
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>{{ $applicationpurchased->notes }}</td>
                                    <td>{{ $applicationpurchased->created_at->format('Y-m-d') }}</td>
                                    <td><a class="btn btn-primary" href="{{route('qrdownload', @$applicationpurchased->application_no)}}" target="_blank"><i class="fa fa-download text-white"></i></a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Filtered Applications Tab -->
        <div class="tab-pane {{ $activeTab === 'filtered-applications' ? 'active' : '' }}" id="filtered-applications">
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered table-striped table-hover ajaxTable datatable datatable-applicationpurchased"
                           id="filteredApplicationsTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Reference Number</th>
                                <th>Application No</th>
                                <th>Waiting List No</th>
                                <th>Student Name</th>
                                <th>Father Name</th>
                                <th>Mother Name</th>
                                <th>Notes</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $counter = 1;
                            @endphp
                            @foreach ($applications->where('stage_id', 13) as $applicationpurchased)
                                @foreach ($lead as $leads)
                                    @if ($leads->id === $applicationpurchased->lead_id && $leads->parent_stage_id == $applicationpurchased->stage_id)
                                        <tr>
                                            <td>{{ $counter++ }}</td>
                                            <td>
                                                <a href="{{ route('admin.leads.show', ['lead' => $leads->id]) }}">
                                                    {{ $leads->ref_num }}
                                                </a>
                                            </td>
                                            <td>{{ $applicationpurchased->application_no ?? '' }}</td>
                                            <td>{{ $applicationpurchased->waiting_list ?? '' }}</td>

                                            <td>
                                                @if (is_array($applicationpurchased->lead->student_details) && count($applicationpurchased->lead->student_details) > 0)
                                                    <ul>
                                                        @foreach ($applicationpurchased->lead->student_details as $student)
                                                            <li>{{ $student['name'] ?? 'No name' }}</li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    No children listed.
                                                @endif
                                            </td>
                                            <td>{{ $leads->father_details['name'] ?? '' }}</td>
                                            <td>{{ $leads->mother_details['name'] ?? '' }}</td>


                                            <td>{{ $applicationpurchased->notes }}</td>
                                            <td>{{ $applicationpurchased->created_at->format('Y-m-d') }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Accepted Applications Tab -->
        <div class="tab-pane {{ $activeTab === 'accepted-applications' ? 'active' : '' }}" id="accepted-applications">
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered table-striped table-hover ajaxTable datatable datatable-applicationpurchased"
                           id="acceptedApplicationsTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Reference Number</th>
                                <th>Application No</th>
                                <th>Waiting List No</th>
                                <th>Child Name</th>
                                <th>Father Name</th>
                                <th>Mother Name</th>
                                <th>Notes</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $counter = 1;
                            @endphp
                            @foreach ($applications->where('stage_id', 30) as $applicationpurchased)
                                <tr>
                                    <td>{{ $counter++ }}</td>
                                    <td>
                                        @foreach ($lead as $leads)
                                            @if ($leads->id === $applicationpurchased->lead_id)
                                                <a href="{{ route('admin.leads.show', ['lead' => $leads->id]) }}">
                                                    {{ $leads->ref_num }}
                                                </a>
                                            @endif
                                        @endforeach
                                    </td>

                                    <td>
                                        @if ($applicationpurchased->application_no)
                                            {{ $applicationpurchased->application_no }}
                                        @else
                                            {{-- Search for matching application_no for the same lead_id --}}
                                            @php
                                                $matchedApplication = $applications->firstWhere('lead_id', $applicationpurchased->lead_id);
                                            @endphp
                                            {{ $matchedApplication->application_no ?? 'No Application Number' }}
                                        @endif
                                    </td>
                                    <td>{{ $applicationpurchased->waiting_list ?? '' }}</td>

                                    <td>
                                        @if (!is_null($applicationpurchased->lead) && !is_null($applicationpurchased->lead->student_details))
                                            @if (is_array($applicationpurchased->lead->student_details) && count($applicationpurchased->lead->student_details) > 0)
                                                <ul>
                                                    @foreach ($applicationpurchased->lead->student_details as $student)
                                                        <li>{{ $student['name'] ?? 'No name' }}</li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                No children listed.
                                            @endif
                                        @else
                                            'Lead or student details not available.'
                                        @endif
                                    </td>

                                    <td>
                                        @foreach ($lead as $leads)
                                            @if ($leads->id === $applicationpurchased->lead_id)
                                                {{ $leads->father_details['name'] ?? '' }}
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach ($lead as $leads)
                                            @if ($leads->id === $applicationpurchased->lead_id)
                                                {{ $leads->mother_details['name'] ?? '' }}
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>{{ $applicationpurchased->notes }}</td>
                                    <td>{{ $applicationpurchased->created_at->format('Y-m-d') }}</td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('scripts')
    @parent
    <script>
        $(function() {
            // Initialize DataTables for all tabs
            $('#allApplicationsTable').DataTable();
            $('#filteredApplicationsTable').DataTable();
            $('#acceptedApplicationsTable').DataTable();
        });
        let table = $('.datatable-applicationpurchased').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
    </script>
@endsection
