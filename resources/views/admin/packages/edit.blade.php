
@extends('layouts.admin')

@section('content')
    <div class="container">
        <h1>Edit Package</h1>
        <form action="{{ route('packages.update', $package) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="plan_id">Plan</label>
                <select name="plan_id" id="plan_id" class="form-control" required>
                    @foreach ($plans as $plan)
                        <option value="{{ $plan->id }}" {{ $plan->id == $package->plan_id ? 'selected' : '' }}>{{ $plan->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="type">Type</label>
                <input type="text" name="type" id="type" class="form-control" value="{{ $package->type }}" required>
            </div>
            <div class="form-group">
                <label for="amount">Amount</label>
                <input type="text" name="amount" id="amount" class="form-control" value="{{ $package->amount }}" required>
            </div>
            <div class="form-group">
                <label for="offer">Offer</label>
                <input type="text" name="offer" id="offer" class="form-control" value="{{ $package->offer }}">
            </div>
            <div class="form-group">
                <label for="transport_price">Transport Price</label>
                <input type="text" name="transport_price" id="transport_price" class="form-control" value="{{ $package->transport_price }}">
            </div>
            <div class="form-group">
                <label for="admission_fees">Admission Fees</label>
                <input type="text" name="admission_fees" id="admission_fees" class="form-control" value="{{ $package->admission_fees }}">
            </div>
            <div class="form-group">
                <label for="waiver_term2">Waiver Term 2</label>
                <input type="text" name="waiver_term2" id="waiver_term2" class="form-control" value="{{ $package->waiver_term2 }}">
            </div>
            <div class="form-group">
                <label for="waiver_term3">Waiver Term 3</label>
                <input type="text" name="waiver_term3" id="waiver_term3" class="form-control" value="{{ $package->waiver_term3 }}">
            </div>
            <div class="form-group">
                <label for="kid_gym_waiver">Kid Gym Waiver</label>
                <input type="text" name="kid_gym_waiver" id="kid_gym_waiver" class="form-control" value="{{ $package->kid_gym_waiver }}">
            </div>
            <button type="submit" class="btn btn-primary">Update Package</button>
        </form>
    </div>
@endsection
