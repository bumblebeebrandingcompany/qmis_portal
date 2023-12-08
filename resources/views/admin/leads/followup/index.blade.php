@extends('layouts.admin')

@section('content')
    <h1>
        Lead Follow Up List</h1>
    <div class="card">

        <div class="card-header">
            <h3 class="card-title">Lead Follow-Up Table</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="campaign_name">Select Campaign Name</label>
                        <select id="campaign_name" name="campaign_name" class="form-control">
                            @foreach ($campaigns as $key => $item)
                                @if ($item->campaign_name)
                                    <!-- Check if representative_name is not null or empty -->
                                    <option value="{{ $item->campaign_name }}">{{ $item->campaign_name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="staffMember">Select Staff Member</label>
                        <select id="staffMember" name="staffMember" class="form-control">
                            @foreach ($agencies as $key => $item)
                                @if ($item->representative_name)
                                    <!-- Check if representative_name is not null or empty -->
                                    @if (trim($item->representative_name) !== '')
                                        <!-- Check if representative_name is not empty after trimming -->
                                        <option value="{{ $item->representative_name }}">{{ $item->representative_name }}
                                        </option>
                                    @endif
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="start_date">{{ trans('cruds.project.fields.start_date') }}</label>
                        <input class="form-control date {{ $errors->has('start_date') ? 'is-invalid' : '' }}" type="text"
                            name="start_date" id="start_date" value="{{ old('start_date') }}">
                        @if ($errors->has('start_date'))
                            <span class="text-danger">{{ $errors->first('start_date') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.project.fields.start_date_helper') }}</span>
                    </div>
                </div>
            </div>

            <table class="table">
                <thead>
                    <tr>
                        <th>Reference Number</th>
                        <th>Campaign Name</th>
                        <th>Follow-Up Date</th>
                        <th>Follow-Up Time</th>
                        <th>Follow-Up By</th>
                        <th>notes</th>
                        {{-- <th>Action</th> --}}

                        <!-- Add more table headers for other lead follow-up properties -->
                    </tr>
                </thead>
                <tbody>
                    @foreach ($followUps as $followUp)
                        <tr>
                            <td>
                                @foreach ($lead as $leads)
                                    @if ($leads->id === $followUp->lead_id)
                                        {{ $leads->ref_num }}
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                @foreach ($lead as $leads)
                                    @if ($leads->id === $followUp->lead_id)
                                        {{ $leads->campaign->campaign_name }}
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                @foreach ($lead as $leads)
                                    @if ($leads->id === $followUp->lead_id)
                                        {{ $followUp->follow_up_date }}
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                @foreach ($lead as $leads)
                                    @if ($leads->id === $followUp->lead_id)
                                        {{ $followUp->follow_up_time }}
                                    @endif
                                @endforeach
                            </td>
                            {{-- <td>{{ $followUp->follow_up_date }}</td> --}}
                            {{-- <td>{{ $followUp->follow_up_time }}</td> --}}
                            <td>
                                {{ $followUp->users->representative_name }}
                            </td>
                            <td>
                                @foreach ($lead as $leads)
                                    @if ($leads->id === $followUp->lead_id)
                                        {{ $followUp->notes }}
                                    @endif
                                @endforeach
                            </td>
                            {{-- <td>
                                <form action="{{ route('admin.followups.destroy', $followUp->id) }}" method="POST"
                                    onsubmit="return confirmDelete();" style="display: inline-block;">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="submit" class="btn btn-xs btn-danger"
                                        value="{{ trans('global.delete') }}">
                                </form>
                            </td> --}}
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endsection
        @section('scripts')
            <script>
                function confirmDelete() {
                    return confirm('{{ trans('global.areYouSure') }}');
                }
            </script>
        @endsection
