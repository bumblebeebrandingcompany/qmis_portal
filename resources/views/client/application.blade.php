@extends('layouts.client')

@section('content')
<div class="screen-6">
    <img class="qmis-logo-white" src="{{ asset('assets/vectors/qmis_logo_white_3_x2.svg') }}" />

    <div class="content">

        <p class="lorem-ipsum">
            <b>
                You are just one step away from accessing a bundle of perks offered by Queen Mira.
            </b>
        </p>

        <div class="line-2"></div>
        @php
            $pricePerItem = 299; // Define the price per item
            $childDetailsCount = is_array($lead->student_details) ? count($lead->student_details) : 0; // Check if it's an array and get count
            $totalPrice = $childDetailsCount * $pricePerItem; // Calculate the total price
        @endphp
        <p class="price m-0">₹ {{ $totalPrice }}</p>
        <span class="lorem-ipsum mt-0">Apply now to receive exclusive Vijayadasami offers, personalized guidance documents curated by our career counsellors, and valuable resources, including schemes and  recommendations.
        </span>


        @php
            $features[] = 'Complete fee structure & schemes';
            $features[] = 'Vijayadashami offers & benefits';
            $features[] = 'School prospectus for Kindergarten.';
            $features[] = 'Exclusive parent guidebook: A comparative analysis of schools in Madurai.';
            $features[] = 'Free subscription to the school newsletter.';
            $features[] = 'A glimpse into "Happy Schooling" at Queen Mira.(pics of kids gym, latest events & more)';
        @endphp
        <div class="line-2"></div>

        <div class="features">
            @foreach ($features as $feature)
                <div class="feature-item">
                    <img class="check-icon" src="{{ asset('assets/vectors/subtract_10_x2.svg') }}" />
                    <span>{{ $feature }}</span>
                </div>
            @endforeach
        </div>

        <div class="feature-item">
            {{--<div class="check-fill-7">
                <img class="subtract-7" src="{{ asset('assets/vectors/subtract_16_x2.svg') }}" />
            </div>--}}
            <p class="terms">
                By applying you agree to our <a href="http://User Agreement">User Agreement</a>,
                <a href="http://Privacy Policy">Privacy Policy</a>, and <a href="http://Cookie Policy">Cookie
                    Policy</a>.
            </p>
        </div>
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <a class="pay-now" href="{{ route('client.createpayment', ['id' => $lead->id, 'amount' => '299']) }}">APPLY NOW</a>
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
                amount: {{ $totalPrice * 100 }} // Convert amount to paise
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(order => {
            var options = {
                "key": "{{ env('RAZORPAY_KEY') }}",
                "amount": order.amount,
                "currency": "INR",
                "name": "Your Company",
                "description": "Test Transaction",
                "order_id": order.id,
                "handler": function(response) {
                    var form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/payment/complete/${id}`;

                    ['razorpay_payment_id', 'razorpay_order_id', 'razorpay_signature'].forEach(field => {
                        var input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = field;
                        input.value = response[field];
                        form.appendChild(input);
                    });

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
        margin-top: 10vh;
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
        /* font-size: 0.875rem;
        line-height: 1.5; */
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
        text-align: left;
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
