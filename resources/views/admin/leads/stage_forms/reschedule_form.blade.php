<form id="ResheduleFormId" method="POST" action="{{ route('admin.sitevisit.reschedule') }}" enctype="multipart/form-data">
    @csrf
    <div id="showrescheduled" class="myDiv" style="display: none;">
        <input type="hidden" name="lead_id" value="{{ $lead->id }}">
        <input type="hidden" name="stage_id" value="19">

        <!-- Date Selection -->
        <div class="form-group">
            <label for="Date">Select Date</label>
            <input type="date" class="form-control datepicker" name="date" id="date" required>
        </div>

        <!-- Time Slot Selection -->
<div class="form-group">
    <label for="time_slot">Select Time Slot</label>
    <select class="form-control" name="time_slot" id="time_slot" required>
        <option value="">Select a time slot</option>
        @foreach ($timeSlots as $slot)
            <option value="{{ $slot }}">{{ $slot }}</option>
        @endforeach
    </select>
</div>

        <!-- Notes Section -->
        <div class="form-group">
            <label for="noteContent">Note Content</label>
            <textarea class="form-control" name="notes" id="notes" rows="4" required>{{ old('notes') }}</textarea>
        </div>

        <!-- Submit Button -->
        <div class="modal-footer">
            <button class="btn btn-danger" type="submit">Save</button>
        </div>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const timeSlots = document.querySelectorAll('.card-slot');
        const hiddenInput = document.getElementById('selected_time_slot');

        timeSlots.forEach(slot => {
            slot.addEventListener('click', function () {
                // Remove 'active' class from all slots
                timeSlots.forEach(s => s.classList.remove('active'));

                // Add 'active' class to clicked slot
                this.classList.add('active');

                // Update hidden input value with the selected slot
                hiddenInput.value = this.getAttribute('data-value');
            });
        });
    });
</script>
