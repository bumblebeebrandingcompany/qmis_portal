@extends('layouts.admin')

@section('content')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h2>View SRD</h2>
        </div>
        <div class="col-sm-6">
            <a class="btn btn-warning float-right" href="{{ route('admin.srd.edit', $srd->id) }}">
                Edit SRD
            </a>
        </div>
    </div>
    <div class="card card-primary card-outline">
        <div class="card-body">
            <div class="form-group">
                <label for="srd">SRD</label>
                <p>{{ $srd->srd }}</p>
            </div>
            <div class="form-group">
                <label for="project">Project</label>
                <p>{{ $srd->project->name }}</p>
            </div>
            <div class="form-group">
                <label for="campaign">Campaign</label>
                <p>{{ $srd->campaign }}</p>
            </div>
            <div class="form-group">
                <label for="source">Source</label>
                <p>{{ $srd->source }}</p>
            </div>
            <a class="btn btn-primary" href="{{ route('admin.srd.index') }}">Back to List</a>
        </div>
    </div>
@endsection
