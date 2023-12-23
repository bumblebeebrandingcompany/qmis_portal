@extends('layouts.admin')
@section('styles')
<style>
    .list-group-item {
        border-bottom: 1px solid rgba(0,0,0,.125) !important;
        border-width: 1px;
        z-index: 1 !important;
        border-top-width: 1px;
    }
    .list-group-item+.list-group-item {
        border-top-width: 1px;
    }
</style>
@endsection
@section('content')
    <div class="row mb-2">
        <div class="col-sm-12 d-flex align-items-center justify-content-between">
            <h2>
                Lead Profile  <small>{{ $lead->name ? ' - ' .$lead->name : '' }}</small>
            </h2>
            <a class="btn btn-default float-right" href="{{ route('admin.leads.index') }}" id="go_back_to_list_btn">
                <i class="fas fa-chevron-left"></i>
                {{ trans('global.back_to_list') }}
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            @includeIf('admin.leads.partials.user_details')
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills" id="lead-tab" role="tablist">
                        <!-- <li class="nav-item">
                            <a class="nav-link active" id="lead-details-tab" data-toggle="tab" href="#lead-details" role="tab" aria-controls="lead-details" aria-selected="true">
                                <i class="fas fa-th-list"></i>
                                @lang('messages.details')
                            </a>
                        </li> -->
                        <li class="nav-item">
                            <a class="nav-link active" id="lead-timeline-tab" data-toggle="tab" href="#lead-timeline" role="tab" aria-controls="lead-timeline" aria-selected="false">
                                <i class="fas fa-history"></i>
                                @lang('messages.profile')
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="lead-documents-tab" data-toggle="tab" href="#lead-documents" role="tab" aria-controls="lead-documents" aria-selected="false">
                                <i class="fas fa-question-circle"></i>
                                @lang('messages.documents')
                            </a>
                        </li>


                        <li class="nav-item">
                            <a class="nav-link" id="lead-webhook_response-tab" data-toggle="tab" href="#lead-webhook_response" role="tab" aria-controls="lead-webhook_response" aria-selected="false">
                                <i class="fas fa-reply"></i>
                                @lang('messages.webhook_response')
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="lead-followup-tab" data-toggle="tab" href="#lead-followup"
                                role="tab" aria-controls="lead-leadfollowup" aria-selected="false">
                                <i class="far fa-newspaper"></i>
                                Followup
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="lead-sitevisit-tab" data-toggle="tab" href="#lead-sitevisit"
                                role="tab" aria-controls="lead-sitevisit" aria-selected="false">
                                <i class="fas fa-map-marker"></i>
                               Site Visit
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="lead-call_logs-tab" data-toggle="tab" href="#lead-call_logs"
                                role="tab" aria-controls="lead-call_logs" aria-selected="false">
                                <i class="fas fa-phone fa-flip-horizontal"></i>
                                @lang('messages.call_logs')
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="lead-notes-tab" data-toggle="tab" href="#lead-notes" role="tab"
                                aria-controls="lead-notes" aria-selected="false">
                                <i class="fas fa-sticky-note"></i> <!-- Change the class to your "notes" icon class -->
                               Notes
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="lead-tabContent">
                        <!-- <div class="tab-pane fade active show" id="lead-details" role="tabpanel" aria-labelledby="lead-details-tab">
                            @includeIf('admin.leads.partials.details')
                        </div> -->
                        <div class="tab-pane fade active show" id="lead-timeline" role="tabpanel" aria-labelledby="lead-timeline-tab">
                            @includeIf('admin.leads.partials.timeline')
                        </div>
                        <div class="tab-pane fade" id="lead-documents" role="tabpanel" aria-labelledby="lead-documents-tab">
                            @includeIf('admin.leads.partials.documents')
                        </div>
                        <div class="tab-pane fade" id="lead-webhook_response" role="tabpanel" aria-labelledby="lead-webhook_response-tab">
                            @includeIf('admin.leads.partials.webhook_responses')
                        </div>
                        <div class="tab-pane fade" id="lead-sitevisit" role="tabpanel" aria-labelledby="lead-call_logs-tab">
                            @includeIf('admin.leads.partials.sitevisit')
                        </div>
                        <div class="tab-pane fade" id="lead-call_logs" role="tabpanel" aria-labelledby="lead-call_logs-tab">
                            @includeIf('admin.leads.partials.call_log')
                        </div>
                        <div class="tab-pane fade" id="lead-notes" role="tabpanel" aria-labelledby="lead-notes-tab">
                            @includeIf('admin.leads.partials.notes')
                        </div>
                        <div class="tab-pane fade" id="lead-followup" role="tabpanel"
                            aria-labelledby="lead-followup-tab">
                            @includeIf('admin.leads.partials.followup')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
@parent


<script>
    $(function () {
        $(document).on('click', '.send_doc_to_lead', function() {
            const url = $(this).attr('data-href');
            const btn = $(this);
            btn.attr('disabled', true);
            const notearea = $(this).closest('div').find('textarea');
            $.ajax({
                method:"GET",
                url: url,
                data:{
                    note: notearea.val()
                },
                dataType: "json",
                success: function(response) {
                    btn.attr('disabled', false);
                    notearea.val('');
                    if(response.success) {
                        alert(response.msg);
                    } else{
                        alert(response.msg);
                    }
                }
            });
        });

        //if came from kanban view, set last url
        let leadListUrl = sessionStorage.getItem('leadListUrl');
        const urlParams = leadListUrl? new URLSearchParams(leadListUrl) : '';
        if(urlParams && urlParams.has('view') && (urlParams.get('view') == 'kanban')) {
            $("#go_back_to_list_btn").attr('href', leadListUrl);
            $("#lead_menu_link").attr('href', leadListUrl);
        }

        function getDocuments() {
            $.ajax({
                method:"GET",
                url: "{{route('admin.documents.filtered')}}",
                data:{
                    project_id : $("#project_filter").val(),
                    lead_id : "{{$lead->id}}"
                },
                dataType: "html",
                success: function(response) {
                    $("#document_accordion").html(response);
                    //initialize fuzzy search on accordion
                    setTimeout(() => {
                        const faqList = new List('document_list', {
                            valueNames: ['faq_question', 'faq_answer']
                        });

                        $('#doc_search_input').on('keyup', function() {
                            const search_term = $(this).val();
                            faqList.search(search_term);
                        });
                    }, 100)
                }
            });
        }

        $(document).on('change', '#project_filter', function() {
            getDocuments();
        });

        getDocuments();
    });
</script>
@endsection
