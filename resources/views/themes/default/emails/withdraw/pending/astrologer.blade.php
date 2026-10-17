@extends(themeView().'emails.layout')
@section('content')
  <div class="">
    <p>Hi {{$astrologer->display_name}},</p>
    <p>Your withdrawal request of ₹{{$data->amount}} has been successfully submitted on {{date('d F Y h:i a', strtotime($data->created_at))}}.</p>
    <p>Our team will review and process the request shortly. You'll be notified once the status is updated.</p>
    <p>Request Details:</p>
    <table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse;">
      <tr>
        <th>Name</th>
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
        <td>Pending</td>
      </tr>
    </table>
  </div>
@endsection
