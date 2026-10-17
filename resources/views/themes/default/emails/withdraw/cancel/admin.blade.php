@extends(themeView().'emails.layout')
@section('content')
  <div class="">
    <p>Hi Admin,</p>
    <p>The withdrawal request from {{$astrologer->display_name}} has been Declined.</p>
    <p>Request Details:</p>
    <table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse;">
      <tr>
        <th>Lawyer</th>
        <td>{{$astrologer->display_name}}</td>
      </tr>
      <tr>
        <th>Amount</th>
        <td>₹{{$data->amount}}</td>
      </tr>
      <tr>
        <th>Date & Time</th>
        <td>{{date('d F Y h:i a', strtotime($data->created_at))}}</td>
      </tr>
      <tr>
        <th>Status</th>
        <td>Declined</td>
      </tr>
    </table>
    <p>You may log this action for financial records.</p>
  </div>
@endsection
