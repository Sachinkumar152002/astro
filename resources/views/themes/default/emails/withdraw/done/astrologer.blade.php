@extends(themeView().'emails.layout')
@section('content')
  <div class="">
    <p>Hi {{$astrologer->display_name}},</p>
    <p>Your withdrawal request for ₹{{$data->amount}} has been Approved.</p>
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
        <td>Approved</td>
      </tr>
    </table>
  </div>
@endsection
