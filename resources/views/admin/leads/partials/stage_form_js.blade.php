<script>
    $(document).ready(function() {
        $('.edit-button').on('click', function() {
            // Show the edit fields and set border color to red
            $('.edit-field').show().css('border-color', 'red');
            $('.display-value').hide();
            $('.save-button').show();
            $('.edit-field').each(function() {
                var fieldName = $(this).attr('name');
                var displayValue = $(this).siblings('.display-value').text();
                $(this).val(displayValue);
            });
            // Clear any previous error messages
            $('.error-message').text('');
        });
        $('.save-button').on('click', function() {
            // Create an object to store the updated values
            var updatedValues = {};
            var isValid = true;
            // Update the display values with the entered values and store in the object
            $('.display-value').each(function() {
                var fieldName = $(this).attr('name');
                var updatedValue = $(this).siblings('.edit-field').val();

                // Perform validation for each field
                switch (fieldName) {
                    case 'secondary_email':
                        if (!isValidEmail(updatedValue)) {
                            isValid = false;
                            $('.error-message').text('Invalid email address.');
                        }
                        break;
                    case 'secondary_phone':
                        if (!isValidPhoneNumber(updatedValue)) {
                            isValid = false;
                            $('.error-message').text(
                                'Invalid phone number (10 digits required).');
                        }
                        break;
                    case 'intake_year':
                        // Add validation logic if needed
                        break;
                    case 'grade_enquired':
                        // Add validation logic if needed
                        break;

                    case 'previous_school':
                    case 'previous_school':
                        // Add validation logic if needed
                        break;
                        // Add more cases for other fields as needed
                }

                updatedValues[fieldName] = updatedValue;
            });

            // Check for empty fields
            if (updatedValue.trim() === '') {
                $(this).siblings('.edit-field').css('border-color', 'red');
            } else {
                $(this).siblings('.edit-field').css('border-color', 'blue');
            }

            // Add the _method field for Laravel to recognize it as a PUT request
            updatedValues['_method'] = 'PUT';

            // Add CSRF token
            updatedValues['_token'] = '{{ csrf_token() }}';

            // Check if all fields are valid before making the AJAX request
            if (isValid) {
                $.ajax({
                    method: 'POST',
                    url: '{{ route('admin.leads.update', [$lead->id]) }}',
                    data: updatedValues,
                    success: function(response) {
                        // Handle success if needed
                        console.log(response);
                        // Hide the text fields and show the display values
                        $('.edit-field').hide();
                        $('.display-value').show();
                    },
                    error: function(error) {
                        // Log the error response
                        console.error(error.responseJSON);
                        // Show the text fields and hide the display values
                        $('.edit-field').show();
                        $('.display-value').hide();
                    }
                });
            }
        });

    });
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
function checkParentStageId(selectElement) {
    var selectedOption = selectElement.options[selectElement.selectedIndex];
    var selectedName = selectedOption.text.trim().toLowerCase();

    // Hide all sections initially
    var formSections = [
        'showenquiryfollowup', 'showrescheduled', 'showsitevisitScheduled', 'showadmissionfollowup','showqualifiedfollowup','showinterviewfollowup',
        'notvisitedContent', 'spamContent', 'notqualifiedContent', 'lostContent',
        'futureprospectContent', 'cancelledContent', 'rnrContent', 'sitevisitconductedContent',
        'applicationpurchasedContent', 'AdmissionContent', 'applicationacceptedContent',
        'applicationnotpurchasedContent', 'admissionwithdrawnContent'
    ];

    formSections.forEach(function(sectionId) {
        var section = document.getElementById(sectionId);
        if (section) {  // Check if the section exists before modifying its style
            section.style.display = 'none';
        }
    });

    // Match the option to the form section to show
    switch (selectedName) {
        case 'enquiry followup':
            document.getElementById('showenquiryfollowup').style.display = 'block';
            break;
        case 'interview scheduled':
            document.getElementById('showsitevisitScheduled').style.display = 'block';
            break;
        case 'admission followup':
            document.getElementById('showadmissionfollowup').style.display = 'block';
            break;
        case 'rescheduled':
            document.getElementById('showrescheduled').style.display = 'block';
            break;

            case 'qualified followup':
            document.getElementById('showqualifiedfollowup').style.display = 'block';
            break;
            case 'interview follow-up':
            document.getElementById('showinterviewfollowup').style.display = 'block';
            break;
        case 'site not visited':
            document.getElementById('notvisitedContent').style.display = 'block';
            break;
        case 'spam':
            document.getElementById('spamContent').style.display = 'block';
            break;
        case 'not qualified':
            document.getElementById('notqualifiedContent').style.display = 'block';
            break;
        case 'lost':
            document.getElementById('lostContent').style.display = 'block';
            break;
        case 'future prospect':
            document.getElementById('futureprospectContent').style.display = 'block';
            break;
        case 'cancelled':
            document.getElementById('cancelledContent').style.display = 'block';
            break;
        case 'rnr':
            document.getElementById('rnrContent').style.display = 'block';
            break;
        case 'interview conducted':
            document.getElementById('sitevisitconductedContent').style.display = 'block';
            break;
        case 'application purchased':
            document.getElementById('applicationpurchasedContent').style.display = 'block';
            break;
        case 'admitted':
            document.getElementById('AdmissionContent').style.display = 'block';
            break;
        case 'application accepted':
            document.getElementById('applicationacceptedContent').style.display = 'block';
            break;
        case 'application not purchased':
            document.getElementById('applicationnotpurchasedContent').style.display = 'block';
            break;
        case 'admission withdrawn':
            document.getElementById('admissionwithdrawnContent').style.display = 'block';
            break;
        default:
            console.log('No matching section to show.');
            break;
    }
}

// Run this function when the page loads to check the current selected option
document.addEventListener('DOMContentLoaded', function() {
    checkParentStageId(document.getElementById('parent_stage_id'));
});



$(document).ready(function() {
    $('#tag_id').change(function() {
        var selectedTagId = $('#tag_id option:selected').val();
        var childStages = {!! json_encode($lead->parentStage->childStages ?? null) !!};
        var selectedChildStages = [];

        if (childStages && childStages.length > 0 && childStages[0].selected_child_stages) {
            selectedChildStages = JSON.parse(childStages[0].selected_child_stages);
        }

        $('#child_stage_id').html('<option value="" selected disabled>Please Select a Tag First</option>');
        @php
    $excludedStages = [];
    if (auth()->user()->is_presales) {
        $excludedStages = ['Interview Scheduled','Rescheduled','Admitted','admission followup','Interview Conducted'];
    }
@endphp


if (selectedTagId && selectedChildStages) {
    @foreach ($parentStages as $stage)
        if ("{{ $stage->tag_id }}" == selectedTagId && selectedChildStages.includes("{{ $stage->id }}")) {
            // Check for 'qualified' stage
            if ("{{ strtolower($stage->name) }}" !== 'qualified') {
                // Exclude stages based on user type
                if (!excludedStages.includes("{{ $stage->name }}")) {
                    $('#child_stage_id').append(
                        '<option value="{{ $stage->id }}" data-tag="{{ $stage->tag_id }}">{{ $stage->name }}</option>'
                    );
                }
            }
        }
    @endforeach
}

    });
});

</script>
<script>
    const excludedStages = @json($excludedStages);
</script>
