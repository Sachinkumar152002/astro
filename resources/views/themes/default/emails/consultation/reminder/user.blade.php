@extends(themeView().'emails.layout')
@section('content')
  <div class="">
    <p>Hi {{$appointment->name}}</p>
    <p>This is a friendly reminder about your upcoming legal consultation scheduled for today:</p>
    <table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse;">
      <tr>
        <th>Lawyer</th>
        <td>{{substr($astrologer->firstname, 0, 1)}}. {{$astrologer->lastname}}{{$astrologer->unique_id?'-'.$astrologer->unique_id:''}}</td>
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
    @if($appointment->package == 1)
      <p>Please be ready to receive the video call from your legal expert through the {{ config("app.name") }} platform at the scheduled time.</p>
    @elseif($appointment->package == 2)
    <p>Remember to log in to your {{ config("app.name") }} account at {{route('user.consultation.history')}} to start your chat consultation.</p>
    @elseif($appointment->package == 3)
      <p>Please be ready to receive the call from your legal expert through the {{ config("app.name") }} platform at the scheduled time.</p>
    @endif
    <p>If you encounter any issues or have last-minute questions, please contact our support team immediately.</p>
  </div>
@endsection
