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
                    <td>
                        @if ($stage->payment_created == 1)
                            Payment Created
                        @else
                            Not Created
                        @endif
                    </td>
                    
                    <td>
                        @if ($stage->payment_verified == 1)
                            Payment Verified
                        @else
                            Not Verified
                        @endif
                    </td>
                    <td>{{ $stage->data }}</td>
                    <td>{{ $stage->page }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
