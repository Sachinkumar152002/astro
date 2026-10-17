@extends(themeView().'emails.layout')
@section('content')
  <div class="">
    <p>Hi {{$order->billing_full_name}},</p>
    <p>We hope this message finds you well. We are writing to inform you that your recent attempt to book an order on our web and app portal at {{config('app.name')}} has unfortunately failed.</p>
    <p>This can happen for a variety of reasons, including:</p>
    <ul>
      <li>Payment processing issues</li>
      <li>Incorrect information provided during checkout</li>
      <li>Technical errors on our platform</li>
    </ul>
    <p>To resolve this issue, we kindly ask you to:</p>
    <ol>
      <li>Review your order details and check for any discrepancies.</li>
      <li>Ensure your payment method is valid and up to date.</li>
      <li>Attempt to place your order again.</li>
    </ol>
  </div>
@endsection
