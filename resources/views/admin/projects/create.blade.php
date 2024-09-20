@extends('layouts.admin')

@section('content')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h2>{{ trans('global.create') }} {{ trans('cruds.project.title_singular') }}</h2>
        </div>
    </div>
    <div class="card card-primary card-outline">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.projects.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label class="required" for="name">{{ trans('cruds.project.fields.name') }}</label>
                    <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name"
                        id="name" value="{{ old('name', '') }}" required>
                    @if ($errors->has('name'))
                        <span class="text-danger">{{ $errors->first('name') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.project.fields.name_helper') }}</span>
                </div>

                <div class="form-group">
                    <label for="location">{{ trans('cruds.project.fields.location') }}</label>
                    <input class="form-control {{ $errors->has('location') ? 'is-invalid' : '' }}" type="text"
                        name="location" id="location" value="{{ old('location', '') }}">
                    @if ($errors->has('location'))
                        <span class="text-danger">{{ $errors->first('location') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.project.fields.location_helper') }}</span>
                </div>
                <div class="form-group">
                    <label>{{ trans('cruds.project.fields.fields') }}</label>
                    <div id="dynamic-fields">
                        @foreach ($fields as $index => $field)
                            <div class="field-row mb-2">
                                <div class="row">
                                    <div class="col-md-4">
                                        <input type="text" name="fields[{{ $index }}][text]" class="form-control"
                                            placeholder="Text" value="{{ $field['text'] }}" readonly>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="fields[{{ $index }}][value]"
                                            class="form-control" placeholder="Value"
                                            value="{{ old("fields.{$index}.value", $field['value']) }}" readonly>
                                    </div>
                                    <div class="col-md-3">
                                        <select name="fields[{{ $index }}][data_type]" class="form-control"
                                            readonly>
                                            <option value="string" {{ $field['data_type'] == 'string' ? 'selected' : '' }}>
                                                String</option>
                                            <option value="datetime"
                                                {{ $field['data_type'] == 'datetime' ? 'selected' : '' }}>DateTime</option>
                                            <option value="float" {{ $field['data_type'] == 'float' ? 'selected' : '' }}>
                                                Float</option>
                                            <option value="double" {{ $field['data_type'] == 'double' ? 'selected' : '' }}>
                                                Double</option>
                                            <option value="int" {{ $field['data_type'] == 'int' ? 'selected' : '' }}>Int
                                            </option>
                                            <option value="boolean"
                                                {{ $field['data_type'] == 'boolean' ? 'selected' : '' }}>Boolean</option>
                                            <option value="array" {{ $field['data_type'] == 'array' ? 'selected' : '' }}>
                                                Array</option>
                                            <option value="object" {{ $field['data_type'] == 'object' ? 'selected' : '' }}>
                                                Object</option>
                                            <option value="json" {{ $field['data_type'] == 'json' ? 'selected' : '' }}>
                                                Json</option>
                                        </select>
                                    </div>
                                    <input type="hidden" name="fields[{{ $index }}][enabled]"
                                        value="{{ $field['enabled'] }}">
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-secondary enable-field"
                                            {{ $field['enabled'] == 1 ? 'style=display:none;' : '' }}>Enable</button>
                                        <button type="button" class="btn btn-secondary disable-field"
                                            {{ $field['enabled'] == 0 ? 'style=display:none;' : '' }}>Disable</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <span class="help-block">{{ trans('cruds.project.fields.fields_helper') }}</span>
                </div>
                <div class="form-group">
                    <button class="btn btn-danger" type="submit">{{ trans('global.save') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Enable and Disable functionality
            $('#dynamic-fields').on('click', '.enable-field', function() {
                $(this).closest('.field-row').find('input, select').prop('readonly', false).prop('disabled',
                    false);
                $(this).hide();
                $(this).siblings('.disable-field').show();
                $(this).closest('.field-row').find('input[name$="[enabled]"]').val('1');
            });

            $('#dynamic-fields').on('click', '.disable-field', function() {
                $(this).closest('.field-row').find('input, select').prop('readonly', true).prop('disabled',
                    true);
                $(this).hide();
                $(this).siblings('.enable-field').show();
                $(this).closest('.field-row').find('input[name$="[enabled]"]').val('0');
            });

            // Initialize existing fields based on the 'enabled' field value
            $('#dynamic-fields .field-row').each(function() {
                var enabled = $(this).find('input[name$="[enabled]"]').val();
                if (enabled == '1') {
                    $(this).find('.enable-field').hide();
                    $(this).find('.disable-field').hide(); // Hide both buttons if enabled is 1
                } else {
                    $(this).find('.enable-field').show();
                    $(this).find('.disable-field').show();
                }
            });
        });
    </script>
@endsection
