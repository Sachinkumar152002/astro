@extends(themeView().'emails.layout')
@section('content')
  <div class="">
    <p>Hi Admin,</p>
    @if($data->package == 1)
    <p>The following video consultation has been cancelled due to the user not joining the session.</p>
    @elseif($data->package == 2)
    <p>The following chat consultation was auto-cancelled because the user did not join the session.</p>
    @elseif($data->package == 3)
    <p>The scheduled phone consultation was automatically cancelled as the user did not respond to two call attempts.</p>
    @endif
    <p>Details:</p>
    <table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse;">
      <tr>
        <th>User</th>
        <td>{{$user->name}}</td>
      </tr>
      <tr>
        <th>Lawyer</th>
        <td>{{$astrologer->display_name}}</td>
      </tr>
      <tr>
        <th>Mode</th>
        @if($data->package == 1)
          <td>Video</td>
        @elseif($data->package == 2)
          <td>Chat</td>
        @elseif($data->package == 3)
          <td>Call</td>
        @endif
      </tr>
      <tr>
        <th>Date & Time</th>
        <td>{{date('d F Y h:i a', strtotime($data->scheduled_date.' '.$data->scheduled_time))}}</td>
      </tr>
      <tr>
        <th>Reason</th>
        @if($data->package == 1)
        <td>User\Lawyer have not joined Video call</td>
          <td>Video</td>
        @elseif($data->package == 2)
          <td>User\Lawyer have not joined chat.</td>
        @elseif($data->package == 3)
        <td>User\Lawyer did not pick up the call</td>
        @endif
      </tr>
      <tr>
        <th>Status</th>
        <td>Cancelled</td>
      </tr>
    </table>
    <p>Please check and take necessary follow-up actions.</p>
  </div>
@endsection
