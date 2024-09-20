@extends('layouts.admin')

@section('content')
    <div class="container">
        <h1>Plans</h1>
        <a href="{{ route('admin.plans.create') }}" class="btn btn-primary">Create New Plan</a>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Duration (months)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($plans as $plan)
                    <tr>
                        <td>{{ $plan->name }}</td>
                        <td>{{ $plan->duration }}</td>
                        <td>
                            <a href="{{ route('admin.plans.edit', $plan) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('admin.plans.destroy', $plan) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
