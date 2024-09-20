<form id="applicationacceptedFormId" method="POST" action="{{ route('admin.application.applicationaccepted') }}" class="myForm"
enctype="multipart/form-data">
@csrf
<div id="applicationacceptedContent" style="display: none;">
    <div>
        <input type="hidden" name="lead_id" value="{{ $lead->id }}">
        <p> Lead ID: {{ $lead->id }}</p>

        <div class="modal-body">
            <input type="hidden" name="stage_id" value="30">

            <div class="form-group">
                <label for="noteContent">Note Content</label>
                <textarea class="form-control {{ $errors->has('notes') ? 'is-invalid' : '' }}" name="notes" id="notes"
                    rows="4" required>{{ old('notes') }}</textarea>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" type="submit">Save</button>
            </div>
        </div>
    </div>
</div>
</form>
