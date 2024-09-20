<form id="SpamFormId" method="POST" action="{{ route('admin.notes.store') }}"
class="myForm" enctype="multipart/form-data">
@csrf
<div id="spamContent" style="display: none;">

    <div class="modal-body">
        <input type="hidden" name="lead_id" value="{{ $lead->id }}">
        <input type="hidden" name="stage_id" value="15">
        <input type="hidden" name="stage" value="spam">

        <div class="form-group">
            <div class="form-group">
                <label class=float-left for="noteContent">Note Content</label>
                <textarea class="form-control {{ $errors->has('note_text') ? 'is-invalid' : '' }}" name="note_text" id="note_text"
                    rows="4" required>{{ old('note_text') }}</textarea>
            </div>

        </div>
        <div class="modal-footer">
            <button class="btn btn-danger" type="submit">Save</button>
        </div>
    </div>
</div>
</form>
@includeIf('admin.leads.partial.stage_forms')
