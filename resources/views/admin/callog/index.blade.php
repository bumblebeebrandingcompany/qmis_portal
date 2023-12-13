@extends('layouts.admin')
@section('content')
    <h1>Call Records</h1>
    <form action="{{ route('admin.callog.store') }}" method="post">
        @csrf
        <button class="btn btn-primary" type="submit">
            Store Call Records</button>
    </form>
    <div class="card">

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
    <table class="table table-bordered table-striped table-hover ajaxTable datatable datatable-callog">
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
                                <!-- Use the asset function to generate the correct URL -->
                                <source src="{{ asset($callRecord['call_recordings']) }}" type="audio/mp3">
                                Your browser does not support the audio tag.
                            </audio>

                            {{-- <audio controls>
                                <!-- Use the asset function to generate the correct URL -->
                                <source src="{{ asset($callRecord['call_recordings']) }}" type="audio/mp3">
                                Your browser does not support the audio tag.
                            </audio>  --}}
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
<style>
    /* Customize the audio player controls */
    audio {
        /* Set the background color */
        background-color: #f0f0f0;

        /* Set the color of the volume control slider */
        --webkit-slider-thumb-color: #3498db;
        --moz-range-thumb-color: #3498db;
        --ms-thumb-color: #3498db;
        --o-range-thumb-color: #3498db;
        --webkit-slider-runnable-track-color: #bdc3c7;
        --moz-range-track-color: #bdc3c7;
        --ms-track-color: #bdc3c7;
        --o-range-track-color: #bdc3c7;
    }
</style>


