@extends('layouts.admin')

@section('content')
    <h1>
        Site Visit</h1>
    <div class="card">

        <div class="card-header">
            <h3 class="card-title">Lead Site Visit Table</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="campaign_name">Select Campaign Name</label>
                        <select id="campaign_name" name="campaign_name" class="form-control">
                            @foreach ($campaigns as $key => $item)
                                @if ($item->campaign_name)
                                    <!-- Check if representative_name is not null or empty -->
                                    <option value="{{ $item->campaign_name }}">{{ $item->campaign_name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="required"
                        for="user_id">{{ trans('cruds.project.fields.client') }}</label>
                    <select class="form-control select2 {{ $errors->has('client') ? 'is-invalid' : '' }}"
                        name="user_id" id="user_id" required>
                    @foreach ($client as $id => $clients)
                        @foreach($clients->clientUsers as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->representative_name }}
                            </option>
                        @endforeach
                    @endforeach
                </select>
                    @if ($errors->has('client'))
                        <span class="text-danger">{{ $errors->first('client') }}</span>
                    @endif
                    <span class="help-block"></span>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="start_date">{{ trans('cruds.project.fields.start_date') }}</label>
                        <input class="form-control date {{ $errors->has('start_date') ? 'is-invalid' : '' }}" type="text"
                            name="start_date" id="start_date" value="{{ old('start_date') }}">
                        @if ($errors->has('start_date'))
                            <span class="text-danger">{{ $errors->first('start_date') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.project.fields.start_date_helper') }}</span>
                    </div>
                </div>
            </div>

            <table class="table">
                <thead>
                    <tr>
                        <th>Reference Number</th>
                        <th>Campaign Name</th>
                        <th>Site Visit Date</th>
                        <th>Site Visit Time</th>
                        <th>Site Visit By</th>
                        <th>Notes</th>
                        <th>Stage Id</th>
                        <th>Actions</th>
                        {{-- <th>Action</th> --}}
                        <!-- Add more table headers for other lead follow-up properties -->
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sitevisits as $sitevisit)
                        <tr>
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
                            {{-- <td>{{ $followUp->follow_up_date }}</td> --}}
                            {{-- <td>{{ $followUp->follow_up_time }}</td> --}}

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
                                <form action="{{ route('admin.sitevisits.reschedule', $sitevisit->id) }}" method="POST">
                                    @csrf
                                    <button type="button" class="btn btn-sm btn-warning" data-toggle="modal"
                                        data-target="#editModal{{ $sitevisit->id }}">
                                        Rescheduled
                                    </button>
                                </form>
                            </td>
                            <!-- Edit Modal -->
                            <div class="modal fade" id="editModal{{ $sitevisit->id }}" tabindex="-1" role="dialog"
                                aria-labelledby="editModalLabel{{ $sitevisit->id }}" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editModalLabel{{ $sitevisit->id }}">
                                                Reschedule</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="POST"
                                                action="{{ route('admin.sitevisits.reschedule', $sitevisit->id) }}"
                                                enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="lead_id" value="{{ $sitevisit->lead_id }}">
                                                <h3 class="card-title">Lead Id : {{ $sitevisit->lead_id }}</h3>
                                                <div class="form-group">
                                                    <label class="required"
                                                        for="user_id">{{ trans('cruds.project.fields.client') }}</label>
                                                    <select
                                                        class="form-control select2 {{ $errors->has('client') ? 'is-invalid' : '' }}"
                                                        name="user_id" id="user_id" required>
                                                        @foreach ($client as $id => $clients)
                                                            @foreach ($clients->clientUsers as $user)
                                                                <option value="{{ $user->id }}"
                                                                    {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                                    {{ $user->representative_name }}
                                                                </option>
                                                            @endforeach
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('client'))
                                                        <span class="text-danger">{{ $errors->first('client') }}</span>
                                                    @endif
                                                    <span
                                                        class="help-block">{{ trans('cruds.project.fields.client_helper') }}</span>
                                                </div>
                                        </div>
                                        <label for="Date">Select Date</label>
                                        <input type="date" name="follow_up_date" class="form-control datepicker" value="{{$sitevisit->follow_up_date}}">
                                        <label for="Time">Select Time</label>
                                        <input id="follow_up_time" name="follow_up_time" type="text"
                                            class="form-control timepicker" value="{{ $sitevisit->follow_up_time }}">
                                        <div class="form-group">
                                            <label for="followUpContent">Notes</label>
                                            <textarea id="followUpContent" name="notes" class="form-control" rows="5">{{$sitevisit->notes}}</textarea>
                                        </div>
                                        <div class="modal-footer">

                                                <button type="submit" class="btn btn-primary ">Save Changes</button>

                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Cancel</button>
                                        </div>

                                        </form>
                                    </div>

                                </div>
                            </div>
                            {{-- <td>
                                <form action="{{ route('admin.followups.destroy', $sitevisit->id) }}" method="POST"
                                    onsubmit="return confirmDelete();" style="display: inline-block;">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="submit" class="btn btn-xs btn-danger"
                                        value="{{ trans('global.delete') }}">
                                </form>
                            </td> --}}
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endsection

        {{-- @extends('layouts.admin')

        @section('content')
            <h1>Site Visit</h1>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Lead Site Visit Table</h3>
                </div>
                <div class="card-body">
                    @foreach ($lead as $leadItem)
                        <!-- Display original SiteVisit details -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h3 class="card-title">Original SiteVisit for Lead ID: {{ $leadItem->id }}</h3>
                            </div>
                            <div class="card-body">
                                <!-- Your code to display original SiteVisit details goes here -->
                                <!-- For example, you can use a table or other HTML structure -->
                                <table class="table">
                                    <!-- Display the details of the original SiteVisit for this lead_id -->
                                    <!-- Adjust the code based on your actual SiteVisit details structure -->
                                    <tr>
                                        <td>Reference Number</td>
                                        <td>{{ $leadItem->ref_num }}</td>
                                    </tr>
                                    <tr>
                                        <td>Campaign Name</td>
                                        <td>{{ $leadItem->campaign->campaign_name }}</td>
                                    </tr>
                                    <!-- Add more rows for other details -->
                                </table>
                            </div>
                        </div>

                        <!-- Display rescheduled SiteVisit details for this lead_id -->
                        @foreach ($leadItem->sitevisits as $sitevisit)
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h3 class="card-title">Rescheduled SiteVisit</h3>
                                </div>
                                <div class="card-body">
                                    <!-- Your code to display rescheduled SiteVisit details goes here -->
                                    <!-- For example, you can use a table or other HTML structure -->
                                    <table class="table">
                                        <!-- Display the details of the rescheduled SiteVisit -->
                                        <tr>
                                            <td>Reference Number</td>
                                            <td>{{ $leadItem->ref_num }}</td>
                                        </tr>
                                        <tr>
                                            <td>Campaign Name</td>
                                            <td>{{ $leadItem->campaign->campaign_name }}</td>
                                        </tr>
                                        <!-- Add more rows for other details -->
                                    </table>
                                </div>
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </div>
        @endsection --}}
