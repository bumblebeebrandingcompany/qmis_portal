@extends('layouts.admin')

@section('content')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h2>{{ trans('global.edit') }} {{ trans('cruds.project.title_singular') }}</h2>
        </div>
    </div>
    <div class="card card-primary card-outline">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.projects.update', $project->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <!-- Other form fields here -->
                <div class="form-group">
                    <label>{{ trans('cruds.project.fields.fields') }}</label>
                    <div id="dynamic-fields">
                        @foreach ($fields as $index => $field)
                            <div class="field-row mb-2">
                                <div class="row">
                                    <div class="col-md-4">
                                        <input type="text" name="fields[{{ $index }}][text]" class="form-control"
                                            placeholder="Text" value="{{ $field['text'] ?? '' }}" readonly>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="fields[{{ $index }}][value]" class="form-control"
                                            placeholder="Value"
                                            value="{{ old("fields.{$index}.value", $field['value'] ?? '') }}" readonly>
                                    </div>
                                    <div class="col-md-3">
                                        <select name="fields[{{ $index }}][data_type]" class="form-control" disabled>
                                            <option value="string"
                                                {{ ($field['data_type'] ?? '') == 'string' ? 'selected' : '' }}>String
                                            </option>
                                            <option value="datetime"
                                                {{ ($field['data_type'] ?? '') == 'datetime' ? 'selected' : '' }}>DateTime
                                            </option>
                                            <option value="float"
                                                {{ ($field['data_type'] ?? '') == 'float' ? 'selected' : '' }}>Float
                                            </option>
                                            <option value="double"
                                                {{ ($field['data_type'] ?? '') == 'double' ? 'selected' : '' }}>Double
                                            </option>
                                            <option value="int"
                                                {{ ($field['data_type'] ?? '') == 'int' ? 'selected' : '' }}>Int</option>
                                            <option value="boolean"
                                                {{ ($field['data_type'] ?? '') == 'boolean' ? 'selected' : '' }}>Boolean
                                            </option>
                                            <option value="array"
                                                {{ ($field['data_type'] ?? '') == 'array' ? 'selected' : '' }}>Array
                                            </option>
                                            <option value="object"
                                                {{ ($field['data_type'] ?? '') == 'object' ? 'selected' : '' }}>Object
                                            </option>
                                            <option value="json"
                                                {{ ($field['data_type'] ?? '') == 'json' ? 'selected' : '' }}>Json</option>
                                        </select>
                                    </div>
                                    <input type="hidden" name="fields[{{ $index }}][enabled]"
                                        value="{{ $field['enabled'] ?? 'false' }}">
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-danger remove-field">-</button>
                                        <button type="button" class="btn btn-primary enable-field"
                                            style="display:{{ ($field['enabled'] ?? 'false') == 'true' ? 'none' : 'block' }};">Enable</button>
                                        <button type="button" class="btn btn-secondary disable-field"
                                            style="display:{{ ($field['enabled'] ?? 'false') == 'true' ? 'block' : 'none' }};">Disable</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <!-- Button to add new fields -->
                        <button type="button" class="btn btn-success add-field">+</button>
                    </div>
                    <span class="help-block">{{ trans('cruds.project.fields.fields_helper') }}</span>
                </div>
                <div class="form-group">
                    <button class="btn btn-danger" type="submit">{{ trans('global.update') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection


@section('scripts')
    <script>
        $(document).ready(function() {
            $('#dynamic-fields').on('click', '.add-field', function() {
                var newIndex = $('#dynamic-fields .field-row').length;
                var newFieldRow = $('.default-field:first').clone(); // Clone the default fields

                newFieldRow.find('input, select').prop('readonly', false).prop('disabled',
                false); // Make all inputs editable
                newFieldRow.find('input[name^="fields["]').each(function() {
                    $(this).val(''); // Clear values for new fields
                });
                newFieldRow.find('select[name^="fields["]').each(function() {
                    $(this).val(''); // Clear values for new fields
                });

                // Update the name attributes with the new index
                newFieldRow.find('input[name^="fields["]').each(function() {
                    var name = $(this).attr('name').replace(/\[\d+\]/, '[' + newIndex + ']');
                    $(this).attr('name', name);
                });
                newFieldRow.find('select[name^="fields["]').each(function() {
                    var name = $(this).attr('name').replace(/\[\d+\]/, '[' + newIndex + ']');
                    $(this).attr('name', name);
                });

                // Set enabled field to true by default
                newFieldRow.find('input[name$="[enabled]"]').val('true');

                // Make sure remove button is shown for the new row
                newFieldRow.find('.remove-field').show();

                // Show enable/disable buttons
                newFieldRow.find('.enable-field').show();
                newFieldRow.find('.disable-field').show();

                $('#dynamic-fields .add-field').remove();
                $('#dynamic-fields').append(newFieldRow);
                $('#dynamic-fields').append(
                    '<button type="button" class="btn btn-success add-field">+</button>');
            });

            $('#dynamic-fields').on('click', '.remove-field', function() {
                if ($('#dynamic-fields .field-row').length > 1) {
                    $(this).closest('.field-row').remove();
                }
            });

            $('#dynamic-fields').on('click', '.enable-field', function() {
                $(this).closest('.field-row').find('input, select').prop('readonly', false).prop('disabled',
                    false);
                $(this).hide();
                $(this).siblings('.disable-field').show();
                $(this).closest('.field-row').find('input[name$="[enabled]"]').val('true');
            });

            $('#dynamic-fields').on('click', '.disable-field', function() {
                $(this).closest('.field-row').find('input, select').prop('readonly', true).prop('disabled',
                    true);
                $(this).hide();
                $(this).siblings('.enable-field').show();
                $(this).closest('.field-row').find('input[name$="[enabled]"]').val('false');
            });
        });
    </script>
@endsection
