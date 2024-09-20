<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>
<body>

    <script>
        var options = {
            "key": "{{ env('RAZORPAY_KEY') }}",
            "amount": "{{ $order['amount'] }}",
            "currency": "INR",
            "name": "QMIS Portal",
            "description": "Test Transaction",
            "order_id": "{{ $order['id'] }}",
            "handler": function (response){
                // Ensure both id and sv_id are passed correctly
                var id = "{{ request()->id }}";
                var sv_id = "{{ request()->sv_id }}";

                // Redirect to the payment completion route
                window.location.href = "{{ route('client.siteVisit.paymentCompletion', ['id' => ':id', 'sv_id' => ':sv_id']) }}"
                    .replace(':id', id)
                    .replace(':sv_id', sv_id)
                    + "?id=" + id
                    + "&sv_id=" + sv_id
                    + "&razorpay_payment_id=" + response.razorpay_payment_id
                    + "&razorpay_order_id=" + response.razorpay_order_id
                    + "&razorpay_signature=" + response.razorpay_signature;
            },
            "prefill": {
                "name": "John Doe",
                "email": "john.doe@example.com",
                "contact": "9999999999"
            }
        };

        var rzp1 = new Razorpay(options);
        rzp1.open();
        e.preventDefault();
    </script>
</body>
</html>
