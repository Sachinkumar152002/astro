@extends(themeView().'emails.layout')
@section('content')
  <div class="">
    <p>Dear Admin,</p>
    <p>A new consultation has been booked on {{ config("app.name") }}:</p>
    <table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse;">
      <tr>
        <th>User</th>
        <td>{{$user->name}}</td>
      </tr>
      <tr>
        <th>Astrologer</th>
        <td>{{$astrologer->display_name}}</td>
      </tr>
      <tr>
        <th>Date</th>
        <td>{{date('d/m/Y', strtotime($appointment->scheduled_date))}}</td>
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
    <p>Please monitor the consultation as needed and ensure smooth execution.</p>
  </div>
@endsection
