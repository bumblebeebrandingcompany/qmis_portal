@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2>Create Url</h2>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.urls.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="project_id">Project</label>
                        <select name="project_id" id="project_id" class="form-control">
                            <option value="">Select Project</option>
                            @foreach ($projects as $project)
                                <option value="{{ $project['project_id'] }}">{{ $project['project'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="campaign">Campaign</label>
                        <select name="campaign_name" id="campaign" class="form-control">
                            <option value="">Select Campaign</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="source">Source</label>
                        <select name="source_name" id="source" class="form-control">
                            <option value="">Select Source</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="sub_source_name">Sub Source</label>
                        <input type="text" name="sub_source_name" id="sub_source_name" class="form-control"
                            placeholder="Enter Sub Source">
                    </div>
                    <button type="submit" class="btn btn-primary">Create</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#project_id').change(function() {
                let projectId = $(this).val();
                let campaign = $('#campaign').val(); // Get selected campaign value
                console.log('Selected Project ID:', projectId); // Log selected project ID

                if (projectId) {
                    $.ajax({
                        url: '/admin/urls/get-campaigns-sources/' + projectId,
                        type: 'GET',
                        data: {
                            campaign: campaign
                        }, // Pass the selected campaign
                        success: function(data) {
                            console.log('AJAX Success:', data); // Log AJAX response data

                            $('#campaign').empty();
                            $('#campaign').append('<option value="">Select Campaign</option>');
                            $('#source').empty();
                            $('#source').append('<option value="">Select Source</option>');

                            $.each(data.campaigns, function(key, value) {
                                $('#campaign').append('<option value="' + key + '">' +
                                    value + '</option>');
                            });

                            $.each(data.sources, function(key, value) {
                                $('#source').append('<option value="' + key + '">' +
                                    value + '</option>');
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX Error:', status,
                                error); // Log AJAX error details
                        }
                    });
                } else {
                    $('#campaign').empty();
                    $('#campaign').append('<option value="">Select Campaign</option>');
                    $('#source').empty();
                    $('#source').append('<option value="">Select Source</option>');
                }
            });

            $('#campaign').change(function() {
                let projectId = $('#project_id').val();
                let campaign = $(this).val(); // Get selected campaign value
                console.log('Selected Campaign:', campaign); // Log selected campaign value

                if (projectId) {
                    $.ajax({
                        url: '/admin/urls/get-campaigns-sources/' + projectId,
                        type: 'GET',
                        data: {
                            campaign: campaign
                        }, // Pass the selected campaign
                        success: function(data) {
                            console.log('AJAX Success:', data); // Log AJAX response data

                            $('#source').empty();
                            $('#source').append('<option value="">Select Source</option>');

                            $.each(data.sources, function(key, value) {
                                $('#source').append('<option value="' + key + '">' +
                                    value + '</option>');
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX Error:', status,
                                error); // Log AJAX error details
                        }
                    });
                } else {
                    $('#source').empty();
                    $('#source').append('<option value="">Select Source</option>');
                }
            });
        });
    </script>
@endsection
