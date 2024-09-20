@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1>Edit Field</h1>
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

                <form action="{{ route('admin.fields.update', $field->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="text">Text</label>
                        <input type="text" class="form-control" id="text" name="text" value="{{ $field->text }}" required>
                    </div>
                    <div class="form-group">
                        <label for="value">Value</label>
                        <input type="text" class="form-control" id="value" name="value" value="{{ $field->value }}" required>
                    </div>
                    <div class="form-group">
                        <label for="data_type">Data Type</label>
                        <select class="form-control" id="data_type" name="data_type" required>
                            <option value="">Select Data Type</option>
                            <option value="string" {{ $field->data_type == 'string' ? 'selected' : '' }}>String</option>
                            <option value="integer" {{ $field->data_type == 'integer' ? 'selected' : '' }}>Integer</option>
                            <option value="float" {{ $field->data_type == 'float' ? 'selected' : '' }}>Float</option>
                            <option value="boolean" {{ $field->data_type == 'boolean' ? 'selected' : '' }}>Boolean</option>
                            <option value="date" {{ $field->data_type == 'date' ? 'selected' : '' }}>Date</option>
                            <option value="time" {{ $field->data_type == 'time' ? 'selected' : '' }}>Time</option>
                            <option value="json" {{ $field->data_type == 'json' ? 'selected' : '' }}>Json</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
@endsection
