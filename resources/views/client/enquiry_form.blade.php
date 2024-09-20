@extends('layouts.client')

@section('content')
    <div class="d-flex flex-column justify-content-center align-items-center vh-100">
        <img src="{{ asset('assets/vectors/qmis_logo_x2.svg') }}" alt="Logo" class="img-fluid"
            style="width: 160px; height: 70px; margin-top: 20px;">
        <div class="text-center">
            <h3>{{ __('messages.Welcome_to_Queen_Mira_International_School')}}</h3>
            <p>🎉 {{ __('messages.2024-2025 Vijayadasami Admissions Now Open: Up to 50% OFF')}} 🎉
            </p>
        </div>
        <form id="otpForm" method="POST" action="{{ route('client.storeEnquiry') }}" enctype="multipart/form-data">
            @csrf

            <input type="hidden" name="campaign" value="{{ request()->campaign }}">
            <input type="hidden" name="source" value="{{ request()->source }}">
            <input type="hidden" name="sub_source_name" value="{{ request()->sub_source }}">
            <input type="hidden" name="ip_address" value="{{ request()->user_ip }}">
            <input type="hidden" name="city" value="{{ request()->user_city }}">
            <input type="hidden" name="browser" value="{{ request()->browser }}">
            <input type="hidden" name="user_os" value="{{ request()->user_os }}">
            <input type="hidden" name="landing_page" value="{{ request()->landing_page }}">
            <input type="hidden" name="ref" value="{{ request()->ref }}">
            <input type="hidden" name="traffic_src" value="{{ request()->traffic_src }}">
            <input type="hidden" name="sub_no" value="{{ request()->sub_no }}">
            <input type="hidden" name="date_time" value="{{ request()->date_time }}">


            <div class="card mt-4" style="max-width: 100%; width: 300px;">
                <div class="card-header text-center">
                    <h5 style="color: #0A1629">{{ __('messages.Sign Up for QMIS')}}</h5>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="relation_type" style="color:#7D8592">{{ __('messages.Who am I?')}}</label>
                        <select name="relation_type" id="relation_type" class="form-control" required style="color:#7D8592">
                            <option value="">{{ __('messages.Select The Relation Type')}}</option>
                            <option value="father">{{ __('messages.Father')}}</option>
                            <option value="mother">{{ __('messages.Mother')}}</option>
                            <option value="guardian">{{ __('messages.Guardian')}}</option>
                            <option value="student">{{ __('messages.Student')}}</option>
                        </select>
                    </div>
                    <div id="student" style="display: none;" class="form-group">
                        <div class="form-group">
                            <label style="color:#7D8592">{{ __('messages.If you are a student, please have your parent or guardian fill out the form.')}}</label>
                        </div>

                    </div>
                    <div id="father" style="display: none;" class="form-group">
                        <div class="form-group">
                            <label style="color:#7D8592">{{ __('messages.Father\'s Name')}}<span class="text-danger">*</span></label>
                            <input type="text" name="father[name]" id="father_name" class="form-control"
                                style="color:#7D8592">
                        </div>
                        <div class="form-group">
                            <label for="father_phone" style="color:#7D8592">Father's Phone<span
                                    class="text-danger">*</span></label>
                            <div class="d-flex" style="color:#7D8592">
                                <select class="form-control w-auto">
                                    <option value="+91">+91</option>
                                    <option value="+1">+1</option>
                                </select>
                                <input type="number" name="father[phone]" id="father_phone" class="form-control ml-2"
                                    oninput="this.value = this.value.slice(0, 10)">
                            </div>
                            <span style="font-size:12px;">Enter your WhatsApp number to receive your child's application and
                                other resources</span>
                        </div>
                        <div class="form-group" style="color:#7D8592">
                            <label for="father_email" style="color:#7D8592">Father's mail</span></label>
                            <input type="email" name="father[email]" id="father_email" class="form-control"
                                style="color:#7D8592">
                        </div>
                    </div>
                    <div id="mother" style="display: none;" class="form-group">
                        <label style="color:#7D8592">Mother's Name<span class="text-danger">*</span></label>
                        <input type="text" name="mother[name]" id="mother_name" class="form-control"
                            style="color:#7D8592">
                        <div class="form-group">
                            <label style="color:#7D8592">Mother's Phone<span class="text-danger">*</span></label>
                            <div class="d-flex">
                                <select class="form-control w-auto" style="color:#7D8592">
                                    <option value="+91">+91</option>
                                    <option value="+1">+1</option>
                                </select>
                                <input type="number" name="mother[phone]" id="mother_phone" class="form-control ml-2">
                            </div>
                            <span style="font-size:12px;">Enter your WhatsApp number to receive your child's application
                                and other resources</span>
                        </div>
                        <div class="form-group">
                            <label style="color:#7D8592">Mother's Mail</label>
                            <input type="email" name="mother[email]" id="mother_email" class="form-control"
                                style="color:#7D8592">
                        </div>
                    </div>
                    <div id="guardian" style="display: none;" class="form-group">
                        <div class="form-group">
                            <label style="color:#7D8592">Guardian Name<span class="text-danger">*</span></label>
                            <input type="text" name="guardian[name]" id="guardian_name" class="form-control"
                                style="color:#7D8592">
                        </div>
                        <div class="form-group">
                            <label style="color:#7D8592" for="guardian_relation">Guardian Relation<span
                                    class="text-danger">*</span></label>
                            <input type="text" name="guardian[relationship]" id="guardian_relation"
                                class="form-control">
                        </div>
                        <div class="form-group">
                            <label style="color:#7D8592">Guardian's Phone<span class="text-danger">*</span></label>
                            <div class="d-flex">
                                <select class="form-control w-auto" style="color:#7D8592">
                                    <option value="+91">+91</option>
                                    <option value="+1">+1</option>
                                </select>
                                <input type="number" name="guardian[phone]" id="guardian_phone"
                                    class="form-control ml-2" style="color:#7D8592">
                            </div>
                            <span style="font-size:12px;">Enter your WhatsApp number to receive your child's application
                                and other resources</span>
                        </div>
                        <div class="form-group">
                            <label style="color:#7D8592">Guardian's Mail</label>
                            <input type="email" name="guardian[email]" id="guardian_email" class="form-control"
                                style="color:#7D8592">
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4" style="margin-bottom:5%">
                    <button type="submit" class="btn btn-primary" style="background-color: #10194A;font-weight:600">
                        Submit
                        <img src="{{ asset('assets/vectors/arrow_2_x2.svg') }}" alt="Arrow Icon"
                            style="width: 16px; height: 16px; margin-left: 5px;">
                    </button>
                </div>
                <div class="text-center mt-3" style="margin: 3%;">
                    <!-- Separate Text -->
                    <p style="color: #000; font-weight: 600; margin-bottom: 5px;">
                        {{__('messages.If_you_already_have_an_account')}} </p>
                    <!-- Button for Sign In -->
                    <a href="{{ route('client.login') }}" class="text-decoration-none"
                        style="font-weight: Bold;color:#D21B21">
                        {{__('messages.Sign In Here')}} </a>
                    <div class=" text-center align-items-center justify-content-center d-flex">
                    <a href="{{ route('locale', 'en') }}" class="text-decoration-none mr-1">
                        English </a>
                    <a href="{{ route('locale', 'ta') }}" class="text-decoration-none">
                    தமிழ் </a>

                    </div>
                </div>

            </div>
        </form>
        <input type="hidden" name="lead_id" id="lead_id" value="">
        <form id="verifyOtpForm" action="#" method="POST" style="display:none;">
            @csrf
            <div class="card mt-4" style="max-width: 100%; width: 300px;">
                <div class="card-body">
                    <!-- Mobile OTP Section -->
                    <div class="form-group">
                        <label for="otp1">Enter Mobile OTP:</label>
                        <div class="otp-container">
                            <input type="text" class="otp-input" maxlength="1" oninput="moveToNext(this, 'otp2')"
                                id="otp1" name="otp">
                            <input type="text" class="otp-input" maxlength="1" oninput="moveToNext(this, 'otp3')"
                                id="otp2" name="otp">
                            <input type="text" class="otp-input" maxlength="1" oninput="moveToNext(this, 'otp4')"
                                id="otp3" name="otp">
                            <input type="text" class="otp-input" maxlength="1" id="otp4" name="otp">
                        </div>
                    </div>

                    <!-- Email OTP Section -->
                    {{-- <div class="form-group mt-3">
                        <label for="emailOtp1">Enter Email OTP:</label>
                        <div class="otp-container">
                            <input type="text" class="otp-input" maxlength="1"
                                oninput="moveToNext(this, 'emailOtp2')" id="emailOtp1" name="email_otp">
                            <input type="text" class="otp-input" maxlength="1"
                                oninput="moveToNext(this, 'emailOtp3')" id="emailOtp2" name="email_otp">
                            <input type="text" class="otp-input" maxlength="1"
                                oninput="moveToNext(this, 'emailOtp4')" id="emailOtp3" name="email_otp">
                            <input type="text" class="otp-input" maxlength="1" id="emailOtp4" name="email_otp">
                        </div>
                    </div> --}}

                    <button type="submit" class="btn btn-success btn-block mt-4">Verify OTP & Login</button>
                    <!-- Resend OTP Button -->
                    <div class="text-center mt-3">
                        <button type="button" id="resendOtpBtn" class="btn btn-link" style="color: #D21B21;">Resend
                            OTP</button>
                    </div>


                    <div id="otpError" class="mt-2 text-danger" style="display: none;"></div>
                </div>
            </div>
        </form>
    </div>

    <!-- Student Modal -->
    {{-- <div class="modal fade" id="studentModal" tabindex="-1" role="dialog" aria-labelledby="studentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="studentModalLabel">Notice</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Unfortunately, the form can only be filled by your parents or a guardian.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div> --}}
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            window.history.pushState(null, "", window.location.href);
            window.onpopstate = function() {
                window.history.pushState(null, "", window.location.href);
            };
        });
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('relation_type').addEventListener('change', function() {
                const relation = this.value;
                document.getElementById('father').style.display = (relation === 'father') ? 'block' :
                    'none';
                document.getElementById('mother').style.display = (relation === 'mother') ? 'block' :
                    'none';
                document.getElementById('guardian').style.display = (relation === 'guardian') ? 'block' :
                    'none';
                document.getElementById('student').style.display = (relation === 'student') ? 'block' :
                    'none';
                // if (relation === 'student') {
                //     $('#studentModal').modal('show');
                // }
            });

            document.getElementById('otpForm').addEventListener('submit', function(event) {
                event.preventDefault();
                const submitButton = event.target.querySelector('button[type="submit"]');

                // Disable the button
                submitButton.disabled = true;

                // Change the color while the button is disabled
                submitButton.style.backgroundColor = '#d3d3d3'; // Gray background
                submitButton.style.color = '#7a7a7a';
                submitButton.innerText = 'Please wait...';
                fetch(this.action, {
                        method: 'POST',
                        body: new FormData(this),
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                        }
                    })

                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            var verifyOtpUrl = `{{ route('client.verifyOtp', ['id' => ':id']) }}`;
                            verifyOtpUrl = verifyOtpUrl.replace(':id', data.lead_id);
                            console.log('Verify OTP URL:', verifyOtpUrl);
                            document.getElementById('lead_id').value = data.lead_id;
                            document.getElementById('verifyOtpForm').action = verifyOtpUrl;

                            document.getElementById('otpForm').style.display = 'none';
                            document.getElementById('verifyOtpForm').style.display = 'block';
                            disableBackButton();
                        } else {
                            alert(data.message || 'Failed to send OTP.');
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });

            document.getElementById('verifyOtpForm').addEventListener('submit', function(event) {
                event.preventDefault();
                const submitButton = event.target.querySelector('button[type="submit"]');

                // Disable the button
                submitButton.disabled = true;

                // Change the color while the button is disabled
                submitButton.style.backgroundColor = '#d3d3d3'; // Gray background
                submitButton.style.color = '#7a7a7a';
                submitButton.innerText = 'Please wait...';
                const mobileOtp = Array.from(document.querySelectorAll(
                        '#verifyOtpForm .otp-input[name="otp"]'))
                    .map(input => input.value).join('');
                const emailOtp = Array.from(document.querySelectorAll(
                        '#verifyOtpForm .otp-input[name="email_otp"]'))
                    .map(input => input.value).join('');
                if (mobileOtp.length < 4) {
                    document.getElementById('otpError').innerText = 'Please enter valid OTPs.';
                    document.getElementById('otpError').style.display = 'block';
                    return;
                }

                const formData = new FormData(this);
                formData.append('otp', mobileOtp);
                formData.append('email_otp', emailOtp);
                fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log(data.redirect);
                        if (data.success) {
                            window.location.href = data.redirect;
                        } else {
                            document.getElementById('otpError').innerText = data.message ||
                                'Failed to verify OTP.';
                            document.getElementById('otpError').style.display = 'block';
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });

            document.querySelectorAll('.otp-input').forEach(function(input) {
                input.addEventListener('input', function() {
                    if (this.value.length === this.maxLength) {
                        let nextInput = this.nextElementSibling;
                        while (nextInput && nextInput.classList.contains('otp-input') === false) {
                            nextInput = nextInput.nextElementSibling;
                        }
                        if (nextInput) {
                            nextInput.focus();
                        }
                    }
                });
            });


            document.getElementById('resendOtpBtn').addEventListener('click', function() {

                var url = `{{ route('client.resendOtp', ['id' => ':id']) }}`;
                var o_url = url.replace(':id', document.getElementById('lead_id').value);
                fetch(o_url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                        },
                        body: JSON.stringify({
                            lead_id: document.getElementById('lead_id')
                                .value // Replace with actual lead ID if available
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('OTP has been resent successfully.');
                        } else {
                            alert(data.message || 'Failed to resend OTP.');
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });

        });


        function disableBackButton() {
            window.history.pushState(null, '', window.location.href);
            window.onpopstate = function(event) {
                window.history.pushState(null, '', window.location.href);
            };


            window.addEventListener('beforeunload', function() {
                window.onpopstate = null;
            });
        }

        function moveToNext(current, nextId) {
            if (current.value.length === current.maxLength) {
                document.getElementById(nextId).focus();
            }
        }
    </script>
    <style>
        .otp-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 20px 0;
        }

        .otp-input {
            width: 40px;
            height: 40px;
            text-align: center;
            font-size: 1rem;
            /* Adjusted for better readability */
            margin: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
            overflow-wrap: break-word;
            font-family: 'Nunito Sans', sans-serif;
            font-weight: 400;
            line-height: 1.5;
            color: #0A1629;
        }

        .otp-input:focus {
            outline: none;
            border-color: #0A1629;
        }
    </style>
@endsection
