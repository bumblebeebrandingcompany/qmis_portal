@extends('layouts.client')

@section('content')
        <div class="system-light-status-bar-default"></div>
        <img class="qmis-logo" src="{{ asset('assets/vectors/qmis_logo_x2.svg') }}" />
        <form id="otpForm" method="POST" action="{{ route('client.storeEnquiry') }}" enctype="multipart/form-data">
            @csrf
            <div class="container">
                <div class="tell-about-your-comp">
                    Sign Up to QMIS
                </div>
                <div class="form-group">
                    <label for="relation_type" class="position-1">Who am I?</label>
                    <select name="relation_type" id="relation_type" class="form-control" required>
                        <option value="">Select Relation Type</option>
                        <option value="father">Father</option>
                        <option value="mother">Mother</option>
                        <option value="guardian">Guardian</option>
                        <option value="student">Student</option>
                    </select>
                </div>

                <div id="father" style="display: none;" class="form-controll">
                    <div class="form-group">
                        <div class="input-1">
                            <label class="position-3">
                                Father Name
                            </label>
                            <input type="text" name="father[name]" id="father_name" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="container-3">
                            <div class="position">
                                Mobile Number
                            </div>
                            <div class="container-4">
                                <select class="container-5">
                                    <div class="icngeneralarrowdarkdown">
                                        <img class="arrow-2" src="{{ asset('assets/vectors/arrow_53_x2.svg') }}" />
                                    </div>
                                </select>
                                <input type="text" name="father[phone]" id="father_phone" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-1">
                            <label class="position-3">
                                Email Address
                            </label>
                            <input type="email" name="father[email]" id="father_email" class="form-control">
                        </div>
                    </div>
                </div>
                <div id="mother" style="display: none;" class="form-controll">
                    <div class="form-group">
                        <div class="input-1">
                            <label class="position-3">
                                Mother Name
                            </label>
                            <input type="text" name="mother[name]" id="mother_name" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="container-3">
                            <div class="position">
                                Mobile Number
                            </div>
                            <div class="container-4">
                                <select class="container-5">
                                    <div class="icngeneralarrowdarkdown">
                                        <img class="arrow-2" src="/assets/vectors/arrow_53_x2.svg" />
                                    </div>
                                </select>
                                <input type="text" name="mother[phone]" id="mother_phone" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-1">
                            <label class="position-3">
                                Email Address
                            </label>
                            <input type="email" name="mother[email]" id="mother_email" class="form-control">
                        </div>
                    </div>
                </div>
                <div id="guardian" style="display: none;" class="form-controll">
                    <div class="form-group">
                        <div class="input-1">
                            <label class="position-3">
                                Guardian Name
                            </label>
                            <input type="text" name="guardian[name]" id="guardian_name" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="guardian_relation" class="position-1">Guardian Relation</label>
                        <input type="text" name="guardian[relationship]" id="guardian_relation" class="form-control">
                    </div>
                    <div class="form-group">
                        <div class="container-3">
                            <div class="position">
                                Mobile Number
                            </div>
                            <div class="container-4">
                                <select class="container-5">
                                    <div class="icngeneralarrowdarkdown">
                                        <img class="arrow-2" src="/assets/vectors/arrow_53_x2.svg" />
                                    </div>
                                </select>
                                <input type="text" name="guardian[phone]" id="guardian_phone" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-1">
                            <label class="position-3">
                                Email Address
                            </label>
                            <input type="email" name="guardian[email]" id="guardian_email" class="form-control">
                        </div>
                    </div>
                </div>
                <button class="main-button">
                    <span class="next-step">Submit</span>
                    <div class="icngeneralarrowgowhite">
                        <img class="arrow-1" src="{{ asset('assets/vectors/arrow_2_x2.svg') }}" />
                    </div>
                </button>
                <div class="mt-3">
                    <a href="{{ route('client.login') }}" class="btn btn-link">Already have an account? Login
                        here</a>
                </div>
            </div>
        </form>
    </div>
    <form id="verifyOtpForm" action="#" method="POST" style="display:none;">
        @csrf
        <div class="form-group">
            <label for="otp">Enter Mobile OTP:</label>
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
        <div class="form-group">
            <label for="email_otp">Enter Email OTP:</label>
            <div class="otp-container">
                <input type="text" class="otp-input" maxlength="1" oninput="moveToNext(this, 'emailOtp2')"
                    id="emailOtp1" name="email_otp">
                <input type="text" class="otp-input" maxlength="1" oninput="moveToNext(this, 'emailOtp3')"
                    id="emailOtp2" name="email_otp">
                <input type="text" class="otp-input" maxlength="1" oninput="moveToNext(this, 'emailOtp4')"
                    id="emailOtp3" name="email_otp">
                <input type="text" class="otp-input" maxlength="1" id="emailOtp4" name="email_otp">
            </div>
        </div>
        <button type="submit" class="btn btn-success btn-block">Verify OTP & Login</button>
        <div id="otpError" class="mt-2 text-danger" style="display: none;"></div>
    </form>
