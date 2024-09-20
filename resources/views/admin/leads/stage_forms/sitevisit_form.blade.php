<form id="SitevisitFormId" method="POST" action="{{ route('admin.sitevisit.store') }}" enctype="multipart/form-data">
    @csrf
    <div id="showsitevisitScheduled" class="myDiv" style="display: none;">
        <div></div>
        <div class="modal-body">
            <input type="hidden" name="lead_id" value="{{ $lead->id }}">
            <input type="hidden" name="stage_id" value="11">

            <!-- Date Selection -->
            <div class="form-group">
                <label for="Date">Select Date</label>
                <input type="date"
                    class="form-control datepicker {{ $errors->has('form-control datepicker') ? 'is-invalid' : '' }}"
                    name="date" id="date" rows="3" required>{{ old('date') }}
            </div>

            <!-- Time Slot Selection -->

            <div class="form-group">
                <div id="time_slots_container">
                    <label>Time Slot:</label>
                    <div class="d-flex flex-wrap">
                        @foreach ($timeSlots as $slot)
                            <div class="card-slot p-2 border rounded m-2" data-value="{{ $slot }}">
                                {{ $slot }}
                            </div>
                        @endforeach
                    </div>
                    <input type="hidden" id="selected_time_slot" name="time_slot" required>
                </div>
            </div>
            <!-- Notes Section -->
            <div class="form-group">
                <label for="noteContent">Note Content</label>
                <textarea class="form-control {{ $errors->has('notes') ? 'is-invalid' : '' }}" name="notes" id="notes"
                    rows="4" required>{{ old('notes') }}</textarea>
            </div>

            <!-- Submit Button -->
            <div class="modal-footer">
                <button class="btn btn-danger" type="submit">
                    Save
                </button>
            </div>
        </div>
    </div>
</form>

<!-- JavaScript to Handle Time Slot Selection -->
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

<!-- Additional CSS for Active Slot Highlighting -->
<style>
    .card-slot.active {
        background-color: #007bff;
        color: white;
    }
</style>

@includeIf('admin.leads.partial.stage_forms')
