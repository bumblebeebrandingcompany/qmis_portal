@extends('layouts.admin')
@section('content')
    <h1>Lead Follow Up List</h1>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Lead Follow-Up Table</h3>
        </div>
        <div class="container-fluid h-100 mt-3">
            <div class="card-body">
                <div class="card-body table-responsive p-0" style="height: 600px;">
                    {{-- <input type="text" id="searchInput" class="form-control" placeholder="Search in table..."> --}}
                    <br>
                    <table class="table table-bordered table-striped table-hover ajaxTable datatable datatable-followup">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Reference Number</th>
                                <th>Student Name</th>
                                <th>Father Name</th>
                                <th>Mother Name</th>
                                <th>Follow-Up Date</th>
                                <th>Follow-Up Time</th>
                                <th>Notes</th>
                                <th>Stage</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody class="followupTable">
                            @php
                                $counter = 1;
                            @endphp
                            @foreach ($followUps->where('parent_stage_id', 10||32||33) as $followUp)
                                @foreach ($lead as $leads)
                                    @if ($leads->id === $followUp->lead_id && $leads->parent_stage_id == $followUp->parent_stage_id)
                                        <tr data-created-at="{{ $followUp->followup_date }}">
                                            <td>{{ $counter++ }}</td>
                                            <td>
                                                @foreach ($lead as $leads)
                                                    @if ($leads->id === $followUp->lead_id)
                                                        <a href="{{ route('admin.leads.show', ['lead' => $leads->id]) }}">
                                                            {{ $leads->ref_num }}
                                                        </a>
                                                    @endif
                                                @endforeach
                                            </td>

                                    <td>
                                        @if (is_array($followUp->lead->student_details) && count($followUp->lead->student_details) > 0)
                                            <ul>
                                                @foreach ($followUp->lead->student_details as $student)
                                                {{ $student['name'] ?? '' }}
                                                @endforeach
                                            </ul>
                                        @else

                                        @endif
                                    </td>
                                            <td>
                                                @foreach ($lead as $leads)
                                                    @if ($leads->id === $followUp->lead_id)
                                                        {{ $leads->father_details['name'] ?? '' }}
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($lead as $leads)
                                                    @if ($leads->id === $followUp->lead_id)
                                                        {{ $leads->mother_details['name'] ?? '' }}
                                                    @endif
                                                @endforeach
                                            </td>

                                            <td>
                                                {{ $followUp->followup_date }}
                                            </td>
                                            <td>
                                                {{ $followUp->followup_time }}
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                                    data-target="#notesModal{{ $followUp->id }}">
                                                    View Notes
                                                </button>
                                                <div class="modal fade" id="notesModal{{ $followUp->id }}" tabindex="-1"
                                                    role="dialog" aria-labelledby="notesModalLabel{{ $followUp->id }}"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"
                                                                    id="notesModalLabel{{ $followUp->id }}">
                                                                    Notes</h5>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <textarea id="notesTextArea{{ $followUp->id }}" class="form-control" rows="5" readonly>{{ $followUp->notes }}</textarea>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </td>
                                            <td>{{$parentstages[$followUp->parent_stage_id]}}</td>
                                            <td>
                                                {{ $followUp->created_at }}
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    @endsection

    {{-- @section('scripts')
        @parent
        <script>
            $(function() {
                let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
                @if (auth()->user()->is_superadmin)
                    let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
                    let deleteButton = {
                        text: deleteButtonTrans,
                        url: "{{ route('admin.followups.massDestroy') }}",
                        className: 'btn-danger',
                        action: function(e, dt, node, config) {
                            var ids = $.map(dt.rows({
                                selected: true
                            }).data(), function(entry) {
                                return entry.id
                            });

                            if (ids.length === 0) {
                                alert('{{ trans('global.datatables.zero_selected') }}')

                                return
                            }
                            if (confirm('{{ trans('global.areYouSure') }}')) {
                                $.ajax({
                                        headers: {
                                            'x-csrf-token': _token
                                        },
                                        method: 'POST',
                                        url: config.url,
                                        data: {
                                            ids: ids,
                                            _method: 'DELETE'
                                        }
                                    })
                                    .done(function() {
                                        location.reload()
                                    })
                            }
                        }
                    }
                    dtButtons.push(deleteButton)
                @endif

                let dtOverrideGlobals = {
                    buttons: dtButtons,
                    processing: true,
                    serverSide: true,
                    retrieve: true,
                    aaSorting: [],
                    ajax: "{{ route('admin.followups.index') }}",
                    orderCellsTop: true,
                    order: [
                        [1, 'desc']
                    ],
                    pageLength: 100,
                };
                let table = $('.datatable-followups').DataTable(dtOverrideGlobals);
                $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                    $($.fn.dataTable.tables(true)).DataTable()
                        .columns.adjust();
                });
            });
        </script>
    <script>
        document.getElementById('select-all').onclick = function() {
            var checkboxes = document.getElementsByName('lead_ids[]');
            for (var checkbox of checkboxes) {
                checkbox.checked = this.checked;
            }
        }
        // Search Functionality
        document.getElementById('searchInput').addEventListener('keyup', function() {
            var input, filter, table, tr, td, i, j, txtValue;
            input = document.getElementById('searchInput');
            filter = input.value.toUpperCase();
            table = document.getElementById('leadTable');
            tr = table.getElementsByTagName('tr');
            for (i = 0; i < tr.length; i++) {
                tr[i].style.display = 'none';
                td = tr[i].getElementsByTagName('td');
                for (j = 0; j < td.length; j++) {
                    if (td[j]) {
                        if (td[j].innerText.toUpperCase().indexOf(filter) > -1) {
                            tr[i].style.display = '';
                            break;
                        }
                    }
                }
            }
        });
    </script>
    @endsection --}}
    @section('scripts')
        @parent
        <script>
            $(function() {
                let table = $('.datatable-followup').DataTable();
                table.on('draw.dt', function() {});
            });
        </script>
    @endsection

