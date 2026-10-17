@extends(themeView().'emails.layout')
@section('content')
  <div class="">
    <p>Hi {{$appointment->name}}</p>
    <p>Thank you for booking a consultation with {{ config("app.name") }}! We're excited to connect you with an experienced expert.</p>
    <p>Here are the details of your scheduled consultation:</p>
    <table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse;">
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
    @if($appointment->package == 1)
      <p>Please ensure you are ready with a stable internet connection and a quiet environment at the scheduled time. Your expert will initiate the video call through the {{ config("app.name") }} platform.</p>
    @elseif($appointment->package == 2)
    <p>Please log in to your {{ config("app.name") }} account at {{route('user.consultations')}} at the scheduled time to begin your chat consultation.</p>
    @elseif($appointment->package == 3)
      <p>Please ensure you are ready with a stable internet connection and a quiet environment at the scheduled time. Your expert will initiate the phone call through the {{ config("app.name") }} platform.</p>
    @endif
  </div>
@endsection
