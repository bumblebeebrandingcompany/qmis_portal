@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1>Create Field</h1>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('admin.fields.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="text">Text</label>
                        <input type="text" class="form-control" id="text" name="text" value="{{ old('text') }}"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="value">Value</label>
                        <input type="text" class="form-control" id="value" name="value" value="{{ old('value') }}"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="data_type">Data Type</label>
                        <select class="form-control" id="data_type" name="data_type" required>
                            <option value="">Select Data Type</option>
                            <option value="string" {{ old('data_type') == 'string' ? 'selected' : '' }}>String</option>
                            <option value="integer" {{ old('data_type') == 'integer' ? 'selected' : '' }}>Integer</option>
                            <option value="float" {{ old('data_type') == 'float' ? 'selected' : '' }}>Float</option>
                            <option value="boolean" {{ old('data_type') == 'boolean' ? 'selected' : '' }}>Boolean</option>
                            <option value="date" {{ old('data_type') == 'date' ? 'selected' : '' }}>Date</option>
                            <option value="time" {{ old('data_type') == 'time' ? 'selected' : '' }}>Time</option>
                            <option value="json" {{ old('data_type') == 'json' ? 'selected' : '' }}>Json</option>
                        </select>
                    </div>
                    <input type="hidden" name="enabled" value="0">

                    {{-- <div class="form-group">
                        <label for="enabled">Enabled</label>
                        <input type="checkbox" id="enabled" name="enabled" value="1" {{ old('enabled') ? 'checked' : '' }}>
                        <input type="hidden" name="enabled" value="0"> --}}
                    {{-- <small class="form-text text-muted">Check this box if the field is enabled.</small> --}}
                    {{-- </div> --}}
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
@endsection
