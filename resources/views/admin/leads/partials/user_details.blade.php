<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Stages Popup</title>

<div class="card card-primary card-outline">
    <div class="card-body box-profile">
        <div style="text-align: right;">
            <input type="hidden" name="lead_id" value="{{ $lead->id }}">
            <div class="row">
                <div class="col-6">
                    @php
                        $avatar = 'https://ui-avatars.com/api/?background=random&font-size=0.7&name=' . $lead->name;
                    @endphp
                    <img class="profile-user-img img-fluid img-circle" src="{{ $avatar }}"
                        alt="{{ $lead->name ?? '' }}">
                </div>

                <div class="col-6">
                    @if (auth()->user()->is_superadmin || auth()->user()->is_presales)
                        <div class="lead-item">
                            <!-- Button to initiate the call -->
                            <a href="javascript:void(0);" onclick="initiateCall({{ $lead->id }})"
                                class="btn btn-primary">
                                <i class="fas fa-phone fa-flip-horizontal"></i>
                            </a>
                        </div>
                        <br>
                        {{-- @if (!auth()->user()->is_client)
                            <button type="submit" class="float-right edit-button btn-primary">Edit</button>
                        @endif --}}
                    @endif
                </div>

                <!-- Placeholder for the disclaimer message -->
                <div id="disclaimer" style="display:none; margin-top: 20px; color: red;"></div>

                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script>
                    function initiateCall(leadId) {
                        $.ajax({
                            url: `/admin/leads/${leadId}/initiate-call`,
                            type: 'GET',
                            success: function(response) {
                                $('#disclaimer').text(response.message).show();
                            },
                            error: function(xhr) {
                                $('#disclaimer').text(xhr.responseJSON.message).show();
                            }
                        });
                    }
                </script>
            </div>
        </div>
        <br>
        <form method="POST" action="{{ route('admin.leads.update', [$lead->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <button type="submit" class="float-right save-button btn-success" style="display:none;">Save</button>
            {{-- <h3 class="profile-username text-center">
                {{ $lead->name ?? '' }}
            </h3> --}}
            <ul class="list-group list-group-unbordered mb-3">
                <li class="list-group-item">
                    <b>{{ trans('messages.ref_num') }}</b>
                    <a class="float-right">{{ $lead->ref_num }}</a>
                </li>
                <li class="list-group-item">
                    <b>Created At</b>
                    <a class="float-right">
                        {{ $lead->created_at ?? '' }}
                    </a>
                </li>
                <li class="list-group-item">
                    <b class="center-text">Father Details</b><br>
                    <div class="father-details">
                        <strong>Name:</strong>
                        <div class="float-right"> {{ $lead->father_details['name'] ?? '' }}</div><br>
                        <strong>Email:</strong>
                        <div class="float-right"> {{ $lead->father_details['email'] ?? '' }}</div><br>
                        <strong>Phone:</strong>
                        <div class="float-right"> {{ $lead->father_details['phone'] ?? '' }}</div><br>
                        <strong>Occupation:</strong>
                        <div class="float-right"> {{ $lead->father_details['occupation'] ?? '' }}</div><br>
                        <strong>Income:</strong>
                        <div class="float-right"> {{ $lead->father_details['income'] ?? '' }}</div><br>
                    </div>
                </li>
                <li class="list-group-item">
                    <b class="text-center">Mother Details</b><br>
                        <strong>Name:</strong>
                        <div class="float-right"> {{ $lead->mother_details['name'] ?? '' }}</div><br>
                        <strong>Email:</strong>
                        <div class="float-right"> {{ $lead->mother_details['email'] ?? '' }}</div><br>
                        <strong>Phone:</strong>
                        <div class="float-right"> {{ $lead->mother_details['phone'] ?? '' }}</div><br>
                        <strong>Occupation:</strong>
                        <div class="float-right"> {{ $lead->mother_details['occupation'] ?? '' }}</div><br>
                        <strong>Income:</strong>
                        <div class="float-right"> {{ $lead->mother_details['income'] ?? '' }}</div><br>
                </li>
                <li class="list-group-item">
                    <b class="text-center">Guardian Details</b><br>
                        <strong>Name:</strong>
                        <div class="float-right"> {{ $lead->guardian_details['name'] ?? '' }}</div><br>
                        <strong>Email:</strong>
                        <div class="float-right"> {{ $lead->guardian_details['email'] ?? '' }}</div><br>
                        <strong>Phone:</strong>
                        <div class="float-right"> {{ $lead->guardian_details['phone'] ?? '' }}</div><br>
                        <strong>Relationship:</strong>
                        <div class="float-right"> {{ $lead->guardian_details['relationship'] ?? '' }}</div><br>
                        <strong>Occupation:</strong>
                        <div class="float-right"> {{ $lead->guardian_details['occupation'] ?? '' }}</div><br>
                        <strong>Income:</strong>
                        <div class="float-right"> {{ $lead->guardian_details['income'] ?? '' }}</div><br>

                </li>
                <style>
                    .row-end {
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                    }
                    .row-end .label {
                        flex: 0 0 40%; /* Adjust the percentage as needed */
                        font-weight: bold;
                    }
                    .row-end .value {
                        flex: 1;
                        text-align: right;
                    }
                </style>

                <li class="list-group-item">
                    <b class="text-center">Child Details</b><br>
                    @if ($lead->student_details)
                        @php
                            $studentDetails = is_array($lead->student_details)
                                ? $lead->student_details
                                : json_decode($lead->student_details, true);
                            if (isset($studentDetails['name'])) {
                                $studentDetails = [$studentDetails];
                            }
                        @endphp
                        @if (!empty($studentDetails))
                            @foreach ($studentDetails as $child)
                                <div class="row-end mb-2">
                                    <div class="label">Name:</div>
                                    <div class="value">{{ $child['name'] ?? '' }}</div>
                                </div>
                                <div class="row-end mb-2">
                                    <div class="label">Date of Birth:</div>
                                    <div class="value">{{ $child['dob'] ?? '' }}</div>
                                </div>
                                <div class="row-end mb-2">
                                    <div class="label">Grade:</div>
                                    <div class="value">{{ $child['grade'] ?? '' }}</div>
                                </div>
                                <div class="row-end mb-2">
                                    <div class="label">Old School:</div>
                                    <div class="value">{{ $child['old_school'] ?? '' }}</div>
                                </div>
                                <div class="row-end mb-2">
                                    <div class="label">Reason for Quitting:</div>
                                    <div class="value">{{ $child['reason_for_quit'] ?? '' }}</div>
                                </div>
                            @endforeach
                        @else
                            No valid student details available.
                        @endif
                    @else
                        No student details available.
                    @endif
                </li>
                <li class="list-group-item">
                    @if ($applications->isEmpty())
                        <p>No applications found for this lead.</p>
                    @else
                        <strong>Application No:</strong>
                        @php
                            $shown = false;  // Initialize a flag
                        @endphp
                        @foreach ($applications as $application)
                            @if (!$shown)  <!-- Check if the application number has been displayed -->
                                <a class="float-right">
                                    {{ $application->application_no }}
                                </a>
                                @php
                                    $shown = true;  // Set the flag to true after displaying the first application number
                                @endphp
                            @endif
                        @endforeach
                    @endif
                </li>

                <li class="list-group-item">
                    <b>Project Details</b>
                    <a class="float-right">
                        <strong>Campaign:</strong>
                        {{ $campaignName }}<br>
                        <strong>Source:</strong>
                        {{ $sourceName }}<br>
                        <strong>Sub Source:</strong>
                        {{ $lead->sub_source_name }}
                    </a>
                </li>
                 <li class="list-group-item">
                    <b class="text-center">Additional Details</b><br>
                        <strong>City:</strong>
                        <div class="float-right"> {{ $lead->additional_details['city'] ?? '' }}</div><br>
                        <strong>Browser:</strong>
                        <div class="float-right"> {{ $lead->additional_details['browser'] ?? '' }}</div><br>
                        <strong>OS:</strong>
                        <div class="float-right"> {{ $lead->additional_details['user_os'] ?? '' }}</div><br>
                        <strong>Traffice Source:</strong>
                        <div class="float-right"> {{ $lead->additional_details['traffic_src'] ?? '' }}</div><br>
                        
                        <strong>Refferal Url:</strong><br>
                        <div class="float-right"> {{ $lead->additional_details['ref'] ??  '' }}</div><br>
                        <strong>Landing Page Url:</strong><br>
                        <div class="float-right"> {{ $lead->additional_details['landing_page'] ??  '' }}</div><br>
                        <strong>IP Address:</strong>
                        <div class="float-right"> {{ $lead->ip_address ?? '' }}</div><br>
                        <strong>Submission Number:</strong>
                        <div class="float-right"> {{ $lead->additional_details['sub_no'] ?? '' }}</div><br>
                        <strong>Date and time:</strong>
                        <div class="float-right"> {{ $lead->additional_details['date_time'] ?? '' }}</div><br>
                <li class="list-group-item">
                    <b>Address</b>
                        <div class="float-right"> {{ $lead->common_details['address'] ?? '' }}</div><br>
                    </a>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <b>Stage</b>
                    <div class="d-flex align-items-center">
                        <span style="margin-right: 1cm;">{{ $lead->parentStage->name ?? '' }}</span>
                        @if (auth()->user()->is_superadmin || auth()->user()->is_frontoffice|| auth()->user()->is_admissionteam||auth()->user()->is_presales||auth()->user()->is_agencyanalytics)
                            <button type="button" class="btn btn-primary rounded-circle p-2 d-flex align-items-center"
                                data-toggle="modal" data-target="#stageModal" style="width: 30px; height: 30px;">
                                <i class="fas fa-play fa-sm mx-auto"></i>
                            </button>
                        @endif
                    </div>
                </li>
                <li class="list-group-item">
                    <b>@lang('messages.customer_comments')</b>
                    <a class="float-right">
                        {!! $lead->comments ?? '' !!}
                    </a>
                </li>
                <li class="list-group-item">
                    <b>@lang('messages.added_by')</b>
                    <a class="float-right">
                        {{ $lead->createdBy ? $lead->createdBy->name : '' }}
                    </a>
                </li>

                <div class="modal fade" id="stageModal" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Child Stages</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form method="POST" action="{{ route('admin.leads.update', [$lead->id]) }}"
                                enctype="multipart/form-data">
                                @method('PUT')
                                @csrf
                                <div class="modal-body">
                                    <p><b>Parent Stage:</b> {{ $lead->parentStage->name ?? ' ' }}</p>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="tag_id">Select Tag:</label>
                                            <select name="tag_id" id="tag_id" class="form-control">
                                                <option value="" selected disabled>Please Select</option>
                                                @foreach ($tags as $tag)
                                                    <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <input type="hidden" name="stage_id" id="selected_stage_id"
                                            value="{{ $lead->parentstage->id ?? '' }}">
                                        <div class="form-group">
                                            <label for="child_stage_id">Select Stage:</label>
                                            <select name="child_stage_id" id="child_stage_id" class="form-control"
                                                onchange="checkParentStageId(this)">
                                                <option value="" selected disabled>Please Select</option>
                                            </select>
                                            {{-- <div class="modal-footer">
                                                <button class="btn btn-danger" type="submit">
                                                    Save
                                                </button>
                                            </div> --}}
                                        </div>
                                    </div>
                                </div>
                            </form>
                            @includeIf('admin.leads.stage_forms.followup_form')
                            @includeIf('admin.leads.stage_forms.sitevisit_form')
                            @includeIf('admin.leads.stage_forms.reschedule_form')
                            @includeIf('admin.leads.stage_forms.spam_form')
                            @includeIf('admin.leads.stage_forms.application_form')
                            @includeIf('admin.leads.stage_forms.application_not_purchased_form')
                            @includeIf('admin.leads.stage_forms.site_not_visited_form')
                            @includeIf('admin.leads.stage_forms.site_visit_conducted_form')
                            @includeIf('admin.leads.stage_forms.cancelled_form')
                            @includeIf('admin.leads.stage_forms.lost_form')
                            @includeIf('admin.leads.stage_forms.admission_form')
                            @includeIf('admin.leads.stage_forms.admission_followup_form')
                            @includeIf('admin.leads.stage_forms.not_qualified_form')
                            @includeIf('admin.leads.stage_forms.admission_withdrawn_form')
                            @includeIf('admin.leads.stage_forms.future_prospect_form')
                            @includeIf('admin.leads.stage_forms.rnr_form')
                            @includeIf('admin.leads.stage_forms.application_accepted_form')
                            @includeIf('admin.leads.stage_forms.qualified_followup_form')
                            @includeIf('admin.leads.stage_forms.interview_followup_form')

                        </div>
                    </div>
                </div>
            </ul>
        </form>
    </div>



</div>

@section('scripts')
    @includeIf('admin.leads.partials.stage_form_js')
@endsection
