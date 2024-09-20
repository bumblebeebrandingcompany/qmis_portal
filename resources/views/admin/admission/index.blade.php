@extends('layouts.admin')
@section('content')
    <h2>Admission Table</h2>
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped table-hover ajaxTable datatable datatable-Admission"
                id="followUpTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Reference Number</th>
                        <th>Father Name</th>
                        <th>Mother name</th>
                        <th>Child Details</th>
                        <th>Application No</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $counter = 1;
                    @endphp
                    @foreach ($admissions as $admission)
                        <td>{{ $counter++ }}</td>
                        <td>
                            @foreach ($lead as $leads)
                                @if ($leads->id === $admission->lead_id)
                                    <a href="{{ route('admin.leads.show', ['lead' => $leads->id]) }}">
                                        {{ $leads->ref_num }}
                                    </a>
                                @endif
                            @endforeach
                        </td>
                        <td>
                            {{ $admission->lead->father_details['name'] ?? '' }}
                        </td>
                        <td>
                            {{ $admission->lead->mother_details['name'] ?? 'Not Updated' }}
                        </td>
                        <td>
                            {{-- @php
                                $leadInstance = $lead->firstWhere('id', $admission->lead_id);
                            @endphp --}}

                                @foreach ($lead as $leads)
                                @if ($leads->id === $admission->lead_id)
                                    {{ $leads->student_details['name'] ?? '' }}
                                @endif
                            @endforeach

                        </td>
                        <td>
                            {{ $admission->application->application_no ?? '' }}
                        </td>

                        {{-- <td>
                            {{ $admission->notes ?? '' }}
                        </td> --}}
                        <td>
                            {{ $admission->created_at->format('Y-m-d') }}
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
    <script>
        $(function() {
            let table = $('.datatable-Admission').DataTable();
            table.on('draw.dt', function() {});
        });
    </script>
@endsection
