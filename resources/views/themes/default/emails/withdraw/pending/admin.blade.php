@extends(themeView().'emails.layout')
@section('content')
  <div class="">
    <p>Hi Admin,</p>
    <p>A new withdrawal request has been initiated by {{$astrologer->display_name}}.</p>
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
    <p>Please review and process the request via the Admin Dashboard.</p>
    <a href="route('admin.withdraw.edit', [$data->id])">Click</a>
  </div>
@endsection