@endsection
@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script>
        function moveToNext(current, nextFieldId) {
            if (current.value.length === 1) {
                const nextField = document.getElementById(nextFieldId);
                if (nextField) nextField.focus();
            }
        }

        document.getElementById('relation_type').addEventListener('change', function() {
            var relationType = this.value;

            document.getElementById('father').style.display = 'none';
            document.getElementById('mother').style.display = 'none';
            document.getElementById('guardian').style.display = 'none';

            if (relationType === 'father') {
                document.getElementById('father').style.display = 'block';
            } else if (relationType === 'mother') {
                document.getElementById('mother').style.display = 'block';
            } else if (relationType === 'guardian') {
                document.getElementById('guardian').style.display = 'block';
            } else if (relationType === 'student') {
                $('#studentModal').modal('show');
                this.value = '';
            }
        });

        document.getElementById('otpForm').addEventListener('submit', function(event) {
            event.preventDefault();

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

                        document.getElementById('verifyOtpForm').action = verifyOtpUrl;

                        document.getElementById('otpForm').style.display = 'none';
                        document.getElementById('verifyOtpForm').style.display = 'block';
                    } else {
                        alert(data.message || 'Failed to send OTP.');
                    }
                })
                .catch(error => console.error('Error:', error));
        });

        document.getElementById('verifyOtpForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const mobileOtp = Array.from(document.querySelectorAll('#verifyOtpForm .otp-input[name="otp"]'))
                .map(input => input.value).join('');
            const emailOtp = Array.from(document.querySelectorAll('#verifyOtpForm .otp-input[name="email_otp"]'))
                .map(input => input.value).join('');
            if (mobileOtp.length < 4 || emailOtp.length < 4) {
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
                    if (data.success) {
                        window.location.href = data.redirect;
                    } else {
                        document.getElementById('otpError').innerText = data.message || 'Failed to verify OTP.';
                        document.getElementById('otpError').style.display = 'block';
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    </script>
    <style>
         .container-8 {
            margin: 0.2rem 0;
            display: flex;
            flex-direction: row;
            column-gap: 0.3rem;
            width: 4.2rem;
            height: fit-content;
            box-sizing: border-box;
        }

         .system-light-status-bar-default {
            position: relative;
            margin: 0 0 7.1rem 0.2rem;
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            width: calc(100% - 0.2rem);
            box-sizing: border-box;
        }

         .tell-about-your-comp {
            margin: 0 0.1rem 2rem 0;
            display: inline-block;
            overflow-wrap: break-word;
            font-family: 'Nunito Sans';
            font-weight: 700;
            font-size: 1.1rem;
            line-height: 1.444;
            color: #0A1629;
        }

         .position-1 {
            margin: 0 0.4rem 0.4rem 0.4rem;
            display: inline-block;
            align-self: flex-start;
            overflow-wrap: break-word;
            font-family: 'Nunito Sans';
            font-weight: 700;
            font-size: 0.9rem;
            line-height: 1.714;
            color: #7D8592;
        }

         .container-2 {
            margin-right: 0.8rem;
            width: 2.6rem;
            overflow-wrap: break-word;
            font-family: 'Nunito Sans';
            font-weight: 400;
            font-size: 0.9rem;
            line-height: 1.714;
            color: #7D8592;
        }

         .arrow {
            width: 0.6rem;
            height: 0.4rem;
        }

         .icngeneralarrowdowngray {
            margin: 0.6rem 0 0.5rem 0;
            display: flex;
            width: 1.5rem;
            height: 1.5rem;
            box-sizing: border-box;
        }

        .form-control {
            box-shadow: 0rem 0.1rem 0.1rem 0rem rgba(184, 200, 224, 0.222);
            border-radius: 0.3rem;
            border: 0.1rem solid #D8E0F0;
            background: #FFFFFF;
            position: relative;
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            padding: 0.7rem 1.6rem 0.9rem 1.1rem;
            width: 100%;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            border-color: #3F8CFF;
            outline: none;
        }


        .form-controll {
            border-radius: 0.3rem;
            /* border: 0.1rem solid #D8E0F0; */
            background: #FFFFFF;
            position: relative;
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            padding: 0.7rem 1.6rem 0.9rem 1.1rem;
            width: 100%;
            box-sizing: border-box;
        }

         .inputwithiconright {
            margin: 0 0.4rem 1.2rem 0;
            display: flex;
            flex-direction: column;
            width: calc(100% - 0.4rem);
            box-sizing: border-box;
        }

         .position-3 {
            margin: 0 0.4rem 0.4rem 0.4rem;
            display: inline-block;
            align-self: flex-start;
            overflow-wrap: break-word;
            font-family: 'Nunito Sans';
            font-weight: 700;
            font-size: 0.9rem;
            line-height: 1.714;
            color: #7D8592;
        }

         .ui-ux-designer-1 {
            overflow-wrap: break-word;
            font-family: 'Nunito Sans';
            font-weight: 400;
            font-size: 0.9rem;
            line-height: 1.714;
            color: #7D8592;
        }

         .container-6 {
            box-shadow: 0rem 0.1rem 0.1rem 0rem rgba(184, 200, 224, 0.222);
            border-radius: 0.3rem;
            border: 0.1rem solid #D8E0F0;
            background: #FFFFFF;
            position: relative;
            padding: 0.7rem 1.1rem 0.8rem 1.1rem;
            width: 100%;
            box-sizing: border-box;
        }

         .input-1 {
            margin: 0 0.4rem 1.2rem 0;
            display: flex;
            flex-direction: column;
            width: calc(100% - 0.4rem);
            box-sizing: border-box;
        }

         .bggeneralinputselected {
            opacity: 0.119;
            border-radius: 0.9rem;
            background: #3F8CFF;
            position: absolute;
            left: 0rem;
            bottom: 0rem;
            width: 5rem;
            height: 3.4rem;
        }

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

         .bggeneralinputselected-1 {
            opacity: 0.119;
            border-radius: 0.9rem;
            background: #3F8CFF;
            position: absolute;
            right: 0rem;
            bottom: 0rem;
            width: 12.3rem;
            height: 3.4rem;
        }

         .position {
            margin: 0 0.4rem 0.5rem 0.4rem;
            display: inline-block;
            align-self: flex-start;
            overflow-wrap: break-word;
            font-family: 'Nunito Sans';
            font-weight: 700;
            font-size: 0.9rem;
            line-height: 1.714;
            color: #7D8592;
        }

         .container {
            margin-right: 0.6rem;
            overflow-wrap: break-word;
            font-family: 'Nunito Sans';
            font-weight: 700;
            font-size: 0.9rem;
            line-height: 1.5;
            color: #0A1629;
        }

         .arrow-2 {
            width: 0.4rem;
            height: 0.6rem;
        }

         .icngeneralarrowdarkdown {
            margin: 0.4rem 0 0.5rem 0;
            display: flex;
            width: 1.5rem;
            height: 1.5rem;
            box-sizing: border-box;
        }

         .container-5 {
            border-radius: 0.9rem;
            border: 0.1rem solid #7D8592;
            background: #FFFFFF;
            position: relative;
            margin-right: 1rem;
            display: flex;
            flex-direction: row;
            padding: 0.8rem 1.2rem 0.9rem 1.1rem;
            flex-grow: 1;
            flex-basis: 4.6rem;
            box-sizing: border-box;
        }

         .container-1 {
            overflow-wrap: break-word;
            font-family: 'Nunito Sans';
            font-weight: 400;
            font-size: 0.9rem;
            line-height: 1.5;
            color: #0A1629;
        }

         .container-2 {
            border-radius: 0.9rem;
            border: 0.1rem solid #3F8CFF;
            background: #FFFFFF;
            position: relative;
            padding: 0.8rem 1.1rem 0.9rem 1.1rem;
            flex-grow: 1;
            flex-basis: 11.9rem;
            box-sizing: border-box;
        }

         .container-4 {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            width: 100%;
            box-sizing: border-box;
        }

         .container-3 {
            position: relative;
            display: flex;
            flex-direction: column;
            width: 100%;
            height: fit-content;
            box-sizing: border-box;
        }

         .inputmobilenumberactive {
            position: relative;
            margin-bottom: 1.4rem;
            display: flex;
            padding: 0 0.2rem 0.2rem 0.2rem;
            width: 100%;
            box-sizing: border-box;
        }

         .position-2 {
            margin: 0 0.4rem 0.4rem 0.4rem;
            display: inline-block;
            align-self: flex-start;
            overflow-wrap: break-word;
            font-family: 'Nunito Sans';
            font-weight: 700;
            font-size: 0.9rem;
            line-height: 1.714;
            color: #7D8592;
        }

         .ui-ux-designer {
            overflow-wrap: break-word;
            font-family: 'Nunito Sans';
            font-weight: 400;
            font-size: 0.9rem;
            line-height: 1.714;
            color: #7D8592;
        }

         .container-7 {
            box-shadow: 0rem 0.1rem 0.1rem 0rem rgba(184, 200, 224, 0.222);
            border-radius: 0.9rem;
            border: 0.1rem solid #D8E0F0;
            background: #FFFFFF;
            position: relative;
            padding: 0.7rem 1.1rem 0.8rem 1.1rem;
            width: 100%;
            box-sizing: border-box;
        }

         .input {
            margin-right: 0.4rem;
            display: flex;
            flex-direction: column;
            width: calc(100% - 0.4rem);
            box-sizing: border-box;
        }

        .form-group {
            margin-bottom: 1.8rem;
            display: flex;
            flex-direction: column;
            width: 100%;
            box-sizing: border-box;
        }

         .next-step {
            margin-right: 0.7rem;
            overflow-wrap: break-word;
            font-family: 'Nunito Sans';
            font-weight: 700;
            font-size: 1rem;
            color: #FFFFFF;
        }
         .arrow-1 {
            width: 1rem;
            height: 0.8rem;
        }

         .icngeneralarrowgowhite {
            margin: 0.3rem 0;
            display: flex;

            box-sizing: border-box;
        }

         .main-button {
            box-shadow: #10194A;
            border-radius: 0.3rem;
            background: #10194A;
            position: relative;
            margin-left: 0.1rem;
            display: flex;
            padding: 0.8rem 1.3rem;
            width: fit-content;
        }

         .container {
            box-shadow: 0rem 0.4rem 3.6rem 0rem rgba(196, 203, 214, 0.104);
            border-radius: 0.3rem;
            background: #FFFFFF;
            position: relative;
            margin-right: 0.4rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 2.6rem 1.1rem 2.4rem 1.1rem;
            width: calc(100% - 0.4rem);
            box-sizing: border-box;
        }

         {
            background: #F4F9FD;
            display: flex;
            flex-direction: column;
            padding: 0.9rem 0.9rem 3.4rem 1.3rem;
            box-sizing: border-box;
        }

        .qmis-logo {
            position: relative;
            margin-bottom: 1.9rem;
            width: 10rem;
            height: 4.8rem;
        }
    </style>
@endsection
