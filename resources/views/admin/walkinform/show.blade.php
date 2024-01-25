@extends('layouts.admin')
@section('content')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h2>
               Walkin
            </h2>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <a class="btn btn-default float-right" href="{{ route('admin.walkinform.index') }}">
                        <i class="fas fa-chevron-left"></i>
                        {{ trans('global.back_to_list') }}
                    </a>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <table class="table table-bordered table-striped">
                            <tbody>
                                @foreach ($walkin->leads as $lead)
                                <tr>
                                        <th>
                                          Ref Num
                                        </th>

                                        <td> {{ $walkin->ref_num }}</td>
                                    </tr>
                                    <tr>
                                    <th>
                                        {{ trans('cruds.client.fields.name') }}
                                    </th>
                                    <td>
                                        {{ $walkin->name }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        Email
                                    </th>
                                    <td>
                                        {{ $walkin->email }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        Phone
                                    </th>
                                    <td class="word-break">
                                        {{ $walkin->phone }}
                                    </td>
                                </tr>
                               <th>
                                        Referred By
                                    </th>
                                    <td>
                                        {{ $walkin->referred_by }}
                                    </td>
                                </tr>
@endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

@endsection
