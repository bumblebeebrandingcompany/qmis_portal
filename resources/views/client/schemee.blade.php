<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Choose Scheme</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .payment-card {
            border: 1px solid #e3e3e3;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            margin: 20px auto;
        }

        .payment-card h3 {
            margin-bottom: 20px;
        }

        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }

        .btn-success:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }

        .btn-success:focus,
        .btn-success.focus {
            box-shadow: 0 0 0 0.2rem rgba(56, 203, 128, 0.5);
        }
    </style>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>

<body>
    <div class="container mt-5">
        <h1>Choose Scheme and Option</h1>
        <form id="scheme-form" action="{{ route('client.chooseSchemeOption', ['id' => $lead->id]) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="scheme">Select Scheme:</label>
                <select id="scheme" name="scheme" class="form-control" required>
                    <option value="scheme1">Scheme 1</option>
                    <option value="scheme2">Scheme 2</option>
                </select>
            </div>

            <div class="form-group">
                <label for="option">Select Option:</label>
                <select id="option" name="option" class="form-control" required>
                    <option value="application_purchase">Application Purchase</option>
                    <option value="site_visit_schedule">Site Visit Schedule</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Proceed</button>
        </form>
        <!-- Payment Modal -->
        <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content payment-card">
                    <div class="modal-header">
                        <h5 class="modal-title" id="paymentModalLabel">Complete Your Payment</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="payment-form" action="{{ route('client.completePayment', ['id' => $lead->id]) }}"
                            method="POST">
                            @csrf
                            <input type="hidden" name="amount" value="${data.amount}">
                            <input type="hidden" name="scheme" value="${data.scheme}">
                            <div class="text-center">
                                <button type="button" id="rzp-button" class="btn btn-success">Pay
                                    ₹${data.amount}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        document.getElementById('scheme-form').addEventListener('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            fetch('{{ route('client.chooseSchemeOption', ['id' => $lead->id]) }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok.');
                    }
                    return response.json();
                })
                .then(data => {
                    document.querySelector('#paymentModal .modal-body').innerHTML = `
                    <form id="payment-form" action="{{ route('client.completePayment', ['id' => $lead->id]) }}" method="POST">
                        @csrf
                        <input type="hidden" name="amount" value="${data.amount}">
                        <input type="hidden" name="scheme" value="${data.scheme}">
                        <div class="text-center">
                            <button type="button" id="rzp-button" class="btn btn-success">Pay ₹${data.amount}</button>
                        </div>
                    </form>
                `;

                    var options = {
                        "key": "{{ config('services.razorpay.key') }}",
                        "amount": "${data.amount * 100}",
                        "currency": "INR",
                        "name": "Your Company Name",
                        "description": "Payment Transaction",
                        "handler": function(response) {
                            document.querySelector('#payment-form').appendChild(createHiddenInput(
                                'razorpay_payment_id', response.razorpay_payment_id));
                            document.querySelector('#payment-form').appendChild(createHiddenInput(
                                'razorpay_order_id', response.razorpay_order_id));
                            document.querySelector('#payment-form').appendChild(createHiddenInput(
                                'razorpay_signature', response.razorpay_signature));
                            document.querySelector('#payment-form').submit();
                        },
                        "prefill": {
                            "name": "Your Name",
                            "email": "you@example.com",
                            "contact": "9999999999"
                        },
                        "theme": {
                            "color": "#3399cc"
                        }
                    };

                    var rzp1 = new Razorpay(options);
                    document.getElementById('rzp-button').onclick = function(e) {
                        rzp1.open();
                        e.preventDefault();
                    };

                    function createHiddenInput(name, value) {
                        var input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = name;
                        input.value = value;
                        return input;
                    }

                    $('#paymentModal').modal('show');
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });
    </script>
</body>

</html>
