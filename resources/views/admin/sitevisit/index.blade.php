@extends('layouts.admin')
@section('content')
    <div class="row">

        <div class="col-md-9 float-right">
            <h3>Site Visit And Reschedule</h3>
        </div>

        {{-- <div class="col-md-2 float-right" id="countdown1">Respond Within: <span id="timer"></span></div> --}}
        <div class="col-md-1"></div>
    </div>

    <ul class="nav nav-tabs" id="myTabs">

        <li class="nav-item">
            <a class="nav-link active" id="rescheduleTab" data-toggle="tab" href="#reschedule">Upcoming SiteVisits</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="siteVisitTab" data-toggle="tab" href="#sitevisit">Site Visit</a>
        </li>
    </ul>

    <div class="tab-content">

        <div class="tab-pane fade show active" id="reschedule">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover ajaxTable datatable datatable-walkin"
                            id="sitevisitTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Ref Num</th>
                                    <th>Site Visit No</th>
                                    <th>Student Name</th>
                                    <th>Father Name</th>
                                    <th>Mother Name</th>
                                    <th>Date</th>
                                    <th>Time Slot</th>
                                    <th>Stage</th>
                                    <th>Created At</th>
                                    {{-- <th>Timer</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $counter = 1;
                            @endphp
                                @foreach ($sitevisits as $sitevisit)
                                    @php
                                        $visitDate = \Carbon\Carbon::parse($sitevisit->date);
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
                                    @php
                                    $counter = 0; // Initialize the counter outside the loop
                                @endphp
                                    @foreach ($todayData as $sitevisit)
                                    @php
                                    $counter++; // Increment the counter for the next iteration
                                @endphp
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
                                    @php
                                    $counter = 0; // Initialize the counter outside the loop
                                @endphp
                                    @foreach ($tomorrowData as $sitevisit)
                                    @php
                                    $counter++; // Increment the counter for the next iteration
                                @endphp
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
        <div class="tab-pane fade" id="sitevisit">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Lead Site Visit Table</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            {{-- <div style="padding-bottom: 4px">
                                <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                                <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                                <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">

                            </div> --}}
                            @includeIf('layouts.partials.javascript')
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
                                    <option value="last_60_days" selected>Last 60 Days</option>
                                    <option value="last_year">Last One Year</option>
                                    <option value="custom">Custom Range</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3" id="custom_range_container" style="display:none;">
                            <div class="form-group">
                                <label for="custom_range">Custom Range</label>
                                <input class="form-control" type="text" name="custom_range" id="custom_range"
                                    value="{{ old('custom_range') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <form method="get" action="{{ url()->current() }}">
                                <label for="recordsPerPage">Records Per Page:</label>
                                <br>
                                <select class="form-control ml-2 select2" id="recordsPerPage" name="perPage"
                                    onchange="this.form.submit()">
                                    <option value="10" {{ request('perPage', 10) == 10 ? 'selected' : '' }}>10</option>
                                    <option value="50" {{ request('perPage', 10) == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ request('perPage', 10) == 100 ? 'selected' : '' }}>100
                                    </option>
                                    <option value="200" {{ request('perPage', 10) == 200 ? 'selected' : '' }}>200
                                    </option>
                                </select>
                            </form>
                        </div>

                        <div class="table-responsive">

                            <table class="table table-bordered table-striped table-hover ajaxTable datatable datatable-lead"
                                id="sitevisitTable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Ref Num</th>
                                        <th>Site Visit No</th>
                                        <th>Student Name</th>

                                        <th>Father Name</th>
                                        <th>Mother Name</th>
                                        <th>Date</th>
                                        <th>Time Slot</th>
                                        <th>Stage</th>
                                        <th>Created At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="8">
                                            Total Site Visits: {{ count($sitevisits) }}
                                            <!-- Display the total count of site visits -->
                                        </td>
                                    </tr>
                                    @php
                                        $counter = 0; // Initialize the counter outside the loop
                                    @endphp
                                    @foreach ($sitevisits as $sitevisit)
                                        @php
                                            $counter++; // Increment the counter for the next iteration
                                        @endphp
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
                    case 'next_60_days':
                        startDate = moment().startOf('day');
                        endDate = moment().add(59, 'days').startOf('day');

                        break;
                    case 'last_year':
                        startDate = moment().subtract(1, 'year').startOf('day');
                        endDate = moment().endOf('day');
                        break;
                    case 'last_week':
                        startDate = moment().subtract(1, 'week').startOf('week');
                        endDate = moment().subtract(1, 'week').endOf('week');
                        break;
                    case 'next_30_days':
                        startDate = moment().startOf('day');
                        endDate = moment().add(29, 'days').endOf('day');
                        break;
                    case 'this_week':
                        startDate = moment().add(1, 'week').startOf('week');
                        endDate = moment().add(1, 'week').endOf('week');
                        break;
                    case 'tomorrow':
                        startDate = moment().add(1, 'day').startOf('day');
                        endDate = moment().add(1, 'day').endOf('day');
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
                $(tableId + ' tbody tr').hide().filter(function() {
                    var createdDate = $(this).data('created-at');
                    return moment(createdDate, 'YYYY-MM-DD').isBetween(startDate, endDate, null, '[]');
                }).show();
            }
        });
    </script>
@endsection


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
@section('scripts')
    @parent
    <script>
        $(function() {
            // Existing JavaScript code for DataTable initialization

            // Additional customization for DataTable
            let table = $('.datatable-walkin').DataTable();
            table.on('draw.dt', function() {
                // Add any additional customization after the table is drawn
            });
        });
    </script>
    <script>
        function searchTable() {
            // Declare variables
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("searchInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("sitevisitTable");
            tr = table.getElementsByTagName("tr");

            // Loop through all table rows, and hide those who don't match the search query
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[0]; // Change index based on the column you want to search
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
    </script>
@endsection
