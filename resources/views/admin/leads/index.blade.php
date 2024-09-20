{{-- @extends('layouts.admin')
@section('content')
    @includeIf('admin.leads.partials.header')
    @if ($lead_view == 'list')
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <div class="card-body">
                        @includeIf('admin.leads.partials.lead_table.lead_table')
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
@section('scripts')
@parent
<script>
    $(function () {
       @includeIf('admin.leads.partials.common_lead_js')
    });
</script>
@endsection --}}
@extends('layouts.admin')
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/fixedheader/3.1.7/css/fixedHeader.dataTables.min.css">
<script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>

<script type="text/javascript" src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/fixedheader/3.1.7/js/dataTables.fixedHeader.min.js"></script>

@section('content')
    <div class="row">
        <div class="col-10">
            @if (auth()->user()->is_superadmin || auth()->user()->is_frontoffice || auth()->user()->is_admissionteam)
                {{-- <div class="card-header"> --}}
                <a class="btn btn-success float-right" href="{{ route('admin.leads.create', $project->id) }}">
                    {{ trans('global.add') }} {{ trans('cruds.lead.title_singular') }}
                </a>
                {{-- <a class="btn btn-success float-right" href="{{ route('admin.leads.create', $project->id) }}">
                    {{ trans('global.add') }} {{ trans('cruds.lead.title_singular') }}
                </a> --}}
                {{-- </div> --}}
            @endif
            <br>
        </div>
        <div class="col-2">

            <div class="d-flex justify-content-end mb-3">
                <a href="{{ route('admin.leads.projects', ['view' => 'list', 'id' => $project->id]) }}"
                    class="btn btn-outline-secondary @if ($lead_view === 'list') active @endif">List View</a>
                <a href="{{ route('admin.leads.projects', ['view' => 'kanban', 'id' => $project->id]) }}"
                    class="btn btn-outline-secondary @if ($lead_view === 'kanban') active @endif">Kanban View</a>
            </div>
        </div>
    </div>

    <div class="text">
        @if ($lead_view == 'list')
            <div class="container-fluid h-100 mt-3">
                <div class="card-body">
                    <div class="table-responsive">
                        @include('admin.leads.lead_table')
                    </div>
                </div>
            </div>
        @elseif($lead_view == 'kanban')

            <body>
                {{-- <div class="container mt-8"> --}}
                <div class="class">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="bbc-tab" data-toggle="tab" href="#bbc" role="tab"
                                aria-controls="bbc" aria-selected="true">For BBC</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="walkins-tab" data-toggle="tab" href="#walkins" role="tab"
                                aria-controls="walkins" aria-selected="false">For Walkins</a>
                        </li>
                    </ul>
                    <!-- Tabs Content -->
                    <div class="tab-content" id="myTabContent">
                        <!-- BBC Tab -->
                        <div class="tab-pane fade show active" id="bbc" role="tabpanel" aria-labelledby="bbc-tab">
                            <div class="d-flex overflow-x-auto kanban-container">
                                @foreach ($lead_stages as $stage => $lead_stage)
                                    @php
                                        if ($stage == 'no_stage') {
                                            $leads = $stage_wise_leads[''] ?? [];
                                        } else {
                                            $leads = $stage_wise_leads[$stage] ?? [];
                                        }
                                    @endphp
                                    @if (!empty($leads))
                                        <div class="card card-row {{ $lead_stage['class'] ?? 'card-secondary' }} mx-2">
                                            <div class="card-header">
                                                <h3 class="card-title">
                                                    {{ $lead_stage['title'] ?? ucfirst(str_replace('_', ' ', $stage)) }}
                                                    <span class="lead-count">({{ count($leads) }})</span>
                                                </h3>
                                            </div>
                                            <div class="card-body flex-column">
                                                @forelse($leads as $lead)
                                                    @php
                                                        $url = \App\Models\Url::find($lead->project_id);
                                                    @endphp
                                                    <div
                                                        class="card card-outline {{ $lead_stage['class'] ?? 'card-secondary' }} mb-3">
                                                        <div class="card-header">
                                                            <h5 class="card-title">
                                                                {{-- {{ ucfirst($lead->father_details['name'] ?? '') }} --}}
                                                                <small>
                                                                    {{ $lead->ref_num ?? '' }}
                                                                </small>
                                                            </h5>
                                                            <div class="card-tools">
                                                                <input class="form-check-input lead_ids" type="checkbox"
                                                                    id="{{ $lead->id }}" value="{{ $lead->id }}">
                                                                <a href="{{ route('admin.leads.show', [$lead->id]) }}"
                                                                    class="btn btn-tool btn-link">
                                                                    <i class="far fa-eye text-primary"></i>
                                                                </a>
                                                                @if (auth()->user()->is_superadmin)
                                                                    <a href="{{ route('admin.leads.edit', [$lead->id]) }}"
                                                                        class="btn btn-tool">
                                                                        <i class="far fa-edit text-info"></i>
                                                                    </a>
                                                                    <form
                                                                        action="{{ route('admin.leads.destroy', [$lead->id]) }}"
                                                                        method="POST"
                                                                        onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                                                        style="display: inline-block;">
                                                                        <input type="hidden" name="_method" value="DELETE">
                                                                        <input type="hidden" name="_token"
                                                                            value="{{ csrf_token() }}">
                                                                        <button type="submit" class="btn btn-tool">
                                                                            <i class="far fa-trash-alt text-danger"></i>
                                                                        </button>
                                                                    </form>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <label>NAME:</label>
                                                                    {{ $lead->father_details['name'] ?? ($lead->mother_details['name'] ?? '') }}<br>

                                                                    <i class='fas fa-phone-volume'></i>
                                                                    {{ $lead->father_details['phone'] ?? ($lead->mother_details['phone'] ?? '') }}<br>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <i class="fa fa-envelope"></i>
                                                                    {{ $lead->mother_details['email'] ?? ($lead->mother_details['email'] ?? '') }}<br>
                                                                </div>
                                                                <div id="additional-details-{{ $lead->id }}"
                                                                    class="additional-details-container">
                                                                    <div class="col-md-12"></div>
                                                                    <div class="col-md-12"></div>
                                                                    @if ($lead->student_details)
                                                                        @php
                                                                            $studentDetails = is_array(
                                                                                $lead->student_details,
                                                                            )
                                                                                ? $lead->student_details
                                                                                : json_decode(
                                                                                    $lead->student_details,
                                                                                    true,
                                                                                );

                                                                            if (isset($studentDetails['name'])) {
                                                                                $studentDetails = [$studentDetails];
                                                                            }
                                                                        @endphp

                                                                        @if (!empty($studentDetails))
                                                                            @foreach ($studentDetails as $child)
                                                                                <div>
                                                                                    <strong>Name:</strong>
                                                                                    {{ $child['name'] ?? 'N/A' }}<br>
                                                                                    <strong>Date of Birth:</strong>
                                                                                    {{ $child['dob'] ?? 'N/A' }}<br>
                                                                                    <strong>Grade:</strong>
                                                                                    {{ $child['grade'] ?? 'N/A' }}<br>
                                                                                    <strong>Old School:</strong>
                                                                                    {{ $child['old_school'] ?? 'N/A' }}<br>
                                                                                    <strong>Reason for Quitting:</strong>
                                                                                    {{ $child['reason_for_quit'] ?? 'N/A' }}<br>
                                                                                </div>
                                                                                <hr>
                                                                                <!-- Separator between each child's details -->
                                                                            @endforeach
                                                                        @else
                                                                            No valid student details available.
                                                                        @endif
                                                                    @else
                                                                        No student details available.
                                                                    @endif

                                                                    <br>
                                                                    @if ($url)
                                                                        <!-- Check if the URL exists -->
                                                                        <strong>Project:</strong>{{ $url->project }}<br>
                                                                        <strong>Campaign:</strong>{{ $url->campaign_name }}<br>
                                                                        <strong>Source:</strong>{{ $url->source_name }}<br>
                                                                        <strong>Sub
                                                                            Source:</strong>{{ $url->source_name }}<br>
                                                                    @else
                                                                        <strong>Project:</strong> Not Available<br>
                                                                        <strong>Campaign:</strong> Not Available<br>
                                                                        <strong>Source:</strong> Not Available<br>
                                                                        <strong>Sub Source:</strong> Not Available<br>
                                                                    @endif
                                                                </div>
                                                                <!-- End of additional details section -->
                                                                <div class="col-md-12 text-center">
                                                                    <button class="btn btn-info btn-xs toggle-details-btn"
                                                                        data-target="{{ $lead->id }}">
                                                                        <i class="fas fa-plus"></i>
                                                                        <!-- Initial plus icon -->
                                                                        <i class="fas fa-minus" style="display: none;"></i>
                                                                        <!-- Minus icon initially hidden -->
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @empty
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <h5 class="card-title">
                                                                @lang('messages.no_leads_found')
                                                            </h5>
                                                        </div>
                                                    </div>
                                                @endforelse
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        <!-- Walkins Tab -->
                        <div class="tab-pane fade" id="walkins" role="tabpanel" aria-labelledby="walkins-tab">
                            <div class="d-flex overflow-x-auto kanban-container">
                                @foreach ($lead_stages as $stage => $lead_stage)
                                    @php
                                        if ($stage == 'no_stage') {
                                            $leads = $stage_wise_leads[''] ?? [];
                                        } else {
                                            $leads = $stage_wise_leads[$stage] ?? [];
                                        }
                                        // Filter leads with walkin_no = 1
                                        $leads = collect($leads)->filter(function ($lead) {
                                            return $lead->walkin_no == 1;
                                        });
                                    @endphp
                                    @if (!empty($leads))
                                        <div class="card card-row {{ $lead_stage['class'] ?? 'card-secondary' }} mx-2">
                                            <div class="card-header">
                                                <h3 class="card-title">
                                                    {{ $lead_stage['title'] ?? ucfirst(str_replace('_', ' ', $stage)) }}
                                                    <span class="lead-count">({{ count($leads) }})</span>
                                                </h3>
                                            </div>
                                            <div class="card-body d-flex flex-column">
                                                @forelse($leads as $lead)
                                                    <div
                                                        class="card card-outline {{ $lead_stage['class'] ?? 'card-secondary' }} mb-3">
                                                        <div class="card-header">
                                                            <h5 class="card-title">
                                                                <small>
                                                                    {{ $lead->ref_num ?? '' }}
                                                                </small>
                                                            </h5>
                                                            <div class="card-tools">
                                                                <input class="form-check-input lead_ids" type="checkbox"
                                                                    id="{{ $lead->id }}"
                                                                    value="{{ $lead->id }}">
                                                                <a href="{{ route('admin.leads.show', [$lead->id]) }}"
                                                                    class="btn btn-tool btn-link">
                                                                    <i class="far fa-eye text-primary"></i>
                                                                </a>
                                                                @if (auth()->user()->is_superadmin)
                                                                    <a href="{{ route('admin.leads.edit', [$lead->id]) }}"
                                                                        class="btn btn-tool">
                                                                        <i class="far fa-edit text-info"></i>
                                                                    </a>
                                                                    <form
                                                                        action="{{ route('admin.leads.destroy', [$lead->id]) }}"
                                                                        method="POST"
                                                                        onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                                                        style="display: inline-block;">
                                                                        <input type="hidden" name="_method"
                                                                            value="DELETE">
                                                                        <input type="hidden" name="_token"
                                                                            value="{{ csrf_token() }}">
                                                                        <button type="submit" class="btn btn-tool">
                                                                            <i class="far fa-trash-alt text-danger"></i>
                                                                        </button>
                                                                    </form>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="col-md-12">
                                                                        <label>NAME:</label>
                                                                        {{ $lead->father_details['name'] ?? $lead->mother_details['name'] ?? $lead->guardian_details['name'] ?? '' }}<br>
                                                                        <i class='fas fa-phone-volume'></i>
                                                                        {{ $lead->father_details['phone'] ?? $lead->mother_details['phone'] ?? $lead->guardian_details['phone'] ?? '' }}<br>
                                                                    </div>
                                                                    
                                                                    <div class="col-md-12">
                                                                        <i class="fa fa-envelope"></i>
                                                                        {{ $lead->father_details['email'] ?? $lead->mother_details['email'] ?? $lead->guardian_details['email'] ?? '' }}<br>
                                                                    </div>
                                                                    
                                                                <div id="additionallll-details-{{ $lead->id }}"
                                                                    class="additionallll-details-container">
                                                                    <div class="col-md-12"></div>
                                                                    <div class="col-md-12"></div>
                                                                    @if ($lead->student_details)
                                                                        @php
                                                                            $studentDetails = is_array(
                                                                                $lead->student_details,
                                                                            )
                                                                                ? $lead->student_details
                                                                                : json_decode(
                                                                                    $lead->student_details,
                                                                                    true,
                                                                                );

                                                                            if (isset($studentDetails['name'])) {
                                                                                $studentDetails = [$studentDetails];
                                                                            }
                                                                        @endphp

                                                                        @foreach ($studentDetails as $child)
                                                                            <div>
                                                                                <strong>Name:</strong>
                                                                                {{ $child['name'] ?? 'N/A' }}<br>
                                                                                <strong>Date of Birth:</strong>
                                                                                {{ $child['dob'] ?? 'N/A' }}<br>
                                                                                <strong>Grade:</strong>
                                                                                {{ $child['grade'] ?? 'N/A' }}<br>
                                                                                <strong>Old School:</strong>
                                                                                {{ $child['old_school'] ?? 'N/A' }}<br>
                                                                                <strong>Reason for Quitting:</strong>
                                                                                {{ $child['reason_for_quit'] ?? 'N/A' }}<br>
                                                                            </div>
                                                                            <hr>
                                                                        @endforeach
                                                                    @else
                                                                        No student details available.
                                                                    @endif

                                                                    <br>
                                                                    @if ($url)
                                                                        <strong>Project:</strong>{{ $url->project }}<br>
                                                                        <strong>Campaign:</strong>{{ $url->campaign_name }}<br>
                                                                        <strong>Source:</strong>{{ $url->source_name }}<br>
                                                                        <strong>Sub
                                                                            Source:</strong>{{ $url->source_name }}<br>
                                                                    @else
                                                                        <strong>Project:</strong> Not Available<br>
                                                                        <strong>Campaign:</strong> Not Available<br>
                                                                        <strong>Source:</strong> Not Available<br>
                                                                        <strong>Sub Source:</strong> Not Available<br>
                                                                    @endif
                                                                </div>
                                                                <div class="col-md-12 text-center">
                                                                    <button class="btn btn-info btn-xs toggle-details-btn"
                                                                        data-target="{{ $lead->id }}">
                                                                        <span class="toggle-icon">
                                                                            <i class="fas fa-plus"></i>
                                                                            <i class="fas fa-minus"
                                                                                style="display: none;"></i>
                                                                        </span>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @empty
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <h5 class="card-title">@lang('messages.no_leads_found')</h5>
                                                        </div>
                                                    </div>
                                                @endforelse
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Include Bootstrap JS and dependencies (use a CDN or your local files) -->
                <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
                <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
            </body>

            </html>
        @endif


    @endsection
    @section('scripts')
        <script>
            $(document).ready(function() {
                $('.additional-details-container').hide();

                $('.toggle-details-btn').click(function() {
                    var leadId = $(this).data('target');
                    var detailsContainer = $('#additional-details-' + leadId);

                    // Toggle the visibility of the details container
                    detailsContainer.toggle();

                    // Toggle the visibility of the plus and minus icons
                    $(this).find('.fa-plus').toggle();
                    $(this).find('.fa-minus').toggle();
                });
            });
        </script>
              <script>
                $(document).ready(function() {
                    $('.additionallll-details-container').hide();

                    $('.toggle-details-btn').click(function() {
                        var leadId = $(this).data('target');
                        var detailsContainer = $('#additionallll-details-' + leadId);

                        // Toggle the visibility of the details container
                        detailsContainer.toggle();

                        // Toggle the visibility of the plus and minus icons
                        $(this).find('.fa-plus').toggle();
                        $(this).find('.fa-minus').toggle();
                    });
                });
            </script>
        <script>
            // document.getElementById('select-all').onclick = function() {
            //     var checkboxes = document.getElementsByName('lead_ids[]');
            //     for (var checkbox of checkboxes) {
            //         checkbox.checked = this.checked;
            //     }
            // }
            document.getElementById('searchInput').addEventListener('keyup', function() {
                var input, filter, table, tr, td, i, j, txtValue;
                input = document.getElementById('searchInput');
                filter = input.value.toUpperCase();
                table = document.getElementById('leadTable');
                tr = table.getElementsByTagName('tr');

                for (i = 0; i < tr.length; i++) {
                    tr[i].style.display = 'none';
                    td = tr[i].getElementsByTagName('td');
                    for (j = 0; j < td.length; j++) {
                        if (td[j]) {
                            if (td[j].innerText.toUpperCase().indexOf(filter) > -1) {
                                tr[i].style.display = '';
                                break;
                            }
                        }
                    }
                }
            });
        </script>
    @endsection
    <style>
        .kanban-container {
            display: flex;
            flex-wrap: nowrap;
            overflow-x: auto;
            /* Enable horizontal scrolling for the container */
            max-width: 100%;
            /* Ensure the container fits within the page width */
            padding-bottom: 10px;
            /* Add space for scrollbar */
        }

        .card-body {
            overflow-y: auto;
            /* Only vertical scrolling for long content */
            max-height: 500px;
            /* Restrict height to ensure vertical scroll if needed */
            white-space: normal;
            /* Ensure long text wraps to prevent horizontal scroll */
            word-wrap: break-word;
            /* Ensure long words are broken into new lines */
        }

        /* For other inline elements like tables or long URLs, force them to fit */
        /* .card-body table, */
        .card-body pre,
        .card-body code {
            display: block;
            max-width: 100%;
            /* Ensure they don't overflow horizontally */
            overflow-x: auto;
            /* If absolutely necessary, provide horizontal scrolling for specific large content */
        }

        .card-row {
            flex: 1 0 20%;
            /* Each stage takes 20% width */
            box-sizing: border-box;
            margin-right: 20px;
            /* Space between stages */
            overflow-x: hidden;
            /* Prevent horizontal scroll on each card-row */
        }



        /* Responsive adjustments for smaller screens */
        @media (max-width: 1500px) {
            .card-row {
                flex: 1 0 auto;
                /* Allow stages to resize on smaller screens */
            }
        }
    </style>
    {{-- .kanban-container {
    display: flex;
    flex-wrap: wrap; /* Allow wrapping instead of horizontal scroll */
    max-width: 100%; /* Ensure the container fits within the page width */
    padding-bottom: 10px;
}

.card-row {
    flex: 1 0 20%; /* Adjust the width of each card row */
    box-sizing: border-box;
    margin-right: 20px;
    margin-bottom: 20px; /* Add space between rows when wrapping */
}

.card-body {
    overflow-y: auto; /* Only allow vertical scrolling */
    max-height: 500px; /* Restrict the height if needed */
}

/* Responsive adjustments for smaller screens */
@media (max-width: 1500px) {
    .card-row {
        flex: 1 0 100%; /* Allow cards to take full width on smaller screens */
        max-width: none;
    }
} --}}
