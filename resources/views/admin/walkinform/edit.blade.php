@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h1>Update Walkin</h1>
                </div>
                <div class="card-body">
                    {{-- <form action="{{ route('admin.walkinform.update', $walkin->id) }}" method="POST">
                        @csrf
                        @method('PUT') --}}

                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" name="name" class="form-control" value="{{$walkin->name}}" required>
                        </div>

                        <div class="form-group">
                            <label for="phone">Phone:</label>
                            <input type="text"  name="phone" class="form-control" value="{{$walkin->phone}}" required>
                        </div>
                        <div class="form-group">
                            <label for="secondary_phone">Secondary Phone:</label>
                            <input type="text"  name="secondary_phone" class="form-control" value="{{$walkin->secondary_phone}}" >
                        </div>

                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="text"  name="email" class="form-control"value="{{$walkin->email}}"  required>
                        </div>
                        <div class="form-group">
                            <label for="additional_email">Additional Email:</label>
                            <input type="text"  name="additional_email" class="form-control"value="{{$walkin->additional_email}}"  >
                        </div>
                        <div class="form-group">
                            <label for="city">City:</label>
                            <input type="text"  name="city" class="form-control" value="{{$walkin->city}}" required>
                        </div>
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
                        <br>
                        @foreach ($walkin->leads as $lead)
                        <div class="form-group">
                            <label for="channelPartner" class="required">Remarks:</label>
                            <input type="text"  name="cp_comments" class="form-control" value="{{$lead->cp_comments}}" required>
                        </div>
                        @endforeach
                        <input type="hidden" name="project_id" class="form-control" value= "1" required>
                        <input type="hidden" name="comments" class="form-control" value= "Direct Walk-in attended" required>

                        <button type="submit" class="btn btn-success">Update Walkin</button>
                    {{-- </form> --}}
                </div>
            </div>
        </div>
    </div>
@endsection

