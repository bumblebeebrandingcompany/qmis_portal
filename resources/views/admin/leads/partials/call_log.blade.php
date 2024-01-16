<h1>Call Records</h1>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Lead ID: {{ $lead->id }}</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">

            </div>
            <div class="col-md-4" id="custom_range_container" style="display:none;">
                <div class="form-group">
                    <label for="custom_range">Custom Range</label>
                    <input class="form-control" type="text" name="custom_range" id="custom_range"
                        value="{{ old('custom_range') }}">
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover ajaxTable datatable datatable-callog">
                <thead>
                    <tr>
                        <th>ID</th>

                        <th>Client number</th>
                        <th>Status</th>

                        <th>Call Duration</th>
                        <th>Called On</th>
                        <th>Call Start Time</th>
                        <th>Call Recording</th>

                    </tr>
                </thead>
                <tbody>
                    @php
                        $counter = 1;
                    @endphp
                    @foreach ($callRecords as $callRecord)
                        <tr>
                            <td>{{ $counter++ }}</td>

                            <td>{{ $callRecord->client_number }}</td>
                            <td>{{ $callRecord->status }}</td>


                            <td>
                                <?php
                                $answeredSeconds = $callRecord->call_duration;
                                $hours = floor($answeredSeconds / 3600);
                                $minutes = floor(($answeredSeconds % 3600) / 60);
                                $seconds = $answeredSeconds % 60;
                                ?>
                                {{ $hours ? $hours . 'h ' : '' }}
                                {{ $minutes ? $minutes . 'm ' : '' }}
                                {{ $seconds . 's' }}
                            </td>
                            <td>{{ $callRecord->called_on }}</td>

                            @php
                                $callOnTime = $callRecord->call_on_time; // Replace this with your actual time
                                $formattedTime = \Carbon\Carbon::createFromFormat('H:i:s', $callOnTime)->format('h:i A');
                            @endphp

                            <td>{{ $formattedTime }}</td>

                            <td>
                                <div class="custom-audio-player">
                                    <audio controls>
                                        <source src="{{ asset($callRecord['call_recordings']) }}" type="audio/mp3">
                                        Your browser does not support the audio tag.
                                    </audio>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        </div>
    </div>
