@extends('layouts.client')

@section('content')
    <div class="screen-6">
        <img class="qmis-logo-white" src="{{ asset('assets/vectors/qmis_logo_white_3_x2.svg') }}" />

        <div class="content">
            <button class="purchase-application">BOOK YOUR PERSONALIZED SESSION</button>

            {{-- <p class="lorem-ipsum">Lorem ipsum dolor sit amet, consectetur<br />adipiscing elit. Fusce ac
                condimentum<br />Orci varius natoque penatibus</p> --}}

            <div class="line-2"></div>

            @php
                $pricePerItem = 999; // Define the price per item
                $childDetailsCount = is_array($lead->student_details) ? count($lead->student_details) : 0; // Check if it's an array and get count
                $totalPrice = $childDetailsCount * $pricePerItem; // Calculate the total price
            @endphp
            <p class="price">₹ {{ $totalPrice }}</p>

            <span class="lorem-ipsum">This Session Includes</span>

            <div class="line-2"></div>

            <div class="features">
                <div class="feature-item">
                    <img class="check-icon" src="{{ asset('assets/vectors/subtract_10_x2.svg') }}" />
                    <span>Complete fee structure & schemes</span>
                </div>
                <div class="feature-item">
                    <img class="check-icon" src="{{ asset('assets/vectors/subtract_10_x2.svg') }}" />
                    <span>Vijayadashami offers & benefits</span>
                </div>
                <div class="feature-item">
                    <img class="check-icon" src="{{ asset('assets/vectors/subtract_10_x2.svg') }}" />
                    <span>School prospectus for KG admission.</span>
                </div>
                <div class="feature-item" style="text-align: left">
                    <img class="check-icon" src="{{ asset('assets/vectors/subtract_10_x2.svg') }}" />
                    <span>Exclusive parent's guidebook: a comparative analysis of schools in Madurai</span>
                </div>
                <div class="feature-item" style="text-align: left">
                    <img class="check-icon" src="{{ asset('assets/vectors/subtract_10_x2.svg') }}" />
                    <span>Free subscription to the school newsletter.</span>
                </div>
                <div class="feature-item" style="text-align: left">
                    <img class="check-icon" src="{{ asset('assets/vectors/subtract_10_x2.svg') }}" />
                    <span>A free demo class to see our teaching methods in action.</span>
                </div>
                <div class="feature-item" style="text-align: left">
                    <img class="check-icon" src="{{ asset('assets/vectors/subtract_10_x2.svg') }}" />
                    <span>An interview and assessment for your child.</span>
                </div>
                <div class="feature-item" style="text-align: left">
                    <img class="check-icon" src="{{ asset('assets/vectors/subtract_10_x2.svg') }}" />
                    <span>A comprehensive scorecard that gives you insights into your child’s personality and skillset.</span>
                </div>
                <div class="feature-item" style="text-align: left">
                    <img class="check-icon" src="{{ asset('assets/vectors/subtract_10_x2.svg') }}" />
                    <span>A chance to get all your doubts and queries answered directly.</span>
                </div>
                <div class="feature-item" style="text-align: left">
                    <img class="check-icon" src="{{ asset('assets/vectors/subtract_10_x2.svg') }}" />
                    <span>Let’s make your journey to Queen Mira as informative and rewarding as possible!</span>
                </div>
            </div>


            <div class="feature-item">
                <div class="check-fill-7">
                    <img class="subtract-7" src="{{ asset('assets/vectors/subtract_16_x2.svg') }}" />
                </div>
                <p class="terms">
                    By clicking "Send Application," you agree to our <a href="http://User Agreement">User Agreement</a>,
                    <a href="http://Privacy Policy">Privacy Policy</a>, and <a href="http://Cookie Policy">Cookie
                        Policy</a>.
                </p>
            </div>
            <meta name="csrf-token" content="{{ csrf_token() }}">

            <button class="pay-now" onclick="makePayment({{ $lead->id }})">PAY NOW</button>
        </div>

        <div class="popup-overlay" id="popupOverlay">
            <div class="popup-content">
                <img src="{{ asset('assets/vectors/subtract_17_x2.svg') }}" alt="Success" class="popup-image"
                    style="margin-top:20%;width:131px;height:131px">

                <p class="popup-message" style="margin-top:10%">You are successfully registered!</p>

                <a class="start-button" href="{{ route('client.schemeDetails', ['id' => $lead->id]) }}">Let's Start</a>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        function makePayment(id) {
            fetch(`/client/payment/${id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify({
                        amount: 29900
                    }) // Amount in paise
                })
                .then(response => response.json())
                .then(order => {
                    var options = {
                        "key": "{{ env('RAZORPAY_KEY') }}", // Your Razorpay Key ID
                        "amount": order.amount, // Amount in paise
                        "currency": "INR",
                        "name": "Your Company",
                        "description": "Test Transaction",
                        "order_id": order.id,
                        "handler": function(response) {
                            // Send the payment details to your server
                            var form = document.createElement('form');
                            form.method = 'POST';
                            form.action = `/payment/complete/${id}`;

                            var input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'razorpay_payment_id';
                            input.value = response.razorpay_payment_id;
                            form.appendChild(input);

                            var inputOrder = document.createElement('input');
                            inputOrder.type = 'hidden';
                            inputOrder.name = 'razorpay_order_id';
                            inputOrder.value = response.razorpay_order_id;
                            form.appendChild(inputOrder);

                            var inputSignature = document.createElement('input');
                            inputSignature.type = 'hidden';
                            inputSignature.name = 'razorpay_signature';
                            inputSignature.value = response.razorpay_signature;
                            form.appendChild(inputSignature);

                            document.body.appendChild(form);
                            form.submit();
                        },
                        "theme": {
                            "color": "#D21B21"
                        }
                    };

                    var payment = new Razorpay(options);
                    payment.open();
                })
                .catch(error => console.error('Error:', error));
        }
    </script>
@endsection


@section('scripts')
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        function makePayment(id) {
            fetch(`/client/payment/${lead->id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify({
                        amount: 29900
                    }) // Amount in paise
                })
                .then(response => response.json())
                .then(order => {
                    var options = {
                        "key": "{{ env('RAZORPAY_KEY') }}", // Your Razorpay Key ID
                        "amount": order.amount, // Amount in paise
                        "currency": "INR",
                        "name": "Your Company",
                        "description": "Test Transaction",
                        "order_id": order.id,
                        "handler": function(response) {
                            // Send the payment details to your server
                            var form = document.createElement('form');
                            form.method = 'POST';
                            form.action = `/payment/complete/${id}`;

                            var input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'razorpay_payment_id';
                            input.value = response.razorpay_payment_id;
                            form.appendChild(input);

                            var inputOrder = document.createElement('input');
                            inputOrder.type = 'hidden';
                            inputOrder.name = 'razorpay_order_id';
                            inputOrder.value = response.razorpay_order_id;
                            form.appendChild(inputOrder);

                            var inputSignature = document.createElement('input');
                            inputSignature.type = 'hidden';
                            inputSignature.name = 'razorpay_signature';
                            inputSignature.value = response.razorpay_signature;
                            form.appendChild(inputSignature);

                            document.body.appendChild(form);
                            form.submit();
                        },
                        "theme": {
                            "color": "#D21B21"
                        }
                    };

                    var payment = new Razorpay(options);
                    payment.open();
                })
                .catch(error => console.error('Error:', error));
        }
    </script>
