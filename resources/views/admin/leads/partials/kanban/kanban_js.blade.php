$(document).on('click', '#filter_leads', function() {
let url = generateUrl("{{ route('admin.leads.index') }}", 'kanban');
sessionStorage.setItem("leadListUrl", url);
window.location = url;
});

$(document).on('click', '#send_bulk_outgoing_webhook', function() {
let selected_ids = [];

$(".lead_ids").each(function(){
if($(this).is(":checked")) {
selected_ids.push($(this).val());
}
});

if (selected_ids.length === 0) {
alert('{{ trans('messages.no_leads_selected') }}')
return
}

sendOutgoingWebhooks(selected_ids);
});

$(document).ready(function() {
$('.additional-details-container').hide();

$('.toggle-details-btn').click(function() {
var leadId = $(this).data('target');
var detailsContainer = $('#additional-details-' + leadId);

// Toggle the visibility of the details container
detailsContainer.toggle();

// Toggle the visibility of the plus and minus icons
$(this).find('.fa-plus').toggle();
$(this).find('.fa-minus').toggle();
});
});
