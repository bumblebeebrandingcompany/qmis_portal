@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h1>Create Walkin</h1>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.walkinform.store') }}" method="post">
                        @csrf

                        <div class="form-group" class="required">
                            <label for="name">Name:</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <div class="form-group" class="required">
                            <label for="phone">Phone:</label>
                            <input type="text" name="phone" id="phone"
                                value="{{ old('phone') ? old('phone') : $phone ?? '' }}" class="form-control input_number"
                                @if (!auth()->user()->is_superadmin) required @endif>
                        </div>
                        <div class="form-group">
                            <label for="secondary_phone">Secondary Phone:</label>
                            <input type="text" name="secondary_phone" class="form-control">
                        </div>
                        <div class="form-group" class="required">
                            <label for="email">Email:</label>
                            <input type="text" name="email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="additional_email">Additional Email:</label>
                            <input type="text" name="additional_email" class="form-control">
                        </div>

                        <div class="form-group">
                            <label class="required" for="project_id">Referred By</label>
                            <br>
                            <select class="form-control select2 {{ $errors->has('project') ? 'is-invalid' : '' }}"
                                name="referred_by" id="leadSource" required>
                                <option value="Direct Walk-in">Direct Walk-in</option>
                                <option value="Parent">Parent</option>
                                <option value="Teachers">Teachers</option>
                                <option value="Management">Management</option>
                            </select>
                        </div>
                        <label class="required" for="project_id">Representative name</label>
                        <br>
                        <select class="form-control select2 {{ $errors->has('client') ? 'is-invalid' : '' }}" name="user_id"
                            id="user_id" required>
                            @foreach ($client as $id => $clients)
                                @foreach ($clients->clientUsers as $user)
                                    @if ($user->user_type == 'Admissionteam')
                                        <option value="{{ $user->id }}"
                                            {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->representative_name }}
                                        </option>
                                    @endif
                                @endforeach
                            @endforeach
                        </select>
                        <div class="form-group">
                            <label for="channelPartner" class="required">Remarks:</label>

                            <input type="text" name="cp_comments" class="form-control" value="" required>
                        </div>
                        <input type="hidden" name="comments" class="form-control" value= "Direct Walk-in attended"
                            required>
                            @if(!(auth()->user()->is_agency || auth()->user()->is_channel_partner || auth()->user()->is_channel_partner_manager))
                            {{-- <div class="col-md-12 sources_div">
                                <label for="source_id">
                                    Source
                                </label>
                                <select class="search form-control" name="source" id="source_id">

                                    @foreach($sources as $source)
                                        <option value="{{$source->id}}" @if(isset($filters['source']) && $filters['source'] == $item->id) selected @endif>{{ $source->name }}</option>
                                    @endforeach
                                </select>
                            </div> --}}
                            <div class="col-md-12 campaigns_div">
                                <label for="campaign_id">
                                    @lang('messages.campaigns')
                                </label>
                                <select class="search form-control" id="campaign_id" >
                                    @foreach($campaigns as $key => $item)
                                        <option value="{{ $item->id }}" @if(isset($filters['campaign_id']) && $filters['campaign_id'] == $item->id) selected @endif>{{ $item->campaign_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <br>
                        @endif
                        <button type="submit" class="btn btn-success">Create Walkin</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