@endsection


@section('styles')
    <style>
        html,
        body {
            margin: 0;
            padding: 0;
            height: 100%;
            width: 100%;
            font-family: 'Poppins', sans-serif;
        }

        .screen-6 {
            display: flex;
            flex-direction: column;
            background-color: #001C80;
            color: #ffffff;
            height: 100vh;
            width: 100vw;
            overflow-y: auto;
            overflow-x: hidden;
            text-align: center;
            padding: 20px;
            position: relative;
        }

        .qmis-logo-white {
            position: absolute;
            top: 20px;
            left: 20px;
            width: 150px;
            height: auto;
        }

        .content {
            display: flex;
            flex-direction: column;
            margin-top: 30%;
            gap: 20px;
        }

        .purchase-application,
        .pay-now,
        .start-button {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            text-transform: uppercase;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .purchase-application {
            color: #D21B21;
            background-color: #ffffff;
            font-size: 0.9rem;
        }

        .pay-now {
            font-size: 1rem;
            color: #ffffff;
            background-color: #D21B21;
        }

        .pay-now:hover {
            background-color: #b20a0a;
        }

        .lorem-ipsum {
            font-size: 0.875rem;
            line-height: 1.5;
            text-align: center;
            margin: 10px 0;
        }

        .line-2 {
            opacity: 0.8;
            background: #FFFFFF;
            width: 100%;
            height: 2px;
            border: 0;
            align-items: center;
        }

        .price {
            font-size: 2.5rem;
            font-weight: 700;
            color: #FFFFFF;
            margin: 20px 0;
        }

        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 10px;
            margin: 20px 0;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .check-icon {
            width: 20px;
            height: auto;
        }

        .terms {
            font-size: 0.75rem;
            line-height: 1.5;
            text-align: left;
        }

        .terms a {
            color: #D21B21;
            text-decoration: none;
        }

        .popup-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .popup-content {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            width: 90%;
            max-width: 400px;
        }

        .popup-image {
            width: 60px;
            height: auto;
            margin-bottom: 20px;
        }

        .popup-message {
            font-size: 1.2rem;
            margin-bottom: 20px;
            color: #001C80;
        }

        .start-button {
            background-color: #001C80;
            color: #ffffff;
        }

        .start-button:hover {
            background-color: #000a4d;
        }
    </style>
@endsection

{{-- @section('scripts')
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        function makePayment() {
            fetch('{{ route('client.payment', ['id' => $lead->id, 'amount' => 299, 'scheme' => 'application_purchase']) }}', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => {
                    console.log('Response Status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Response Data:', data);
                    if (data.error) {
                        throw new Error(data.error);
                    }
                    if (!data.id) {
                        throw new Error('Order ID missing from response');
                    }

                    var options = {
                        key: '{{ env('RAZORPAY_KEY') }}',
                        amount: '29900',
                        currency: 'INR',
                        name: 'Your Company',
                        description: 'Test Transaction',
                        order_id: data.id,
                        handler: function(response) {
                            fetch('{{ route('client.completePayment', ['id' => $lead->id]) }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                            .getAttribute('content')
                                    },
                                    body: JSON.stringify({
                                        razorpay_payment_id: response.razorpay_payment_id,
                                        razorpay_order_id: response.razorpay_order_id,
                                        razorpay_signature: response.razorpay_signature,
                                        scheme: 'application_purchase'
                                    })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.status === 'success') {
                                        showPopup();
                                    } else {
                                        alert('Payment verification failed.');
                                    }
                                })
                                .catch(error => {
                                    console.error('Error during payment verification:', error);
                                    alert('Payment verification failed.');
                                });
                        },
                        prefill: {
                            name: '',
                            email: '',
                            contact: ''
                        }
                    };

                    var paymentObject = new Razorpay(options);
                    paymentObject.open();
                })
                .catch(error => {
                    console.error('Error during payment process:', error);
                    alert('Failed to retrieve payment information. Please try again.');
                });
        }



        function showPopup() {
            document.getElementById('popupOverlay').style.display = 'flex';
        }
    </script>
@endsection --}}
