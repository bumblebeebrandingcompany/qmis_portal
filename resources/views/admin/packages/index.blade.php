@extends('layouts.admin')

@section('content')
    <div class="container">
        <h1>Packages</h1>
        <a href="{{ route('admin.packages.create') }}" class="btn btn-primary">Create New Package</a>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <table class="table">
            <thead>
                <tr>
                    <th>Plan</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Offer</th>
                    <th>Transport Price</th>
                    <th>Admission Fees</th>
                    <th>Waiver Term 2</th>
                    <th>Waiver Term 3</th>
                    <th>Kid Gym Waiver</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($packages as $package)
                    <tr>
                        <td>{{ $package->plan->name }}</td>
                        <td>{{ $package->type }}</td>
                        <td>${{ ($package->amount) }}</td>
                        <td>{{ $package->offer }}</td>
                        <td>${{ ($package->transport_price) }}</td>
                        <td>${{ ($package->admission_fees) }}</td>
                        <td>${{ ($package->waiver_term2) }}</td>
                        <td>${{ ($package->waiver_term3) }}</td>
                        <td>${{ ($package->kid_gym_waiver) }}</td>
                        <td>
                            <a href="{{ route('admin.packages.edit', $package) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('admin.packages.destroy', $package) }}" method="POST" style="display:inline;">
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
