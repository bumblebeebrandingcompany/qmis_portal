@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm rounded">
        <div class="card-header justify-content-between align-items-center">
            <h4 class="mb-0">URLs</h4>
        </div>
        @if (auth()->user()->is_superadmin )
        <div class="col-sm-12">
            <a class="btn btn-success float-right" href="{{ route('admin.srd.create') }}">
                Add SRD
            </a>
            @endif
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover table-sm">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Project</th>
                            <th>Campaign Name</th>
                            <th>Source Name</th>
                            <th>Sub Source</th>
                        </tr>
                    </thead>
                    <tbody id="url-table-body">
                        @php
                        $counter = 1;
                        @endphp
                        @foreach ($srds as $srd)
                        <!-- @endphp -->
                        <tr>
                            <td>{{ $counter++ }}</td>
                            <td>{{ $srd->project->name ?? 'No Project Assigned' }}</td>
                            <td>{{ $srd->campaign_name }}</td>
                            <td>{{ $srd->source_name }}</td>
                            <td>{{ $srd->sub_source_name }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>

<style>
    .btn-group-spacing .btn {
        margin-right: 5px;
    }

    .btn-group-spacing .btn:last-child {
        margin-right: 0;
    }
</style>

<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(function () {
        $(document).on('click', '.duplicate-row', function () {
            var $row = $(this).closest('tr');
            var $newRow = $row.clone();

            // Generate a unique ID for the new row
            var uniqueId = new Date().getTime();

            // Update the names and IDs of the inputs in the new row
            $newRow.find('input').each(function () {
                var name = $(this).attr('name');
                if (name) {
                    // Replace any existing index with the unique ID
                    $(this).attr('name', name.replace(/\[\d*\]/g, '[' + uniqueId + ']'));
                }
                var id = $(this).attr('id');
                if (id) {
                    // Append the unique ID to the existing ID
                    $(this).attr('id', id + '-' + uniqueId);
                }
            });

            // Clear the values of sub-sources and sell_do_sub_source inputs
            $newRow.find('.sub-sources-container input[name^="sub_sources"]').val('');
            $newRow.find('.sub-sources-container input[name^="sell_do_sub_source"]').val('');

            // Reset the webhook secret column
            $newRow.find('td').eq(5).text(''); // Adjust column index for Webhook Secret

            // Reset the actions column to include the buttons
            $newRow.find('td').eq(6).html(`
                <button class="btn btn-warning btn-sm duplicate-row mt-2">Duplicate</button>
            `);

            // Insert the new row after the original row
            $row.after($newRow);
        });
    });
</script> -->
@endsection