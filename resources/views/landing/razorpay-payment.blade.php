<!DOCTYPE html>
<html>
<head>
    <title>Razorpay Payment</title>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<script>
    var options = {
        "key": "{{ config('services.razorpay.key') }}",
        "amount": {{ $amount }},
        "currency": "INR",
        "name": "{{ $module }}",
        "description": "Pay ₹{{ $amount / 100 }}",
        "order_id": "{{ $order_id }}",
        "handler": function (response) {
            $.ajax({
                url: "{{ route('payment.callback') }}",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    razorpay_payment_id: response.razorpay_payment_id
                },
                success: function(data) {
                    if (data.redirect_url) {
                        window.location.href = data.redirect_url;
                    } else {
                        alert("Redirect failed.");
                    }
                },
                error: function(xhr) {
                    let msg = xhr.responseJSON?.error || "Payment failed. Please try again.";
                    alert(msg);
                }
            });
        },
        "prefill": {
            "name": "{{ $name }}",
            "email": "{{ $email }}",
            "contact": "{{ $phone }}"
        },
        "theme": {
            "color": "#F37254"
        }
    };

    var rzp1 = new Razorpay(options);
    window.onload = function () {
        rzp1.open();
    };
</script>



</body>
</html>
