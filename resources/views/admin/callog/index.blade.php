@extends('layouts.admin')
@section('content')
    <h1>Call Records</h1>
    <form action="{{ route('admin.callog.store') }}" method="post">
        @csrf
        <button type="submit">Store Call Records</button>
    </form>
    <div class="card">
        <div class="row">
            <div class="col-md-3">
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
            <div class="col-md-3">
                <div class="form-group">
                    <label for="staffMember">Select Staff Member</label>
                    <select id="staffMember" name="staffMember" class="form-control">
                        @foreach ($agencies as $key => $item)
                            @if ($item->representative_name)
                                <!-- Check if representative_name is not null or empty -->
                                @if (trim($item->representative_name) !== '')
                                    <!-- Check if representative_name is not empty after trimming -->
                                    <option value="{{ $item->representative_name }}">{{ $item->representative_name }}
                                    </option>
                                @endif
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3">
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
            <div class="col-md-3">
                <div class="form-group">
                    <label for="start_date">{{ trans('cruds.project.fields.end_date') }}</label>
                    <input class="form-control date {{ $errors->has('end_date') ? 'is-invalid' : '' }}" type="text"
                        name= "end_date" id="end_date" value="{{ old('end_date') }}">
                    @if ($errors->has('end_date'))
                        <span class="text-danger">{{ $errors->first('end_date') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.project.fields.end_date_helper') }}</span>
                </div>
            </div>
        </div>
    <table class="table">
        <thead>
            <tr>
                <th>Client number</th>
                <th>Status</th>
                <th>Call Start Time</th>
                <th>Call Duration</th>
                <th>Called On</th>
                <th>Call Recording</th>
                <!-- Add more table headers for other lead follow-up properties -->
            </tr>
        </thead>
        <tbody>
            @foreach ($callRecords as $callRecord)
                <tr>
                    <td>{{ $callRecord->called_by }}</td>
                    <td>{{ $callRecord->called_on }}</td>
                    <td>{{ $callRecord->call_start_time }}</td>
                    <td>{{ $callRecord->call_duration }}</td>
                    <td>{{ $callRecord->called_on }}</td>

                    {{-- <td>
                        <audio controls>
                            <source src="{{ asset('storage/audio/' . $callRecord->call_recordings) }}" type="audio/mp3">
                            Your browser does not support the audio element.
                        </audio>
                    </td> --}}
                    <td>
                        <audio controls>
                            <!-- Assuming the recording URL is already in MP3 format -->
                            <source src="{{ $callRecord['recording_url'] }}" type="audio/mp3">
                            Your browser does not support the audio tag.
                        </audio>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    </div>
</div>
{{-- <form action="{{ route('admin.callog.store') }}" method="post">
    @csrf
    <button type="submit">Store Call Records</button>
</form> --}}
@endsection
