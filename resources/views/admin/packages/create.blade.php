
@extends('layouts.admin')

@section('content')
    <div class="container">
        <h1>Create New Package</h1>
        <form action="{{ route('admin.packages.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="plan_id">Plan</label>
                <select name="plan_id" id="plan_id" class="form-control" required>
                    @foreach ($plans as $plan)
                        <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="type">Type</label>
                <input type="text" name="type" id="type" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="class">Class</label>
                <input type="text" name="class" id="class" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="amount">Amount</label>
                <input type="text" name="amount" id="amount" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="offer">Offer</label>
                <input type="text" name="offer" id="offer" class="form-control">
            </div>
            <div class="form-group">
                <label for="transport_price">Transport Price</label>
                <input type="text" name="transport_price" id="transport_price" class="form-control">
            </div>
            <div class="form-group">
                <label for="admission_fees">Admission Fees</label>
                <input type="text" name="admission_fees" id="admission_fees" class="form-control">
            </div>
            <div class="form-group">
                <label for="waiver_term2">Waiver Term 2</label>
                <input type="text" name="waiver_term2" id="waiver_term2" class="form-control">
            </div>
            <div class="form-group">
                <label for="kid_gym_waiver">Kid Gym Waiver</label>
                <input type="text" name="kid_gym_waiver" id="kid_gym_waiver" class="form-control">
            </div>
            <div class="form-group">
                <label for="total_amount">Total amount</label>
                <input type="text" name="total_amount" id="total_amount" class="form-control">
            </div>
            <div class="form-group">
                <label for="benefits_availed">Benefits Availed</label>
                <input type="text" name="benefits_availed" id="benefits_availed" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Save Package</button>
        </form>
    </div>
@endsection
