@extends('layouts.admin')
@section('content')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h2>
                {{ trans('global.edit') }} {{ trans('cruds.project.title_singular') }}
            </h2>
        </div>
    </div>
    <div class="card card-primary card-outline">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.projects.update', [$project->id]) }}" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div class="form-group">
                    <label class="required" for="name">{{ trans('cruds.project.fields.name') }}</label>
                    <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name"
                        id="name" value="{{ old('name', $project->name) }}" required>
                    @if ($errors->has('name'))
                        <span class="text-danger">{{ $errors->first('name') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.project.fields.name_helper') }}</span>
                </div>
                <div class="form-group">
                    <label for="start_date">{{ trans('cruds.project.fields.start_date') }}</label>
                    <input class="form-control date {{ $errors->has('start_date') ? 'is-invalid' : '' }}" type="text"
                        name="start_date" id="start_date" value="{{ old('start_date', $project->start_date) }}">
                    @if ($errors->has('start_date'))
                        <span class="text-danger">{{ $errors->first('start_date') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.project.fields.start_date_helper') }}</span>
                </div>
                <div class="form-group">
                    <label for="end_date">{{ trans('cruds.project.fields.end_date') }}</label>
                    <input class="form-control date {{ $errors->has('end_date') ? 'is-invalid' : '' }}" type="text"
                        name="end_date" id="end_date" value="{{ old('end_date', $project->end_date) }}">
                    @if ($errors->has('end_date'))
                        <span class="text-danger">{{ $errors->first('end_date') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.project.fields.end_date_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required" for="client_id">{{ trans('cruds.project.fields.client') }}</label>
                    <select class="form-control select2 {{ $errors->has('client') ? 'is-invalid' : '' }}" name="client_id"
                        id="client_id" required>
                        @foreach ($clients as $id => $entry)
                            <option value="{{ $id }}"
                                {{ (old('client_id') ? old('client_id') : $project->client->id ?? '') == $id ? 'selected' : '' }}>
                                {{ $entry }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('client'))
                        <span class="text-danger">{{ $errors->first('client') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.project.fields.client_helper') }}</span>
                </div>
                <div class="form-group">
                    <label for="location">{{ trans('cruds.project.fields.location') }}</label>
                    <input class="form-control {{ $errors->has('location') ? 'is-invalid' : '' }}" type="text"
                        name="location" id="location" value="{{ old('location', $project->location) }}">
                    @if ($errors->has('location'))
                        <span class="text-danger">{{ $errors->first('location') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.project.fields.location_helper') }}</span>
                </div>
                <div class="form-group">
                    <label for="description">{{ trans('cruds.project.fields.description') }}</label>
                    <textarea class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description"
                        id="description">{!! old('description', $project->description) !!}</textarea>
                    @if ($errors->has('description'))
                        <span class="text-danger">{{ $errors->first('description') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.project.fields.description_helper') }}</span>
                </div>
                {{-- essential fields --}}
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="essential_fields">{{ trans('cruds.project.fields.essential') }}</label>
                        </div>
                    </div>
                    <div id="essential-fields-container">
                        @if ($project->essential_fields)

                            @foreach ($project->essential_fields as $key => $essentialField)
                                <div class="row mb-2">
                                    <div class="col-md-3">
                                        <label for="essential_fields">{{ $essentialField['name_data'] }}</label>
                                        <input type="hidden" name="essential_fields[{{ $key }}][name_data]" value="{{ $essentialField['name_data'] }}">
                                    </div>
                                    <div class="col-md-3">
                                        <input class="form-control" type="text"
                                            name="essential_fields[{{ $key }}][name_key]"
                                            value="{{ $essentialField['name_key'] }}" readonly>
                                    </div>
                                    <div class="col-md-3">
                                        <input class="form-control" type="text"
                                            name="essential_fields[{{ $key }}][name_value]"
                                            value="{{ $essentialField['name_value'] }}" placeholder="Field Value" readonly>
                                    </div>
                                    <div class="col-md-1">
                                        <label class="switch">
                                            <input type="checkbox" name="essential_fields[{{ $key }}][enabled]"
                                                value="1"
                                                {{ isset($essentialField['enabled']) && $essentialField['enabled'] === '1' ? 'checked' : '' }}>
                                            <span class="slider round"></span>
                                            <input type="hidden" name="essential_fields[{{ $key }}][disabled]"
                                                value="0">
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                {{-- sales fields --}}
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="sales_fields">{{ trans('cruds.project.fields.sales') }}</label>
                        </div>
                    </div>
                    <div id="sales-fields-container">
                        @if ($project->sales_fields)

                            @foreach ($project->sales_fields as $key => $salesField)
                                <div class="row mb-2">
                                    <div class="col-md-3">
                                        <label for="sales_fields">{{ $salesField['name_data'] }}</label>
                                        <input type="hidden" name="sales_fields[{{ $key }}][name_data]" value="{{ $salesField['name_data'] }}">
                                    </div>
                                    <div class="col-md-3">
                                        <input class="form-control" type="text"
                                            name="sales_fields[{{ $key }}][name_key]"
                                            value="{{ $salesField['name_key'] }}" readonly>
                                    </div>
                                    <div class="col-md-3">
                                        <input class="form-control" type="text"
                                            name="sales_fields[{{ $key }}][name_value]"
                                            value="{{ $salesField['name_value'] }}" readonly>
                                    </div>
                                    <div class="col-md-1">
                                        <label class="switch">
                                            <input type="checkbox" name="sales_fields[{{ $key }}][enabled]"
                                                value="1"
                                                {{ isset($salesField['enabled']) && $salesField['enabled'] === '1' ? 'checked' : '' }}>
                                            <span class="slider round"></span>
                                            <input type="hidden" name="sales_fields[{{ $key }}][disabled]"
                                                value="0">
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                {{-- system fields --}}
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="system_fields">{{ trans('cruds.project.fields.system') }}</label>
                        </div>
                    </div>
                    <div id="system-fields-container">
                        @if ($project->system_fields)

                            @foreach ($project->system_fields as $key => $systemField)
                                <div class="row mb-2">
                                    <div class="col-md-3">
                                        <label for="system_fields">{{ $systemField['name_data'] }}</label>
                                        <input type="hidden" name="system_fields[{{ $key }}][name_data]" value="{{ $systemField['name_data'] }}">
                                    </div>
                                    <div class="col-md-3">
                                        <input class="form-control" type="text"
                                            name="system_fields[{{ $key }}][name_key]"
                                            value="{{ $systemField['name_key'] }}" readonly>
                                    </div>
                                    <div class="col-md-3">
                                        <input class="form-control" type="text"
                                            name="system_fields[{{ $key }}][name_value]"
                                            value="{{ $systemField['name_value'] }}" readonly>
                                    </div>
                                    <div class="col-md-1">
                                        <label class="switch">
                                            <input type="checkbox" name="system_fields[{{ $key }}][enabled]"
                                                value="1"
                                                {{ isset($systemField['enabled']) && $systemField['enabled'] === '1' ? 'checked' : '' }}>
                                            <span class="slider round"></span>
                                            <input type="hidden" name="system_fields[{{ $key }}][disabled]"
                                                value="0">
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                {{-- custom fields --}}
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="custom_fields">{{ trans('cruds.project.fields.custom') }}</label>
                        </div>
                        <div class="col-md-6 text-right">
                            <button type="button" id="add-custom-field" class="btn btn-primary">Add Field</button>
                        </div>
                    </div>
                    <div id="custom-fields-container">
                        @if ($project->custom_fields)

                            @foreach ($project->custom_fields as $key => $customField)
                                <div class="row mb-2">
                                    <div class="col-md-3">
                                        <label for="custom_fields">{{ $customField['name_data'] }}</label>
                                        <input type="hidden" name="custom_fields[{{ $key }}][name_data]" value="{{ $customField['name_data'] }}">
                                    </div>
                                    <div class="col-md-3">
                                        <input class="form-control" type="text"
                                            name="custom_fields[{{ $key }}][name_key]"
                                            value="{{ $customField['name_key'] }}" placeholder="Field Value">
                                    </div>
                                    <div class="col-md-3">
                                        <input class="form-control" type="text"
                                            name="custom_fields[{{ $key }}][name_value]"
                                            value="{{ $customField['name_value'] }}" placeholder="Field Value">
                                    </div>
                                    <div class="col-md-1">
                                        <label class="switch">
                                            <input type="checkbox" name="custom_fields[{{ $key }}][enabled]"
                                                value="1"
                                                {{ isset($customField['enabled']) && $customField['enabled'] === '1' ? 'checked' : '' }}>
                                            <span class="slider round"></span>
                                            <input type="hidden" name="custom_fields[{{ $key }}][disabled]"
                                                value="0">
                                        </label>
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-danger remove-field">Remove</button>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
                <div class="form-group text-end">
                    <input type="hidden" id="sales-fields-json" name="sales_fields_json" value="">
                    <button class="btn btn-danger" type="submit">
                        {{ trans('global.save') }}
                    </button>
                </div>
        </div>
        </form>
    </div>
    </div>

    </form>
    </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Initialize an empty object to store essential fields
            const essentialFields = {};

            // Form submission event
            $('#essential-fields-form').on('submit', function(event) {
                event.preventDefault(); // Prevent the default form submission

                // Collect and format essential fields as an array of objects
                const essentialFieldsArray = [];

                $('div.row.mb-2').each(function() {
                    const nameData = $(this).find('input[name="essential_fields[name_data][]"]')
                        .val();
                    const nameKey = $(this).find('input[name="essential_fields[name_key][]"]')
                .val();
                    const nameValue = $(this).find('input[name="essential_fields[name_value][]"]')
                        .val();
                    const enabled = $(this).find('input[name="essential_fields[enabled][]"]').prop(
                        'checked') ? 1 : 0;

                    essentialFieldsArray.push({
                        name_data: nameData,
                        name_key: nameKey,
                        name_value: nameValue,
                        enabled: enabled,
                    }, );
                }, );

                essentialFields['essential_fields'] = essentialFieldsArray;

                $('#essential-fields-json').val(JSON.stringify(essentialFields));
            });
        }, );
    </script>


    {{-- custom fields --}}
    <script>
        $(document).ready(function() {
            let fieldCounter =
                {{ isset($project) && is_array($project->custom_fields) ? count($project->custom_fields) : 0 }};
            const customFields = {};

            $('#add-custom-field').on('click', function() {
                fieldCounter++;

                const customFieldInput = `
            <div class="row mb-2">
                <div class="col-md-3">
                    <input class="form-control" type="text" name="custom_fields[${fieldCounter}][name_data]" placeholder="Field Name">
                </div>
                <div class="col-md-3">
                    <input class="form-control" type="text" name="custom_fields[${fieldCounter}][name_key] placeholder="Field Value">
                </div>
                <div class="col-md-3">
                    <input class="form-control" type="text" name="custom_fields[${fieldCounter}][name_value]" placeholder="Field Value">
                </div>
                <div class="col-md-1">
                    <label class="switch">
                        <input type="checkbox" name="custom_fields[${fieldCounter}][enabled]" value="1">
                        <span class="slider round"></span>
                        <input type="hidden" name="custom_fields[${fieldCounter}][disabled]" value="0">
                    </label>
                </div>

                <div class="col-md-1">
                    <button type="button" class="btn btn-danger remove-field">Remove</button>
                </div>
            </div>`;
                $('#custom-fields-container').append(customFieldInput);
            });

            // Remove Field button click event
            $('#custom-fields-container').on('click', '.remove-field', function() {
                $(this).parent().parent().remove();
            });

            // Form submission event
            $('form').on('submit', function() {
                // Collect and format custom fields as JSON
                const customFieldsArray = [];
                $('#custom-fields-container input[name^="custom_fields"]').each(function() {
                    const fieldData = $(this).attr('name').split('[');
                    const fieldIndex = fieldData[1];
                    const fieldName = fieldData[2].split(']')[0];
                    const fieldValue = $(this).val();
                    const fieldDataValue = $(`input[name="custom_fields[${fieldIndex}][data]"]`)
                        .val();
                    const isEnabled = $(`input[name="custom_fields[${fieldIndex}][enabled]"]`).prop(
                        'checked');
                    customFieldsArray.push({
                        name_key: fieldName,
                        nmae_value: fieldValue,
                        name_data: fieldDataValue,
                        enabled: isEnabled,
                    });
                });

                customFields['custom_fields'] = customFieldsArray;
                $('#custom-fields-json').val(JSON.stringify(customFields)); // Serialize as JSON
            });
        });
    </script>

    {{-- sales fields --}}
    <script>
        $(document).ready(function() {
            const salesFields = [];
            // Form submission event
            $('form').on('submit', function() {
                // Form submission event
                $('#sales-fields-form').on('submit', function(event) {
                    event.preventDefault(); // Prevent the default form submission

                    // Collect and format sales fields as an array of objects
                    const salesFields = [];

                    $('div.row.mb-2').each(function() {
                        const nameKey = $(this).find(
                                'input[name="sales_fields[name_key][]"]')
                            .val();
                        const nameData = $(this).find(
                                'input[name="sales_fields[name_data][]"]')
                            .val();
                        const nameValue = $(this).find(
                                'input[name="sales_fields[name_value][]"]')
                            .val();
                        const enabled = $(this).find(
                                'input[name="sales_fields[enabled][]"]').prop('checked') ?
                            1 : 0;

                        salesFields.push({
                            name_data: nameData,
                            name_key: nameKey,
                            name_value: nameValue,
                            enabled: enabled,
                        });
                    });
                });
            });

            salesFields['sales_fields'] = salesFieldsArray;
            $('#sales-fields-json').val(JSON.stringify(salesFields)); // Serialize as JSON
        });
    </script>


    {{-- system fields --}}
    <script>
        $(document).ready(function() {
            const systemFields = {};
            $('form').on('submit', function() {
                // Form submission event
                $('#system-fields-form').on('submit', function(event) {
                    event.preventDefault();

                    const systemFields = [];

                    $('div.row.mb-2').each(function() {
                        const nameData = $(this).find(
                            'input[name="system_fields[name_data][]"]')
                        const nameKey = $(this).find(
                                'input[name="system_fields[name_key][]"]')
                            .val();
                        const nameValue = $(this).find(
                                'input[name="system_fields[name_value][]"]')
                            .val();
                        const enabled = $(this).find(
                            'input[name="system_fields[enabled][]"]').prop(
                            'checked') ? 1 : 0;

                        systemFields.push({
                            name_data: nameData,
                            name_key: nameKey,
                            name_value: nameValue,
                            enabled: enabled,
                        });
                    });
                });
            });

            systemFields['system_fields'] = essentialFieldsArray;
            $('#system-fields-json').val(JSON.stringify(essentialFields)); // Serialize as JSON

        });
    </script>
@endsection


@section('scripts')
    <script>
        $(document).ready(function() {
            function SimpleUploadAdapter(editor) {
                editor.plugins.get('FileRepository').createUploadAdapter = function(loader) {
                    return {
                        upload: function() {
                            return loader.file
                                .then(function(file) {
                                    return new Promise(function(resolve, reject) {
                                        // Init request
                                        var xhr = new XMLHttpRequest();
                                        xhr.open('POST',
                                            '{{ route('admin.projects.storeCKEditorImages') }}',
                                            true);
                                        xhr.setRequestHeader('x-csrf-token', window._token);
                                        xhr.setRequestHeader('Accept', 'application/json');
                                        xhr.responseType = 'json';

                                        // Init listeners
                                        var genericErrorText =
                                            `Couldn't upload file: ${ file.name }.`;
                                        xhr.addEventListener('error', function() {
                                            reject(genericErrorText)
                                        });
                                        xhr.addEventListener('abort', function() {
                                            reject()
                                        });
                                        xhr.addEventListener('load', function() {
                                            var response = xhr.response;

                                            if (!response || xhr.status !== 201) {
                                                return reject(response && response
                                                    .message ?
                                                    `${genericErrorText}\n${xhr.status} ${response.message}` :
                                                    `${genericErrorText}\n ${xhr.status} ${xhr.statusText}`
                                                );
                                            }

                                            $('form').append(
                                                '<input type="hidden" name="ck-media[]" value="' +
                                                response.id + '">');

                                            resolve({
                                                default: response.url
                                            });
                                        });

                                        if (xhr.upload) {
                                            xhr.upload.addEventListener('progress', function(
                                                e) {
                                                if (e.lengthComputable) {
                                                    loader.uploadTotal = e.total;
                                                    loader.uploaded = e.loaded;
                                                }
                                            });
                                        }

                                        // Send request
                                        var data = new FormData();
                                        data.append('upload', file);
                                        data.append('crud_id', '{{ $project->id ?? 0 }}');
                                        xhr.send(data);
                                    });
                                })
                        }
                    };
                }
            }

            var allEditors = document.querySelectorAll('.ckeditor');
            for (var i = 0; i < allEditors.length; ++i) {
                ClassicEditor.create(
                    allEditors[i], {
                        extraPlugins: [SimpleUploadAdapter]
                    }
                );
            }
        });
    </script>
@endsection

ChatGPT
