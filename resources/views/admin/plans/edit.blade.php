<!-- resources/views/plans/edit.blade.php -->

@extends('layouts.admin')

@section('content')
    <div class="container">
        <h1>Edit Plan</h1>
        <form action="{{ route('admin.plans.update', $plan) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ $plan->name }}" required>
            </div>
            <div class="form-group">
                <label for="duration">Duration (months)</label>
                <input type="number" name="duration" id="duration" class="form-control" value="{{ $plan->duration }}" required>
            </div>
            <div class="form-group">
                <label for="price">Price</label>
                <input type="text" name="price" id="price" class="form-control" value="{{ $plan->price }}" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Plan</button>
        </form>
    </div>
@endsection
