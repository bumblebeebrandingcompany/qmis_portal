<table class="table">
    <thead>
        <tr>
            <th>Reference Number</th>
            <th>Campaign Name</th>
            <th>Follow-Up Time</th>
            <th>Follow-Up Date</th>

            <th>Follow-Up By</th>
            {{-- <th>Action</th> --}}
        </tr>
    </thead>
    <tbody>
        @foreach ($followUps as $followUp)
            <tr>
                <td>{{ $lead->ref_num }}</td>
                <td>{{ $lead->campaign->campaign_name }}</td>
                <td>{{ $followUp->follow_up_time }}</td>
                <td>{{ $followUp->follow_up_date }}</td>
                {{-- <td>
                    @foreach ($agencies as $id => $agency)
                        @foreach ($agency->agencyUsers as $user)
                            <option value="{{ $user->id }}" {{ old('user_id', $sitevisit->user_id) == $user->id ? 'selected' : '' }}>
                                {{ $agency->$user_id->representative_name }}
                            </option>
                        @endforeach
                    @endforeach
                </td> --}}
                <td>
                    {{ $followUp->users->representative_name }}
                </td>

            </tr>
        @endforeach
    </tbody>
</table>

{{-- <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Delete Confirmation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this item?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger">Delete</button>
            </div>
        </div>
    </div>
</div> --}}
