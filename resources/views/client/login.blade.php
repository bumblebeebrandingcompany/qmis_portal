@extends('layouts.client')

@section('content')
    <div class="screen-3">
        <div class="system-light-status-bar-default"></div>
        <img class="qmis-logo" src="{{ asset('assets/vectors/qmis_logo_x2.svg') }}" />
        <div class="container">
            <div class="tell-about-your-comp">
                Login to QMIS
            </div>
            <form id="otpForm" action="{{ route('client.sendOtp') }}" method="POST">
                @csrf
                <div class="form-group">
                    <div class="container-3">
                        <div class="position">
                            Mobile Number
                        </div>
                        <div class="container-4">
                            <select class="container-5">
                                <option value="+91">+ 91</option>
                                <option value="+1">+ 1</option>
                            </select>
                            <input type="text" name="mobile" id="mobile" class="form-control" required>
                        </div>
                    </div>
                </div>
                <button type="submit" class="main-button">
                    <span class="next-step">Send OTP</span>
                    <div class="icngeneralarrowgowhite">
                        <img class="arrow-1" src="{{ asset('assets/vectors/arrow_2_x2.svg') }}" />
                    </div>
                </button>
            </form>
            <input type="hidden" name="lead_id" id="lead_id" value="">

            <form id="verifyOtpForm" action="#" method="POST" style="display:none;">
                @csrf
                <div class="form-group">
                    <label for="otp">Enter Mobile OTP:</label>
                    <div class="otp-container">
                        <input type="text" class="otp-input" maxlength="1" oninput="moveToNext(this, 'otp2')"
                            id="otp1" name="otp" required>
                        <input type="text" class="otp-input" maxlength="1" oninput="moveToNext(this, 'otp3')"
                            id="otp2" name="otp" required>
                        <input type="text" class="otp-input" maxlength="1" oninput="moveToNext(this, 'otp4')"
                            id="otp3" name="otp" required>
                        <input type="text" class="otp-input" maxlength="1" id="otp4" name="otp" required>
                    </div>
                </div>
                <button type="submit" class="main-button">
                    <span class="next-step">Verify OTP & Login</span>
                    <div class="icngeneralarrowgowhite">
                        <img class="arrow-1" src="{{ asset('assets/vectors/arrow_2_x2.svg') }}" />
                    </div>
                </button>
                <div class="text-center mt-3">
                    <button type="button" id="resendOtpBtn" class="btn btn-link" style="color: #D21B21;">Resend
                        OTP</button>
                </div>
                <div id="otpError" class="mt-2 text-danger" style="display: none;"></div>
            </form>
        </div>
    </div>



    <script>
        function showOtpForm(leadId) {
            document.getElementById('otpForm').style.display = 'none';
            document.getElementById('verifyOtpForm').style.display = 'block';
            document.getElementById('verifyOtpForm').action = `/client/login/verify-otp/${leadId}`;
        }

        document.getElementById('otpForm').addEventListener('submit', function(e) {
            e.preventDefault();
            let form = this;
            let mobile = document.getElementById('mobile').value;
            const submitButton = event.target.querySelector('button[type="submit"]');

            // Disable the button
            submitButton.disabled = true;

            // Change the color while the button is disabled
            submitButton.style.backgroundColor = '#d3d3d3'; // Gray background
            submitButton.style.color = '#7a7a7a';
            submitButton.innerText = 'Please wait...';
            fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        mobile: mobile
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('lead_id').value = data.lead_id;
                        showOtpForm(data.lead_id);
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
        });

        document.getElementById('verifyOtpForm').addEventListener('submit', function(e) {
            e.preventDefault();
            let form = this;
            let otp = [...form.querySelectorAll('.otp-input')].map(input => input.value).join('');
            const submitButton = event.target.querySelector('button[type="submit"]');

            // Disable the button
            submitButton.disabled = true;

            // Change the color while the button is disabled
            submitButton.style.backgroundColor = '#d3d3d3'; // Gray background
            submitButton.style.color = '#7a7a7a';
            submitButton.innerText = 'Please wait...';
            fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        otp: otp
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = data.redirect;
                    } else {
                        document.getElementById('otpError').innerText = data.otp_error;
                        document.getElementById('otpError').style.display = 'block';
                    }
                })
                .catch(error => console.error('Error:', error));
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

        function moveToNext(current, nextFieldId) {
            if (current.value.length >= 1 && nextFieldId) {
                document.getElementById(nextFieldId).focus();
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

        .screen-3 .qmis-logo {
            position: absolute;
            left: 50%;
            top: 2.3rem;
            translate: -50% 0;
            width: 10rem;
            height: 4.8rem;
        }

        .screen-3 .container-8 {
            margin: 0.2rem 0;
            display: flex;
            flex-direction: row;
            column-gap: 0.3rem;
            width: 4.2rem;
            height: fit-content;
            box-sizing: border-box;
        }

        .screen-3 .system-light-status-bar-default {
            position: relative;
            margin: 0 0 7.1rem 0.2rem;
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            width: calc(100% - 0.2rem);
            box-sizing: border-box;
        }

        .screen-3 .tell-about-your-comp {
            margin: 0 0.1rem 2rem 0;
            display: inline-block;
            overflow-wrap: break-word;
            font-family: 'Nunito Sans';
            font-weight: 700;
            font-size: 1.1rem;
            line-height: 1.444;
            color: #0A1629;
        }

        .screen-3 .position-1 {
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

        .screen-3 .container-2 {
            margin-right: 0.8rem;
            width: 2.6rem;
            overflow-wrap: break-word;
            font-family: 'Nunito Sans';
            font-weight: 400;
            font-size: 0.9rem;
            line-height: 1.714;
            color: #7D8592;
        }

        .screen-3 .arrow {
            width: 0.6rem;
            height: 0.4rem;
        }

        .screen-3 .icngeneralarrowdowngray {
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

        .screen-3 .inputwithiconright {
            margin: 0 0.4rem 1.2rem 0;
            display: flex;
            flex-direction: column;
            width: calc(100% - 0.4rem);
            box-sizing: border-box;
        }

        .screen-3 .position-3 {
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

        .screen-3 .ui-ux-designer-1 {
            overflow-wrap: break-word;
            font-family: 'Nunito Sans';
            font-weight: 400;
            font-size: 0.9rem;
            line-height: 1.714;
            color: #7D8592;
        }

        .screen-3 .container-6 {
            box-shadow: 0rem 0.1rem 0.1rem 0rem rgba(184, 200, 224, 0.222);
            border-radius: 0.3rem;
            border: 0.1rem solid #D8E0F0;
            background: #FFFFFF;
            position: relative;
            padding: 0.7rem 1.1rem 0.8rem 1.1rem;
            width: 100%;
            box-sizing: border-box;
        }

        .screen-3 .input-1 {
            margin: 0 0.4rem 1.2rem 0;
            display: flex;
            flex-direction: column;
            width: calc(100% - 0.4rem);
            box-sizing: border-box;
        }

        .screen-3 .bggeneralinputselected {
            opacity: 0.119;
            border-radius: 0.9rem;
            background: #3F8CFF;
            position: absolute;
            left: 0rem;
            bottom: 0rem;
            width: 5rem;
            height: 3.4rem;
        }

        .screen-3 .bggeneralinputselected-1 {
            opacity: 0.119;
            border-radius: 0.9rem;
            background: #3F8CFF;
            position: absolute;
            right: 0rem;
            bottom: 0rem;
            width: 12.3rem;
            height: 3.4rem;
        }

        .screen-3 .position {
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

        .screen-3 .container {
            margin-right: 0.6rem;
            overflow-wrap: break-word;
            font-family: 'Nunito Sans';
            font-weight: 700;
            font-size: 0.9rem;
            line-height: 1.5;
            color: #0A1629;
        }

        .screen-3 .arrow-2 {
            width: 0.4rem;
            height: 0.6rem;
        }

        .screen-3 .icngeneralarrowdarkdown {
            margin: 0.4rem 0 0.5rem 0;
            display: flex;
            width: 1.5rem;
            height: 1.5rem;
            box-sizing: border-box;
        }

        .screen-3 .container-5 {
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

        .screen-3 .container-1 {
            overflow-wrap: break-word;
            font-family: 'Nunito Sans';
            font-weight: 400;
            font-size: 0.9rem;
            line-height: 1.5;
            color: #0A1629;
        }

        .screen-3 .container-2 {
            border-radius: 0.9rem;
            border: 0.1rem solid #3F8CFF;
            background: #FFFFFF;
            position: relative;
            padding: 0.8rem 1.1rem 0.9rem 1.1rem;
            flex-grow: 1;
            flex-basis: 11.9rem;
            box-sizing: border-box;
        }

        .screen-3 .container-4 {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            width: 100%;
            box-sizing: border-box;
        }

        .screen-3 .container-3 {
            position: relative;
            display: flex;
            flex-direction: column;
            width: 100%;
            height: fit-content;
            box-sizing: border-box;
        }

        .screen-3 .inputmobilenumberactive {
            position: relative;
            margin-bottom: 1.4rem;
            display: flex;
            padding: 0 0.2rem 0.2rem 0.2rem;
            width: 100%;
            box-sizing: border-box;
        }

        .screen-3 .position-2 {
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

        .screen-3 .ui-ux-designer {
            overflow-wrap: break-word;
            font-family: 'Nunito Sans';
            font-weight: 400;
            font-size: 0.9rem;
            line-height: 1.714;
            color: #7D8592;
        }

        .screen-3 .container-7 {
            box-shadow: 0rem 0.1rem 0.1rem 0rem rgba(184, 200, 224, 0.222);
            border-radius: 0.9rem;
            border: 0.1rem solid #D8E0F0;
            background: #FFFFFF;
            position: relative;
            padding: 0.7rem 1.1rem 0.8rem 1.1rem;
            width: 100%;
            box-sizing: border-box;
        }

        .screen-3 .input {
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

        .screen-3 .next-step {
            margin-right: 0.7rem;
            overflow-wrap: break-word;
            font-family: 'Nunito Sans';
            font-weight: 700;
            font-size: 1rem;
            color: #FFFFFF;
        }

        .screen-3 .arrow-1 {
            width: 1rem;
            height: 0.8rem;
        }

        .screen-3 .icngeneralarrowgowhite {
            margin: 0.3rem 0;
            display: flex;

            box-sizing: border-box;
        }

        .screen-3 .main-button {
            box-shadow: #10194A;
            border-radius: 0.3rem;
            background: #10194A;
            position: relative;
            margin-left: 0.1rem;
            display: flex;
            /* flex-direction: row; */
            padding: 0.8rem 1.3rem;
            width: fit-content;
            /* box-sizing: border-box; */
        }

        /* .screen-3 .main-button {

            border-radius: 0.3rem;
            background: #10194A;
            position: relative;
            margin-right: 0.1rem;
            display: flex;
            flex-direction: row;
            padding: 0.8rem 0;
          } */
        .screen-3 .container {
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

        .screen-3 {
            background: #F4F9FD;
            display: flex;
            flex-direction: column;
            padding: 0.9rem 0.9rem 3.4rem 1.3rem;
            box-sizing: border-box;
        }
    </style>
@endsection
