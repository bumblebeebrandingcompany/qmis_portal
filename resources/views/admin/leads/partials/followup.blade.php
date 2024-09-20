@if (auth()->user()->is_superadmin || auth()->user()->is_presales)
    <h3 class="card-title"> Lead ID: {{ $lead->id }}</h3>
    <br>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover ajaxTable datatable datatable-followup">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Reference Number</th>
                    <th>Father Name</th>
                    <th>Mother Name</th>
                    <th>Student Details</th>
                    <th>Follow-Up Date</th>
                    <th>Follow-Up Time</th>
                    <th>Notes</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody class="followupTable">
                @php
                    $counter = 1;
                @endphp
                @foreach ($followUps as $followUp)
                    <tr data-created-at="{{ $followUp->followup_date }}">
                        <td>{{ $counter++ }}</td>
                        <td>
                            @foreach ($leads as $lead)
                                @if ($lead->id === $followUp->lead_id)
                                    <a href="{{ route('admin.leads.show', ['lead' => $lead->id]) }}">
                                        {{ $lead->ref_num }}
                                    </a>
                                @endif
                            @endforeach
                        </td>
                        <td>
                            @foreach ($leads as $lead)
                                @if ($lead->id === $followUp->lead_id)
                                    {{ $lead->father_details['name'] ?? 'Not Updated' }}
                                @endif
                            @endforeach
                        </td>
                        <td>
                            @foreach ($leads as $lead)
                                @if ($lead->id === $followUp->lead_id)
                                    {{ $lead->mother_details['name'] ?? 'Not Updated' }}
                                @endif
                            @endforeach
                        </td>
                        {{-- <td>
                            @foreach ($leads as $lead)
                                @if ($lead->id === $followUp->lead_id)
                                    @php
                                        $students = json_decode($lead->student_details, true);
                                    @endphp
                                    @if (is_array($students))
                                        @foreach ($students as $index => $student)
                                            {{ 'Child ' . ($index + 1) . ': ' . ($student['name'] ?? 'No Name Found') }}<br>
                                            {{ 'Grade: ' . ($student['grade'] ?? 'No Grade Found') }}<br>
                                            <hr>
                                        @endforeach
                                    @else
                                        No Student Details Found
                                    @endif
                                @endif
                            @endforeach
                        </td> --}}
                        <td>{{ $followUp->followup_date }}</td>
                        <td>{{ $followUp->followup_time }}</td>
                        <td>
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                data-target="#notesModal{{ $followUp->id }}">
                                View Notes
                            </button>
                            <div class="modal fade" id="notesModal{{ $followUp->id }}" tabindex="-1"
                                role="dialog" aria-labelledby="notesModalLabel{{ $followUp->id }}"
                                aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="notesModalLabel{{ $followUp->id }}">
                                                Notes
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <textarea id="notesTextArea{{ $followUp->id }}" class="form-control" rows="5" readonly>{{ $followUp->notes }}</textarea>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $followUp->created_at->format('Y-m-d') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
@endif
{{-- @if (auth()->user()->is_admissionteam)
<h3 class="card-title"> Lead ID: {{ $lead->id }}</h3>
    <br>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Campaign Name</th>
                    <th>Follow-Up Time</th>
                    <th>Follow-Up Date</th>
                    <th>Notes</th>
                    <th>Created at</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($followUps->where('stage_id', 28) as $followUp)
                    <tr>
                        <td>{{ $lead->campaign->name }}</td>
                        <td>{{ $followUp->follow_up_time }}</td>
                        <td>{{ $followUp->follow_up_date }}</td>
                        <td>{{ $followUp->notes }}</td>
                        <td>{{ $followUp->created_at }}</td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endif --}}
