@extends(themeView().'emails.layout')
@section('content')
  <div class="">
    <p>Hi {{$invoice->customer_name}},</p>
    <p>We are pleased to inform you that your recharge was successful!</p>
    <h3>Transaction Details:</h3>
    <ul>
        <li><strong>Transaction ID:</strong> {{$invoice->my_txn_id}}</li>
        <li><strong>Amount:</strong> ₹ {{$invoice->total_amount}}</li>
        <li><strong>Talk Amount:</strong> ₹ {{$invoice->taktime_value}}</li>
        <li><strong>Date & Time:</strong> {{date('d/m/Y h:i a', strtotime($invoice->created_at))}}</li>
        <li><strong>Payment Method:</strong> Online</li>
    </ul>
    <table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse;">
        <tr>
            <th>Transaction ID</th>
            <td>{{$invoice->my_txn_id}}</td>
        </tr>
        <tr>
            <th>Amount</th>
            <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{$invoice->total_amount}}</td>
        </tr>
        <tr>
            <th>Talk Amount</th>
            <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{$invoice->taktime_value}}</td>
        </tr>
        <tr>
            <th>Date & Time</th>
            <td>{{date('d/m/Y h:i a', strtotime($invoice->created_at))}}</td>
        </tr>
        <tr>
            <th>Payment Method</th>
            <td>Online</td>
        </tr>
    </table>
    <p>Thank you for choosing {{config('app.name')}}. We appreciate your trust in our services.</p>
  </div>
@endsection
