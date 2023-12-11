<h1>
    Site Visit</h1>
<div class="card">


    <table class="table">
        <thead>
            <tr>
                <th>Reference Number</th>
                <th>Campaign Name</th>
                <th>Site Visit Date</th>
                <th>Site Visit Time</th>
                <th>Site Visit By</th>
                <th>Notes</th>
                {{-- <th>Stage Id</th> --}}
                <th>Action</th>
                <!-- Add more table headers for other lead follow-up properties -->
            </tr>
        </thead>
        <tbody>
            @foreach ($sitevisit as $sitevisit)
                <tr>
                    <td>

                        {{ $sitevisit->lead_id }}
                    </td>
                    <td>{{ $lead->campaign->campaign_name }}</td>

                    <td>{{ $sitevisit->follow_up_date }}</td>


                    <td>{{ $sitevisit->follow_up_time }}</td>

                    <td>
                        {{ $sitevisit->users->representative_name }}
                    </td>
                    {{-- <td>
    @foreach ($agencies as $id => $agency)
        @foreach($agency->agencyUsers as $user)
            @if ($user->id == old('user_id'))
                {{ $user->representative_name }}
            @endif
        @endforeach
    @endforeach
</td> --}}

    {{-- <div class="form-group">
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
    </div> --}}
                    <td>
                        {{ $sitevisit->notes }}
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
                                <input type="date" name="follow_up_date" class="form-control datepicker"
                                    value="{{ $sitevisit->follow_up_date }}">
                                <label for="Time">Select Time</label>
                                <input id="follow_up_time" name="follow_up_time" type="text"
                                    class="form-control timepicker" value="{{ $sitevisit->follow_up_time }}">
                                <div class="form-group">
                                    <label for="followUpContent">Notes</label>
                                    <textarea id="followUpContent" name="notes" class="form-control" rows="5">{{ $sitevisit->notes }}</textarea>
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
