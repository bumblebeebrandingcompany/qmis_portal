@extends('layouts.admin')
@section('content')
    <div class="row">

        <div class="col-md-9 float-right">
            <h3>Site Visit And Reschedule</h3>
        </div>
        <div class="col-md-2 float-right" id="countdown1">Respond Within: <span id="timer"></span></div>
        <div class="col-md-1"></div>
    </div>

    <ul class="nav nav-tabs" id="myTabs">
        <li class="nav-item">
            <a class="nav-link active" id="siteVisitTab" data-toggle="tab" href="#siteVisit">Upcoming Schedule</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="rescheduleTab" data-toggle="tab" href="#reschedule">Site Visit</a>
        </li>
    </ul>

    <div class="tab-content">
        <!-- Site Visit Tab -->
        <div class="tab-pane fade show active" id="siteVisit">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Upcoming Sitevisits</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table
                            class="table table-bordered table-striped table-hover ajaxTable datatable datatable-reschedule"
                            id="rescheduleTable">
                            <thead>
                                <tr>
                                    <th>R.No</th>
                                    <th>Parent Name</th>
                                    <th>Campaign Name</th>
                                    <th>Site Visit Date</th>
                                    <th>Site Visit Time</th>
                                    <th>Supervise By</th>
                                    <th>Notes</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                    <th>Timer</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $todayData = [];
                                    $tomorrowData = [];
                                @endphp

                                @foreach ($sitevisits as $sitevisit)
                                    @php
                                        $visitDate = \Carbon\Carbon::parse($sitevisit->follow_up_date);
                                        $today = \Carbon\Carbon::today();
                                        $tomorrow = \Carbon\Carbon::tomorrow();
                                        $followUpTime = \Carbon\Carbon::parse($sitevisit->follow_up_time);
                                        $currentTime = \Carbon\Carbon::now();
                                        $timeRemaining = $followUpTime->diffInMinutes($currentTime);
                                    @endphp

                                    @if ($visitDate->eq($today))
                                        @php
                                            $todayData[] = $sitevisit;
                                        @endphp
                                    @elseif ($visitDate->eq($tomorrow))
                                        @php
                                            $tomorrowData[] = $sitevisit;
                                        @endphp
                                    @endif
                                @endforeach

                                {{-- Display today's data --}}
                                @if (!empty($todayData))
                                    <tr>
                                        <td>
                                            <h4 class="custom-today-heading">Today</h4>
                                        </td>
                                    </tr>

                                    @foreach ($todayData as $sitevisit)
                                        @include('admin.sitevisit.partials.table_body')
                                    @endforeach
                                @endif

                                {{-- Display tomorrow's data --}}
                                @if (!empty($tomorrowData))
                                    <tr>
                                        <td>
                                            <h4 class="custom-tomorrow-heading">Tomorrow</h4>
                                        </td>
                                    </tr>

                                    @foreach ($tomorrowData as $sitevisit)
                                        @include('admin.sitevisit.partials.table_body')
                                    @endforeach
                                @endif


                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Reschedule Tab -->
        <div class="tab-pane fade" id="reschedule">
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
                                    <option value="tomorrow">Tomorrow</option>
                                    <option value="this_week">This Week</option>
                                    <option value="next_60_days" selected>Next 60 Days</option>
                                    <option value="next_30_days">Next 30 Days</option>
                                    <option value="last_week">Last Week</option>
                                    <option value="last_30_days">Last 30 Days</option>
                                    <option value="last_60_days">Last 60 Days</option>
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
                        <div class="table-responsive">
                            <table
                                class="table table-bordered table-striped table-hover ajaxTable datatable datatable-sitevisit"
                                id="siteVisitTable">
                                <thead>
                                    <tr>
                                        <th>R.No</th>
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
                                        @include('admin.sitevisit.partials.table_body')
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
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
            // Initialize the date range picker for Site Visit
            $('#custom_range').daterangepicker();

            // Show/hide the custom range container based on the selected option for Site Visit
            $('#date_range').change(function() {
                var selectedOption = $(this).val();
                if (selectedOption === 'custom') {
                    $('#custom_range_container').show();
                } else {
                    $('#custom_range_container').hide();
                    filterTable(selectedOption, 'siteVisit');
                }
            });

            // Handle filtering when the custom range is selected for Site Visit
            $('#custom_range').change(function() {
                var customRange = $(this).val();
                filterTable('custom', 'siteVisit', customRange);
            });

            // Initialize the date range picker for Reschedule
            $('#custom_range_reschedule').daterangepicker();

            // Show/hide the custom range container based on the selected option for Reschedule
            $('#date_range_reschedule').change(function() {
                var selectedOption = $(this).val();
                if (selectedOption === 'custom') {
                    $('#custom_range_container_reschedule').show();
                } else {
                    $('#custom_range_container_reschedule').hide();
                    filterTable(selectedOption, 'reschedule');
                }
            });

            // Handle filtering when the custom range is selected for Reschedule
            $('#custom_range_reschedule').change(function() {
                var customRange = $(this).val();
                filterTable('custom', 'reschedule', customRange);
            });

            function filterTable(selectedOption, tableType, customRange = null) {
                var startDate, endDate;
                var tableId;

                if (tableType === 'siteVisit') {
                    startDate = moment().startOf('day');
                    endDate = moment().endOf('day');
                    tableId = '#siteVisitTable';
                } else if (tableType === 'reschedule') {
                    // Adjust the logic for reschedule table if needed
                    startDate = moment().startOf('day');
                    endDate = moment().endOf('day');
                    tableId = '#rescheduleTable';
                }

                switch (selectedOption) {
                    // ... (Your existing switch cases for date range options) ...
                }

                // Filter the table rows based on the calculated start and end dates
                $(tableId + ' tbody tr').hide().filter(function() {
                    var createdDate = $(this).data('created-at');
                    return moment(createdDate, 'YYYY-MM-DD').isBetween(startDate, endDate, null, '[]');
                }).show();
            }
        });
    </script>
@endsection


<!-- resources/views/countdown/index.blade.php -->

<style>
    #countdown {
        font-size: 24px;
    }
</style>


<script>
    const targetTime = new Date();
    targetTime.setHours(19, 0, 0); // 7:00 PM

    function updateTimer() {
        const currentTime = new Date();
        const timeDifference = targetTime - currentTime;

        if (timeDifference > 0) {
            const hours = Math.floor(timeDifference / (1000 * 60 * 60));
            const minutes = Math.floor((timeDifference % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((timeDifference % (1000 * 60)) / 1000);

            document.getElementById('timer').innerHTML = `${hours}h ${minutes}m ${seconds}s`;
        } else {
            document.getElementById('timer').innerHTML = 'Countdown expired';
        }
    }
    // Update the timer every second
    setInterval(updateTimer, 1000);

    // Initial update
    updateTimer();
</script>
<style>
    body {
        font-family: Arial, sans-serif;
        text-align: right;
        margin: 10px;
    }

    #countdown {
        font-size: 18px;
        margin-bottom: 10px;
    }

    #timer {
        font-weight: bold;
    }
</style>
<!-- resources/views/countdown/index.blade.php -->
