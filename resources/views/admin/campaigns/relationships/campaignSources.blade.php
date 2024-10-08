<div class="m-3">
    <div class="card">
        @if(auth()->user()->is_superadmin)
            <div class="card-header">
                <a class="btn btn-success float-right" href="{{ route('admin.sources.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.source.title_singular') }}
                </a>
            </div>
        @endif
        <div class="card-body">
            <div class="table-responsive">
                <table class=" table table-bordered table-striped table-hover datatable datatable-campaignSources">
                    <thead>
                        <tr>
                            <th width="10">

                            </th>
                            <th>
                                {{ trans('cruds.source.fields.id') }}
                            </th>
                            <th>
                                {{ trans('cruds.source.fields.project') }}
                            </th>
                            <th>
                                {{ trans('cruds.source.fields.campaign') }}
                            </th>
                            <th>
                                {{ trans('cruds.source.fields.name') }}
                            </th>
                            <th>
                                &nbsp;
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sources as $key => $source)
                            <tr data-entry-id="{{ $source->id }}">
                                <td>

                                </td>
                                <td>
                                    {{ $source->id ?? '' }}
                                </td>
                                <td>
                                    {{ $source->project->name ?? '' }}
                                </td>
                                <td>
                                    {{ $source->campaign->name ?? '' }}
                                </td>
                                <td>
                                    {{ $source->name ?? '' }}
                                </td>
                                <td>

                                        <a class="btn btn-xs btn-primary" href="{{ route('admin.sources.show', $source->id) }}">
                                            {{ trans('global.view') }}
                                        </a>
                                        @if(auth()->user()->is_superadmin )
                                        <a class="btn btn-xs btn-info" href="{{ route('admin.sources.edit', $source->id) }}">
                                            {{ trans('global.edit') }}
                                        </a>
                                        <a class="btn btn-xs btn-dark" href="{{ route('admin.sources.webhook', $source->id) }}">
                                            {{ trans('messages.webhook') }}
                                        </a>
                                        <form action="{{ route('admin.sources.destroy', $source->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@if(auth()->user()->is_superadmin)
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.sources.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endif

  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  let table = $('.datatable-campaignSources:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

})

</script>
@endsection
