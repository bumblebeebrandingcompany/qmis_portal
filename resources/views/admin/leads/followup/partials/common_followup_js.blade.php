function generateUrl(url, view='') {
    let filters = {};

    filters.project_id = $("#project_id").val();
    filters.campaign_id = $("#campaign_id").val();
    filters.leads_status = $("#leads_status").val();
    filters.no_lead_id = $("#no_lead_id").is(":checked");

    if($("#source_id").length) {
        filters.source = $("#source_id").val();
    }

    if($(".date_range").length) {
        filters.start_date = $('#added_on').data('daterangepicker').startDate.format('YYYY-MM-DD');
        filters.end_date = $('#added_on').data('daterangepicker').endDate.format('YYYY-MM-DD');
    }

    if($("#additional_columns_to_export").length) {
        filters.additional_columns = $("#additional_columns_to_export").val();
    }

    if(view) {
        filters.view = view;
    }

    const query = Object.keys(filters)
                    .map(key =>`${encodeURIComponent(key)}=${encodeURIComponent(filters[key])}`)
                    .join('&');

    if (query){
        url += `?${query}`;
    }

    return url;
}
