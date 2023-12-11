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
            @if(!(auth()->user()->is_channel_partner || auth()->user()->is_channel_partner_manager))
                        <div class="col-md-3 campaigns_div">
                            <label for="campaign_id">
campaign                            </label>
                            <select class="search form-control" id="campaign_id">
                                <option value>{{ trans('global.all') }}</option>
                                @foreach($campaigns as $key => $item)
                                    <option value="{{ $item->id }}" @if(isset($filters['campaign_id']) && $filters['campaign_id'] == $item->id) selected @endif>{{ $item->campaign_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="user_id">Select Staff Member</label>
                        <select name="user_id" id="user_id" class="form-control{{ $errors->has('user_id') ? ' is-invalid' : '' }}"
                    rows="3" required>{{ old('user_id') }}
>
<option value>{{ trans('global.all') }}</option>

                        <!-- <option value="" selected disabled>Please Select</option> -->
                        @foreach ($agencies as $id => $agency)
                            @foreach ($agency->agencyUsers as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->representative_name }}
                                </option>
                            @endforeach
                        @endforeach
                    </select>
                    </div>
                </div>
            <div class="col-md-3">
                            <label for="added_on">{{ trans('messages.added_on') }}</label>
                            <input class="form-control date_range" type="text" name="date" id="added_on" readonly>
                        </div>
            <table class="table table-bordered table-striped table-hover ajaxTable datatable datatable-Followup">
                <thead>
                    <tr>
                        <th>{{trans('messages.ref_num')}}</th>
                        <th>{{trans('messages.campaign')}}</th>
                        <th>{{trans('messages.follow_up_date')}}</th>
                        <th>{{trans('messages.follow_up_time')}}</th>
                        <th>{{trans('messages.follow_up_by')}}</th>
                        <th>{{trans('messages.notes')}}</th>
                        <th>{{trans('messages.created_at')}}</th>

                        {{-- <th>{{trans('messages.action')}}</th> --}}
                        <!-- Add more table headers for other lead follow-up properties -->
                    </tr>
                </thead>
                <tbody id="followUpTableBody">
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
                            <td>
                                @foreach ($lead as $leads)
                                    @if ($leads->id === $followUp->lead_id)
                                        {{ $followUp->created_at }}
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
        @parent

        <script>

    $(function () {
       @includeIf('admin.leads.partials.follow_up_js')
    });
</script>

@endsection
