<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lead Search and Create Form</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .form-container {
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
            border-radius: 10px;
            background-color: #ffffff;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        .lead-details-card {
            background-color: #ffffff;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-group label {
            font-weight: bold;
        }

        .form-container h2,
        .lead-details-card h4 {
            font-size: 1.25rem;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        #lead-details-card p {
            font-size: 14px;
        }

        .btn-block {
            width: 100%;
        }

        .submit-btn {
            background-color: #007bff;
            color: white;
            border: none;
            font-size: 18px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .submit-btn img {
            margin-left: 8px;
            height: 20px;
        }

        .submit-btn:hover {
            background-color: #0056b3;
        }

        /* Mobile Styles */
        @media (max-width: 576px) {
            .form-container {
                padding: 10px;
            }

            .lead-details-card {
                padding: 10px;
            }

            .form-group {
                margin-bottom: 10px;
            }

            h2,
            h4 {
                font-size: 1.2rem;
            }

            .btn-lg {
                font-size: 16px;
                padding: 10px;
            }
        }
    </style>
</head>

<body>

    <div class="form-container">
        <!-- Heading -->
        <div class="row mb-2">
            <div class="col-sm-12">
                <h2 class="text-center">{{ trans('global.create') }} {{ trans('cruds.lead.title_singular') }}</h2>
            </div>
        </div>
        <!-- Phone Search Form -->
        <form id="phoneSearchForm">
            <div class="form-group">
                <input type="text" id="phone-input" placeholder="Primary Phone" class="form-control form-control-lg">
            </div>
            <div class="form-group">
                <input type="text" id="second-phone-input" placeholder="Secondary Phone"
                    class="form-control form-control-lg">
            </div>
            <!-- Hidden input for lead_id -->
            <input type="hidden" id="lead_id" value="">
            <button type="submit" class="btn btn-primary btn-block btn-lg">Search</button>
        </form>


        <div id="disclaimer" class="text-danger text-center" style="display: none;"></div>

        <!-- Lead Details Card -->
        <div id="lead-details-card" class="lead-details-card mt-3" style="display: none;">
            <h4 class="text-center">Lead Details</h4>
            <div class="row">
                <div class="col-6">
                    <p><strong>Ref:</strong>
                        <a id="ref_num_link" href="#">
                            <span id="ref_num"></span>
                        </a>
                    </p>
                </div>
                <div class="col-6">
                    <p><strong>Father:</strong> <span id="father-name"></span></p>
                    <p><strong>Phone:</strong> <span id="father-phone"></span></p>
                    <p><strong>Email:</strong> <span id="father-email"></span></p>
                </div>
                <div class="col-6">
                    <p><strong>Mother:</strong> <span id="mother-name"></span></p>
                    <p><strong>Phone:</strong> <span id="mother-phone"></span></p>
                    <p><strong>Email:</strong> <span id="mother-email"></span></p>
                </div>
                <div class="col-6">
                    <p><strong>Guardian:</strong> <span id="guardian-name"></span></p>
                    <p><strong>Phone:</strong> <span id="guardian-phone"></span></p>
                    <p><strong>Email:</strong> <span id="guardian-email"></span></p>
                </div>
                <div class="col-6">
                    {{-- <p><strong>Stage:</strong> <span id="stage"></span></p> --}}

                </div>
            </div>
        </div>

        <!-- OTP Container -->
        <div id="otp-container" class="mt-3" style="display: none;">
            <h4 class="text-center">Enter OTP</h4>
            <form id="otpForm">
                <div class="form-group">
                    <input type="text" id="otp-input" placeholder="Enter OTP" class="form-control form-control-lg">
                </div>
                <!-- Hidden input for lead_id -->
                <input type="hidden" id="lead_id" value="">
                <div id="otp-error" class="text-danger text-center" style="display: none;"></div>
                <button type="submit" class="btn btn-primary btn-block btn-lg">Verify OTP</button>
            </form>
        </div>


        <!-- New Lead Form -->


        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
        <script>
        $(document).ready(function() {
    // Handle phone search form submission
    $('#phoneSearchForm').on('submit', function(e) {
        e.preventDefault();

        var primaryPhone = $('#phone-input').val();
        var secondaryPhone = $('#second-phone-input').val();

        $.ajax({
            url: '{{ route('admin.search-lead') }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                primary_phone: primaryPhone,
                secondary_phone: secondaryPhone
            },
            success: function(response) {
                if (response.status === 'found') {
                    var lead = response.lead;

                    // Update lead details on the page
                    $('#ref_num').text(lead.ref_num || '');
                    $('#ref_num_link').attr('href', '/admin/leads/' + lead.id);
                    $('#father-name').text(lead.father_details.name || '');
                    $('#father-phone').text(lead.father_details.phone || '');
                    $('#father-email').text(lead.father_details.email || '');
                    $('#mother-name').text(lead.mother_details.name || '');
                    $('#mother-phone').text(lead.mother_details.phone || '');
                    $('#mother-email').text(lead.mother_details.email || '');
                    $('#guardian-name').text(lead.guardian_details.name || '');
                    $('#guardian-phone').text(lead.guardian_details.phone || '');
                    $('#guardian-email').text(lead.guardian_details.email || '');
                  //  $('#stage').text(lead.parent_stage_id || '');
                    $('#lead-details-card').show();
                    $('#otp-container').hide();
                    $('#form-container').hide();
                    $('#lead_id').val(lead.id);

                } else if (response.show_otp_container) {
                    $('#lead-details-card').hide();
                    $('#otp-container').show();
                    $('#form-container').hide();
                    $('#disclaimer').text(response.message).show();
                    $('#lead_id').val(response.lead_id);

                } else {
                    $('#lead-details-card').hide();
                    $('#otp-container').hide();
                    $('#form-container').show();
                    $('#disclaimer').text(response.message).show();
                }
            }
        });
    });

    // Handle OTP form submission
    $('#otpForm').on('submit', function(e) {
        e.preventDefault();

        var otp = $('#otp-input').val();
        var leadId = $('#lead_id').val();

        $.ajax({
            url: '{{ route('admin.validate-otp') }}',
            method: 'POST',
            data: {
                otp: otp,
                lead_id: leadId,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.status === 'valid') {
                    window.location.href = '{{ url('admin/update-form') }}/' + leadId;
                } else {
                    $('#otp-error').text(response.message).show();
                }
            },
            error: function(xhr) {
                $('#otp-error').text('Something went wrong. Please try again.').show();
            }
        });
    });
});

        </script>


</body>

</html>
