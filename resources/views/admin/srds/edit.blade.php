@extends('layouts.admin')

@section('content')
<div class="row mb-2">
    <div class="col-sm-6">
        <h2>Edit SRD</h2>
    </div>
</div>
<div class="card card-primary card-outline">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.srd.update', $srd->id) }}">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="srd">SRD</label>
                <input class="form-control" type="text" name="srd" id="srd" value="{{ $srd->srd }}" required>
            </div>
            <div class="form-group">
                <label for="project_id">Project</label>
                <select class="form-control" name="project_id" id="project_id" required>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ $project->id == $srd->project_id ? 'selected' : '' }}>{{ $project->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="campaign">Campaign</label>
                <input class="form-control" type="text" name="campaign" id="campaign" value="{{ $srd->campaign }}" required>
            </div>
            <div class="form-group">
                <label for="source">Source</label>
                <input class="form-control" type="text" name="source" id="source" value="{{ $srd->source }}" required>
            </div>
            <button type="submit" class="btn btn-success">Update</button>
        </form>
    </div>
</div>
@endsection
