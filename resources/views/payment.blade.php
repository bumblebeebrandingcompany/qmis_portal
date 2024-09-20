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
            "description": "Enquiry No:{{1000+$lead->id}}, Order #{{$lead->id}}",
            "order_id": "{{ $order['id'] }}", 
            "handler": function (response){
                window.location.href = "{{route('client.paymentCompletion', request()->id)}}?id={{request()->id}}&razorpay_payment_id=" + response.razorpay_payment_id + "&razorpay_order_id=" + response.razorpay_order_id + "&razorpay_signature=" + response.razorpay_signature;
            },
            "prefill": {
                "name": "{{$lead->name}}",
                "email": "{{$lead->email}}",
                "contact": "{{$lead->phone}}",
                "notes": "Father:{{json_encode($lead->father_details)}}, Mother:{{json_encode($lead->mother_details)}}, Student:{{json_encode($lead->student_details)}}, Enquiry No:{{1000+$lead->id}}, Application No:{{2000+$lead->id}}",
                "address": "{{$lead->common_details}}"
            }
        };

        var rzp1 = new Razorpay(options);
            rzp1.open();
            e.preventDefault();
    </script>
</body>
</html>
