<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover ajaxTable datatable datatable-walkin" id="sitevisitTable">
        <thead>
            <tr>
                <th>No</th>
                <th>Ref Num</th>
                <th>Site Visit No</th>
                <th>Father Name</th>
                <th>Mother Name</th>
                <th>Student Name</th>
                <th>Date</th>
                <th>Time Slot</th>
                <th>Created At</th>
                {{-- <th>Timer</th> --}}
            </tr>
        </thead>
        <tbody>
            @php
                $counter = 1;
            @endphp
            {{-- <tr>
                <td>{{ $counter }}</td>

                <td>
                    @foreach ($leads as $lead)
                        @if ($lead->id === $sitevisit->lead_id)
                                {{ $lead->ref_num }}
                        @endif
                    @endforeach
                </td>
                <td>
                    {{ $sitevisit->ref_num }}
                </td>
                <td>
                    @php
                        $leadForSiteVisit = $leads->firstWhere('id', $sitevisit->lead_id);
                    @endphp

                    {{ $leadForSiteVisit->father_details['name'] ?? 'No Name Found' }}
                </td>

                <td>
                    {{ $leadForSiteVisit->mother_details['name'] ?? 'No Name Found' }}
                </td>

                <td>
                    @php
                        $students = json_decode($leadForSiteVisit->student_details, true);
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
                </td>
                <td>
                    {{ $sitevisit->date }}
                </td>
                <td>
                    {{ $sitevisit->time_slot }}
                </td>

                <td>
                    {{ $sitevisit->created_at }}
                </td>
            </tr> --}}
        </tbody>
    </table>
</div>
