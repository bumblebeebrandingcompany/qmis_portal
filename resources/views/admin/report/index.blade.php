@extends('layouts.admin')
@section('content')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <div class="row mb-2">
        <div class="col-sm-6">
            <h2>
                Report
            </h2>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-primary card-outline">
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="row">
                        @if (auth()->user()->is_superadmin)
                            <div class="{{ $settings3['column_class'] }}">
                                <div class="info-box">
                                    <span class="info-box-icon bg-success">
                                        <i class="fas fa-user-friends"></i>
                                    </span>

                                    <div class="info-box-content">
                                        <span class="info-box-text">{{ $settings3['chart_title'] }}</span>
                                        <span class="info-box-number">{{ number_format($settings3['total_number']) }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if (auth()->user()->is_superadmin)
                            <div class="{{ $settings5['column_class'] }}">
                                <div class="info-box">
                                    <span class="info-box-icon bg-danger">
                                        <i class="fa fa-chart-line"></i>
                                    </span>

                                    <div class="info-box-content">
                                        <span class="info-box-text">{{ $settings5['chart_title'] }}</span>
                                        <span class="info-box-number">{{ number_format($settings5['total_number']) }}</span>
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                            </div>
                        @endif
                        <div class="{{ $settings6['column_class'] }}">
                            <div class="info-box">
                                <span class="info-box-icon bg-info">
                                    <i class="fas fa-project-diagram"></i>
                                </span>

                                <div class="info-box-content">
                                    <span class="info-box-text">{{ $settings6['chart_title'] }}</span>
                                    <span class="info-box-number">{{ number_format($settings6['total_number']) }}</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <div class="{{ $settings9['column_class'] }}">
                            <div class="info-box">
                                <span class="info-box-icon bg-success">
                                    <i class="fas fa-user-plus"></i>
                                </span>

                                <div class="info-box-content">
                                    <span class="info-box-text">{{ $settings9['chart_title'] }}</span>
                                    <span class="info-box-number">{{ number_format($settings9['total_number']) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="{{ $settings8['column_class'] }}">
                            <div class="info-box">
                                <span class="info-box-icon bg-success">
                                    <i class="fas fa-user-plus"></i>
                                </span>

                                <div class="info-box-content">
                                    <span class="info-box-text">{{ $settings8['chart_title'] }}</span>
                                    <span class="info-box-number">{{ number_format($settings8['total_number']) }}</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>

                        @if (auth()->user()->is_superadmin)
                            <div class="{{ $settings1['column_class'] }}">
                                <div class="info-box">
                                    <span class="info-box-icon bg-info">
                                        <i class="fas fa-users"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">{{ $settings1['chart_title'] }}</span>
                                        <span
                                            class="info-box-number">{{ number_format($settings1['total_number']) }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if (auth()->user()->is_superadmin)
                            <div class="{{ $settings2['column_class'] }}">
                                <div class="info-box">
                                    <span class="info-box-icon bg-primary">
                                        <i class="fas fa-user-friends"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">{{ $settings2['chart_title'] }}</span>
                                        <span
                                            class="info-box-number">{{ number_format($settings2['total_number']) }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if (auth()->user()->is_superadmin)
                            <div class="{{ $settings4['column_class'] }}">
                                <div class="info-box">
                                    <span class="info-box-icon bg-warning">
                                        <i class="fas fa-user-friends"></i>
                                    </span>

                                    <div class="info-box-content">
                                        <span class="info-box-text">{{ $settings4['chart_title'] }}</span>
                                        <span
                                            class="info-box-number">{{ number_format($settings4['total_number']) }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="{{ $settings7['column_class'] }}">
                            <div class="info-box">
                                <span class="info-box-icon bg-primary">
                                    <i class="fas fa-bullhorn"></i>
                                </span>

                                <div class="info-box-content">
                                    <span class="info-box-text">{{ $settings7['chart_title'] }}</span>
                                    <span class="info-box-number">{{ number_format($settings7['total_number']) }}</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>

                        <div class="{{ $settings16['column_class'] }}">
                            <div class="info-box">
                                <span class="info-box-icon bg-success">
                                    <i class="fas fa-user-plus"></i>
                                </span>

                                <div class="info-box-content">
                                    <span class="info-box-text">{{ $settings16['chart_title'] }}</span>
                                    <span class="info-box-number">{{ number_format($settings16['total_number']) }}</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card card-primary card-outline">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-4">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Campaign Leads Pie Chart</h5>
                            <canvas id="campaignPieChart" width="100" height="100"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-4"></div>

            </div>
            <div class="col-12">
                <h5 class="bold"><strong style="font-weight: 800;">Q's Consolidation</h5>
                <div class="table table-bordered table-striped table-hover ajaxTable datatable datatable-User">

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th>BBC</th>
                                <th>Direct</th>
                                <th>Total</th>
                                <th>Exist-Student</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Walkin</td>
                                <td>{{ number_format($settings5['total_number']) }}</td>
                                <td>{{ number_format($settings6['total_number']) }}</td>
                                <td>{{ number_format($settings5['total_number'] + $settings6['total_number']) }}
                                </td>
                                <td>{{ number_format($settings7['total_number']) }}</td>
                            </tr>
                            <tr>
                                <td>App</td>
                                <td>{{ number_format($settings10['total_number']) }}</td>
                                <td>{{ number_format($settings11['total_number']) }}</td>
                                <td>{{ number_format($settings10['total_number'] + $settings11['total_number']) }}
                                </td>
                                <td>{{ number_format($settings7['total_number']) }}</td>

                            </tr>
                            <tr>
                                <td>Admission</td>
                                <td>{{ number_format($settings12['total_number']) }}</td>
                                <td>{{ number_format($settings13['total_number']) }}</td>
                                <td>{{ number_format($settings12['total_number'] + $settings13['total_number']) }}
                                </td>
                                <td>{{ number_format($settings7['total_number']) }}</td>
                            </tr>
                            <tr>
                                <td>Withdrawal</td>
                                <td>{{ number_format($settings14['total_number']) }}</td>
                                <td>{{ number_format($settings15['total_number']) }}</td>
                                <td>{{ number_format($settings14['total_number'] + $settings15['total_number']) }}
                                </td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                {{--   </div>
            </div> --}}
            </div>
            <div class="col-12">
                <h5 class="bold"><strong style="font-weight: 800;">App Team Conversion</strong></h5>

                <div class="table table-bordered table-striped table-hover ajaxTable datatable datatable-User">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>walkin</th>
                                <th>App</th>
                                <th>conversion</th>
                            </tr>
                        </thead>
                        @php
                            $totalLeads = 0;
                            $totalApplications = 0;
                        @endphp
                        @foreach ($users->where('user_type', 'Frontoffice') as $user)
                            <tr>
                                <td>{{ $user->representative_name }}</td>
                                <td>{{ $user->leads->count() }}</td>
                                <td>{{ $user->applications->count() }}</td>
                                <td>
                                    @if ($user->leads->count() > 0 && $user->applications->count() > 0)
                                        {{ number_format(($user->applications->count() / $user->leads->count()) * 100, 2) }}%
                                    @else
                                        0
                                    @endif
                                </td>
                            </tr>
                            @php
                                $totalLeads += $user->leads->count();
                                $totalApplications += $user->applications->count();
                            @endphp
                        @endforeach
                        <tr>
                            <td>Total</td>
                            <td>{{ $totalLeads }}</td>
                            <td>{{ $totalApplications }}</td>
                            <td></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-12">
                <h5 class="bold"><strong style="font-weight: 800;">Adm Team Conversion</h5>
                <div class="table table-bordered table-striped table-hover ajaxTable datatable datatable-User">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Adm Team</th>
                                <th>attended</th>
                                <th>Admitted</th>
                                <th>conversion</th>
                            </tr>
                        </thead>

                        @php
                            $totalLeads = 0;
                            $totalApplications = 0;
                        @endphp
                        @foreach ($users->where('user_type', 'Admissionteam') as $user)
                            <tr>
                                <td>{{ $user->representative_name }}</td>
                                <td>{{ $user->admissions->count() }}</td>
                                <td>{{ $admissionCount = $user->admission->count() }}
                                </td>
                                <td>
                                    @if ($admissionCount > 0 && $user->admissions->count() > 0)
                                        {{ number_format(($admissionCount / $user->admissions->count()) * 100, 2) }}%
                                    @else
                                        0
                                    @endif
                                </td>
                            </tr>
                            @php
                                $totalLeads += $user->admissions->count();
                                $totalApplications += $admissionCount;
                            @endphp
                        @endforeach

                        <tr>
                            <td>Total</td>
                            <td>{{ $totalLeads }}</td>
                            <td>{{ $totalApplications }}</td>
                            <td></td>
                        </tr>
                        </tbody>
                    </table>
                </div>

            </div>

            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="bold"><strong style="font-weight: 800;">Overall Conversion Rate in %</h5>
                        <div class="table table-bordered table-striped table-hover ajaxTable datatable datatable-User">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Particulars</th>
                                        <th>Bumblebee</th>
                                        <th>QMIS</th>
                                    </tr>
                                </thead>
                                <tr>
                                    <td>Walkin - Application</td>
                                    <td>
                                        @if ($settings5['total_number'] > 0)
                                            {{ number_format(($settings10['total_number'] / $settings5['total_number']) * 100, 2) }}%
                                        @else
                                            {{-- Display an empty cell when the denominator is zero --}}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($settings6['total_number'] > 0)
                                            {{ number_format(($settings11['total_number'] / $settings6['total_number']) * 100, 2) }}%
                                        @else
                                            {{-- Display an empty cell when the denominator is zero --}}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Application - Admission</td>
                                    <td>
                                        @if ($settings10['total_number'] > 0)
                                            {{ number_format(($settings12['total_number'] / $settings10['total_number']) * 100, 2) }}%
                                        @else
                                            {{-- Display an empty cell when the denominator is zero --}}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($settings11['total_number'] > 0)
                                            {{ number_format(($settings13['total_number'] / $settings11['total_number']) * 100, 2) }}%
                                        @else
                                            {{-- Display an empty cell when the denominator is zero --}}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Walkin - Admission</td>
                                    <td>
                                        @if ($settings5['total_number'] > 0)
                                            {{ number_format(($settings12['total_number'] / $settings5['total_number']) * 100, 2) }}%
                                        @else
                                            {{-- Display an empty cell when the denominator is zero --}}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($settings6['total_number'] > 0)
                                            {{ number_format(($settings13['total_number'] / $settings6['total_number']) * 100, 2) }}%
                                        @else
                                            {{-- Display an empty cell when the denominator is zero --}}
                                        @endif
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
                    <div id="chartContainer" style="height: 370px; width: 100%;"></div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        $(function() {
            @includeIf('admin.report.report_js')
        });
    </script>
@endsection
<script>
    window.onload = function() {
        var chart = new CanvasJS.Chart("chartContainer", {
            // theme: "dark2",
            animationEnabled: true,
            title: {
                text: "Lead Funnel Graph"
            },
            data: [{
                type: "funnel",
                indexLabelPlacement: "inside",
                indexLabel: "{label} - {y} ({count})",
                yValueFormatString: "#,##0.00'%'",
                showInLegend: true,
                legendText: "{label}",
                dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
            }]
        });
        chart.render();
    }
</script>
