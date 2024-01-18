<h3 class="card-title"> Lead ID: {{ $lead->id }}</h3>
<br>
<div class="table-responsive">

<table class="table">
    <thead>
        <tr>
            <th>Campaign Name</th>
            <th>Follow-Up Time</th>
            <th>Follow-Up Date</th>

            <th>Follow-Up By</th>
<th>Notes</th>
<th>Created at</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($followUps as $followUp)
            <tr>
                <td>{{ $lead->campaign->campaign_name }}</td>
                <td>{{ $followUp->follow_up_time }}</td>
                <td>{{ $followUp->follow_up_date }}</td>

                <td>
                    {{ $followUp->users->representative_name }}
                </td>
                <td>{{ $followUp->notes }}</td>
                <td>{{ $followUp->created_at }}</td>

            </tr>
        @endforeach
    </tbody>
</table>
</div>
<div class="d-flex justify-content-end">
    {{ $followUps->links('pagination::bootstrap-4') }}
</div>
