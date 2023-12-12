@extends('layouts.admin')

@section('content')
    <h3>Site Visit And Reschedule</h3>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Lead Site Visit Table</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="date_range">Select a date</label>
                        <select class="form-control" id="date_range">
                            <option value="today">Today</option>
                            <option value="yesterday">Yesterday</option>
                            <option value="last_week">Last Week</option>
                            <option value="last_30_days">Last 30 Days</option>
                            <option value="last_60_days" selected>Last 60 Days</option>
                            <option value="last_year">Last One Year</option>
                            <option value="custom">Custom Range</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4" id="custom_range_container" style="display:none;">
                    <div class="form-group">
                        <label for="custom_range">Custom Range</label>
                        <input class="form-control" type="text" name="custom_range" id="custom_range"
                            value="{{ old('custom_range') }}">
                    </div>
                </div>
            </div>

            <table class="table" id="siteVisitTable">
                <thead>
                    <tr>
                        <th>Reference Number</th>
                        <th>Parent Name</th>
                        <th>Campaign Name</th>
                        <th>Site Visit Date</th>
                        <th>Site Visit Time</th>
                        <th>Supervise By</th>
                        <th>Notes</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sitevisits as $sitevisit)
                        <tr data-created-at="{{ $sitevisit->created_at->format('Y-m-d') }}">
                            <td>
                                @foreach ($lead as $leads)
                                    @if ($leads->id === $sitevisit->lead_id)
                                        {{ $leads->ref_num }}
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                @foreach ($lead as $leads)
                                    @if ($leads->id === $sitevisit->lead_id)
                                        {{ $leads->name }}
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                @foreach ($lead as $leads)
                                    @if ($leads->id === $sitevisit->lead_id)
                                        {{ $leads->campaign->campaign_name }}
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                @foreach ($lead as $leads)
                                    @if ($leads->id === $sitevisit->lead_id)
                                        {{ $sitevisit->follow_up_date }}
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                {{ $sitevisit->follow_up_time }}
                            </td>
                            <td>
                                {{ $sitevisit->users->representative_name }}
                            </td>
                            <td>
                                @foreach ($lead as $leads)
                                    @if ($leads->id === $sitevisit->lead_id)
                                        {{ $sitevisit->notes }}
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                {{ $sitevisit->created_at->format('Y-m-d') }}
                            </td>
                            <td>
                                <div class="btn-group" role="group" aria-label="Site Visit Actions">
                                    <form action="{{ route('admin.sitevisits.reschedule', $sitevisit->id) }}" method="POST">
                                        @csrf
                                        @if (!auth()->user()->is_client && $sitevisit->lead && in_array($sitevisit->lead->parent_stage_id, [11, 20, 27]))
                                        <button type="button" class="btn btn-sm btn-warning" disabled>
                                                Reschedule
                                            </button>
                                        @elseif (!auth()->user()->is_client && $sitevisit->lead && $sitevisit->lead->parent_stage_id != 20)
                                            <button type="button" class="btn btn-sm btn-warning" data-toggle="modal"
                                                    data-target="#editModal{{ $sitevisit->id }}">
                                                Reschedule
                                            </button>
                                        @endif
                                    </form>
                                    <form action="{{ route('admin.sitevisits.conducted', $sitevisit->id) }}" method="POST">
                                        @csrf
                                        @if (!auth()->user()->is_superadmin && $sitevisit->lead && in_array($sitevisit->lead->parent_stage_id, [11,12,26,27]))
                                            <button type="button" class="btn btn-sm btn-primary" disabled>
                                                Conducted
                                            </button>
                                        @elseif (!auth()->user()->is_superadmin && $sitevisit->lead && $sitevisit->lead->parent_stage_id != 12)
                                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#conductedModal">
                                                Conducted
                                            </button>
                                        @endif
                                    </form>
                                    <div class="mr-2"></div>

                                    <form action="{{ route('admin.sitevisits.notvisited', $sitevisit->id) }}" method="POST">
                                        @csrf
                                        @if (!auth()->user()->is_superadmin && $sitevisit->lead && in_array($sitevisit->lead->parent_stage_id, [11, 12, 26, 27]))
                                            <button type="button" class="btn btn-sm btn-danger" disabled>
                                                Not Visited
                                            </button>
                                        @elseif (!auth()->user()->is_superadmin && $sitevisit->lead && $sitevisit->lead->parent_stage_id != 12)
                                            <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#notVisitedModel">
                                                Not Visited
                                            </button>
                                        @endif
                                    </form>

                                    <!-- Add margin to create space -->
                                    <div class="mr-2"></div>

                                    <div class="btn-group" role="group" aria-label="Site Visit Actions">
                                        <form method="POST" action="{{ route('admin.sitevisits.cancel', $sitevisit->id) }}" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            {{-- <input type="hidden" name="lead_id" value="{{ $sitevisit->lead_id }}"> --}}
                                            @if (!auth()->user()->is_client && $sitevisit->lead && in_array($sitevisit->lead->parent_stage_id, [11, 20, 27]))
                                                <button type="button" class="btn btn-sm btn-danger" disabled>
                                                    Cancel
                                                </button>
                                            @elseif (!auth()->user()->is_client && $sitevisit->lead && $sitevisit->lead->parent_stage_id != 20)
                                                <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#cancelModal">
                                                    Cancel
                                                </button>
                                            @endif



                                            <!-- Modal -->
                                            <div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="cancelModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="cancelModalLabel">Confirmation</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <input type="hidden" name="parent_stage_id" value="20">

                                                        <div class="modal-body">
                                                            Are you sure you want to cancel?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-danger">Confirm</button>
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                </div>
                                <div class="modal fade" id="conductedModal" tabindex="-1" role="dialog" aria-labelledby="conductedModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="conductedModalLabel">Confirmation</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>

                                            <div class="modal-body">
                                                Are you sure you want to mark this visit as conducted?
                                            </div>
                                            <div class="modal-footer">
                                                <form method="POST" action="{{ route('admin.sitevisits.conducted', $sitevisit->id) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="parent_stage_id" value="11">

                                                    <button type="submit" class="btn btn-primary">Confirm</button>
                                                </form>
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" id="notVisitedModel" tabindex="-1" role="dialog" aria-labelledby="notVisitedModelLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="notVisitedModelLabel">Confirmation</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>

                                            <div class="modal-body">
                                                Are you sure this site visit is not visited?
                                            </div>
                                            <div class="modal-footer">
                                                <form action="{{ route('admin.sitevisits.notvisited', $sitevisit->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="parent_stage_id" value="12">

                                                    <button type="submit" class="btn btn-danger">Confirm</button>
                                                </form>
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Edit Modal -->
<div class="modal fade" id="editModal{{ $sitevisit->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel{{ $sitevisit->id }}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel{{ $sitevisit->id }}">Reschedule</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>

                                            <div class="modal-body">
                                                <form method="POST" action="{{ route('admin.sitevisits.reschedule', $sitevisit->id) }}" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="lead_id" value="{{ $sitevisit->lead_id }}">
                                                    <h3 class="card-title">Lead Id : {{ $sitevisit->lead_id }}</h3>

                                                    <div class="form-group">
                                                        <label class="required" for="user_id">{{ trans('cruds.project.fields.client') }}</label>
                                                        <select class="form-control select2 {{ $errors->has('client') ? 'is-invalid' : '' }}" name="user_id" id="user_id" required>
                                                            @foreach ($client as $id => $clients)
                                                                @foreach ($clients->clientUsers as $user)
                                                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                                        {{ $user->representative_name }}
                                                                    </option>
                                                                @endforeach
                                                            @endforeach
                                                        </select>
                                                        @if ($errors->has('client'))
                                                            <span class="text-danger">{{ $errors->first('client') }}</span>
                                                        @endif
                                                        <span class="help-block">{{ trans('cruds.project.fields.client_helper') }}</span>
                                                    </div>

                                                    <label for="Date">Select Date</label>
                                                    <input type="date" name="follow_up_date" class="form-control datepicker" value="{{ $sitevisit->follow_up_date }}">

                                                    <label for="Time">Select Time</label>
                                                    <input id="follow_up_time" name="follow_up_time" type="text" class="form-control timepicker" value="{{ $sitevisit->follow_up_time }}">

                                                    <div class="form-group">
                                                        <label for="followUpContent">Notes</label>
                                                        <textarea id="followUpContent" name="notes" class="form-control" rows="5">{{ $sitevisit->notes }}</textarea>
                                                    </div>
                                                </div>

                                            <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <script>
        $(document).ready(function() {
            // Initialize the date range picker
            $('#custom_range').daterangepicker();

            // Show/hide the custom range container based on the selected option
            $('#date_range').change(function() {
                var selectedOption = $(this).val();
                if (selectedOption === 'custom') {
                    $('#custom_range_container').show();
                } else {
                    $('#custom_range_container').hide();
                    filterTable(selectedOption);
                }
            });

            // Handle filtering when the custom range is selected
            $('#custom_range').change(function() {
                var customRange = $(this).val();
                filterTable('custom', customRange);
            });

            function filterTable(selectedOption, customRange = null) {
                var startDate, endDate;

                switch (selectedOption) {
                    case 'today':
                        startDate = moment().startOf('day');
                        endDate = moment().endOf('day');
                        break;
                    case 'yesterday':
                        startDate = moment().subtract(1, 'days').startOf('day');
                        endDate = moment().subtract(1, 'days').endOf('day');
                        break;
                    case 'last_30_days':
                        startDate = moment().subtract(29, 'days').startOf('day');
                        endDate = moment().endOf('day');
                        break;
                    case 'last_60_days':
                        startDate = moment().subtract(59, 'days').startOf('day');
                        endDate = moment().endOf('day');
                        break;
                    case 'last_year':
                        startDate = moment().subtract(1, 'year').startOf('day');
                        endDate = moment().endOf('day');
                        break;
                    case 'last_week':
                        startDate = moment().subtract(1, 'week').startOf('week');
                        endDate = moment().subtract(1, 'week').endOf('week');
                        break;
                    case 'custom':
                        if (customRange) {
                            var dates = customRange.split(' - ');
                            startDate = moment(dates[0], 'YYYY-MM-DD').startOf('day');
                            endDate = moment(dates[1], 'YYYY-MM-DD').endOf('day');
                        }
                        break;
                }

                // Filter the table rows based on the calculated start and end dates
                $('#siteVisitTable tbody tr').hide().filter(function() {
                    var createdDate = $(this).data('created-at');
                    return moment(createdDate, 'YYYY-MM-DD').isBetween(startDate, endDate, null, '[]');
                }).show();
            }
        });
    </script>
@endsection
