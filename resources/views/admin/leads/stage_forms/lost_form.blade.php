<form id="LostFormId" method="POST" action="{{ route('admin.lost.store') }}" class="myForm"
enctype="multipart/form-data">
@csrf
<div id="lostContent" style="display: none;">
        <div>
        </div>
        <div class="modal-body">
            <input type="hidden" name="lead_id" value="{{ $lead->id }}">
            <input type="hidden" name="tag_id" value="17">
            <label for="reasonSelects">Select Reason:</label>
            <select id="reasonSelects" name="reason" onchange="toggleInputFields()">
                @foreach ($notintrested as $notintrest)
                            @if ($notintrest->notes !== null)
                                <option value="{{ $notintrest->notes }}">{{ $notintrest->notes }}</option>
                            @endif
                        @endforeach
                        <option value="Wrong Number">Wrong Number</option>
                <option value="others">Others</option>
            </select>
            <!-- Hidden input field initially -->
            <input class="text" name="notes" id="otherLocationsInputs" style="display: none;">
        </div>
        <div class="modal-footer">
            <button class="btn btn-danger" type="submit">Save</button>
        </div>
    </div>
</form>
<script>
    function toggleInputFields() {
        var selectsElement = document.getElementById("reasonSelects");
        var inputField = document.getElementById("otherLocationsInputs");
        if (selectsElement.value === "others") {
            inputField.style.display = "block";
        } else {
            inputField.style.display = "none";
        }

    }
</script>

