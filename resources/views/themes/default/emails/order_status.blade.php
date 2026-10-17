@extends(themeView().'emails.layout')
@section('content')
  <div class="">
    <p>Hi {{$order->billing_full_name}},</p>
    <p>We hope this message finds you well! We are writing to inform you about the status of your recent order placed through the {{config('app.name')}} web and app portal.</p>
    <h3>Order Details:</h3>
    <table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse;">
        <tr>
            <th>Order ID</th>
            <td>{{$order->order_number}}</td>
        </tr>
        <tr>
            <th>Order Status</th>
            <td>{{$order->order_status}}</td>
        </tr>
    </table>
    <h3>Delivery Instructions:</h3>
    <ol>
        <li>Please ensure that someone is available at the delivery address to receive the package.</li>
        <li>If you have specific delivery preferences (e.g., leave with a neighbour, delivery time preferences), please reply to this email with those details.</li>
        <li>Track your order using the tracking link provided in your order confirmation email to stay updated on its progress.</li>
    </ol>
    <p>Thank you for choosing {{config('app.name')}}. If you have any questions or need further assistance, feel free to reach out to our support team.</p>
  </div>
@endsection
