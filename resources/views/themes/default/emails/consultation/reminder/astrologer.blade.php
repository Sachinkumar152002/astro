@extends(themeView().'emails.layout')
@section('content')
  <div class="">
    <p>Hi {{$astrologer->display_name}}</p>
    <p>This is a reminder about your scheduled legal consultation today:</p>
    <table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse;">
      <tr>
        <th>User</th>
        <td>{{$appointment->name}}</td>
      </tr>
      <tr>
        <th>Time</th>
        <td>{{date('h:i a', strtotime($appointment->scheduled_date.' '.$appointment->scheduled_time))}}</td>
      </tr>
      <tr>
        <th>Mode of Consultation</th>
        @if($appointment->package == 1)
          <td>Video</td>
        @elseif($appointment->package == 2)
          <td>Chat</td>
        @elseif($appointment->package == 3)
          <td>Call</td>
        @endif
      </tr>
    </table>
    <p>Please ensure you are available to connect with the user through the {{ config("app.name") }} platform at the scheduled time.</p>
  </div>
@endsection
