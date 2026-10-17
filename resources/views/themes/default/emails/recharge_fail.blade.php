@extends(themeView().'emails.layout')
@section('content')
  <div class="">
  <p>Hi {{$invoice->customer_name}},</p>
  <p>We hope this message finds you well. We regret to inform you that your recent recharge attempt on our website and app portal at {{config('app.name')}} has failed.</p>

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
          <th>Date and Time</th>
          <td>{{date('d/m/Y h:i a', strtotime($invoice->created_at))}}</td>
      </tr>
      <tr>
          <th>Payment Method</th>
          <td>Online</td>
      </tr>
  </table>

  <p>Please verify the following to resolve the issue:</p>
  <ol>
      <li>Ensure that your payment method has sufficient funds.</li>
      <li>Confirm that all details entered during the recharge process are correct.</li>
      <li>Check for any network issues that may have impacted the transaction.</li>
  </ol>

  <p>We apologize for the inconvenience this may have caused and appreciate your understanding.</p>
  </div>
@endsection
