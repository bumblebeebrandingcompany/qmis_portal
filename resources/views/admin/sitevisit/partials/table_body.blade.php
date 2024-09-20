<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


<tr data-created-at="{{ $sitevisit->follow_up_date }}">
    <td>{{ $counter }}</td>


    <td>
        @foreach ($lead as $leads)
            @if ($leads->id === $sitevisit->lead_id)
                <a href="{{ route('admin.leads.show', ['lead' => $leads->id]) }}">
                    {{ $leads->ref_num }}
                </a>
            @endif
        @endforeach
    </td>
    <td>
        @if ($sitevisit->ref_num)
            {{ $sitevisit->ref_num }}
        @else
            @php
                $matchedsitevisit = $sitevisit->firstWhere('lead_id', $sitevisit->lead_id);
            @endphp
            {{ $matchedsitevisit->ref_num ?? '' }}
        @endif
    </td>
    <td>
        {{-- @if (is_array($sitevisit->lead->student_details) && count($sitevisit->lead->student_details) > 0)
            <ul>
                @foreach ($sitevisit->lead->student_details as $student)
                    <li>{{ $student['name'] ?? '' }}</li>
                @endforeach
            </ul>
        @else
            No children listed.
        @endif --}}
    </td>
    <td>
        @foreach ($lead as $leads)
            @if ($leads->id === $sitevisit->lead_id)
                {{ $leads->father_details['name'] ?? '' }}
            @endif
        @endforeach
        
    </td>
    <td>
        @foreach ($lead as $leads)
            @if ($leads->id === $sitevisit->lead_id)
                {{ $leads->mother_details['name'] ?? '' }}
            @endif
        @endforeach
    </td>


    <td>
        {{ $sitevisit->date }}
    </td>

    <td>
        {{ $sitevisit->time_slot }}
    </td>
    <td>
        {{ $parentStages[$sitevisit->stage_id] }}
    </td>
    <td>
        {{-- {{ $sitevisit->created_at->format('Y-m-d') }} --}}
        {{ $sitevisit->created_at }}

    </td>
    {{-- <td></td> --}}
    {{-- <script>
        var countdownElements = document.getElementsByClassName('countdown');
        Array.from(countdownElements).forEach(function(countdownElement) {
            var timeRemaining = parseInt(countdownElement.getAttribute('data-time'));
            var countdownInterval = setInterval(function() {
                if (timeRemaining > 0) {
                    var minutes = Math.floor(timeRemaining / 60);
                    var seconds = timeRemaining % 60;
                    countdownElement.innerHTML = (minutes < 10 ? '0' : '') + minutes + ':' + (seconds < 10 ?
                        '0' : '') + seconds;
                    timeRemaining--;
                } else {
                    clearInterval(countdownInterval);
                    countdownElement.innerHTML = 'Ended';
                }
            }, 1000); // Update every second
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
    </script> --}}
</tr>
