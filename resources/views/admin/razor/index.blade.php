@extends('layouts.admin')

@section('content')
    <div class="container">
        <h2>Create Stage</h2>
        <div class="card card-primary card-outline">
            <div class="card-body">
                {{-- Form --}}

                {{-- Display all created stages in a table --}}
                <h3>All Created Stages</h3>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover datatable datatable-Project">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Enquiry ID</th>
                                <th>Error</th>
                                <th>Order created Status</th>
                                <th>verification created Status</th>
                                <th>Transferred Data</th>
                                <th>Page</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $counter = 1;
                            @endphp
                            @foreach ($logs as $stage)
                                <tr>
                                    <td>{{ $counter++ }}</td>
                                    <td>{{ 1000+$stage->lead_id }}</td>
                                    <td>{{ $stage->error }}</td>
                                    <td>{{ $stage->payment_created ? 'Success' : '-' }}</td>
                                    <td>{{ $stage->payment_verified ? 'Success' : ($stage->payment_created ? '-' : 'Failed') }}</td>
                                    <td>{{ $stage->data }}</td>
                                    <td>{{ $stage->page }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
