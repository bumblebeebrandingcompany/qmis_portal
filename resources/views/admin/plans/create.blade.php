<!-- resources/views/plans/create.blade.php -->

@extends('layouts.admin')

@section('content')
    <div class="container">
        <h1>Create New Plan</h1>
        <form action="{{ route('admin.plans.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="duration">Duration (months)</label>
                <input type="number" name="duration" id="duration" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Save Plan</button>
        </form>
    </div>
@endsection
