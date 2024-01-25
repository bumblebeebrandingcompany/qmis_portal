@extends('layouts.admin')

@section('content')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h2> Walkin {{ trans('global.list') }}</h2>
        </div>
    </div>

    <div class="card card-primary card-outline">

        <div class="card-header">
            {{-- @if (auth()->user()->checkPermission('client_create')) --}}
            <a class="btn btn-success float-right" href="{{ route('admin.walkinform.create') }}">
                {{ trans('global.add') }} Walkin
            </a>
            {{-- @endif --}}
        </div>
        <div class="card-body">
            <div class="table-responsive">

                <table class="table table-bordered table-striped table-hover ajaxTable datatable datatable-walkin">

                    <thead>
                        <tr>
                            <th width="10"></th>
                            <th>Ref_num</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Source</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($walkins as $walkin)
                            <tr>

                                <td width="10"></td>

                                @foreach ($walkin->leads as $lead)
                                    <td> {{ $lead->ref_num ?? '' }}</td>
                                    <td> {{ $walkin->name }}</td>
                                    <td> {{ $walkin->email }}</td>
                                    <td> {{ $walkin->phone }}</td>
                                    <td> {{ $walkin->sources->name ?? '' }}</td>
                                    <td>
                                        <div class="d-flex justify-content-between flex-nowrap">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <!-- Button to trigger the edit modal -->
                                                    <a href="{{ route('admin.walkinform.show', $walkin->id) }}"
                                                        class="btn btn-primary btn-sm">
                                                        View
                                                    </a>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <!-- Button to trigger the edit modal -->
                                                    <a href="{{ route('admin.walkinform.edit', $walkin->id) }}"
                                                        class="btn btn-info btn-sm">
                                                        Edit
                                                    </a>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <!-- Button to trigger the delete modal -->
                                                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                                        data-target="#deleteModal{{ $walkin->id }}">
                                                        Delete
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
    <script>
        $(function() {
            // Existing JavaScript code for DataTable initialization

            // Additional customization for DataTable
            let table = $('.datatable-walkin').DataTable();
            table.on('draw.dt', function() {
                // Add any additional customization after the table is drawn
            });
        });
    </script>
@endsection
