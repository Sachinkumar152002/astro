@extends(themeView().'emails.layout')
@section('content')
  <div class="">
    <p>Dear {{$astrologer->display_name}}</p>
    <p>Thank you for booking a consultation with {{ config("app.name") }}! We're excited to connect you with an experienced expert.</p>
    <p>Here are the details of your scheduled consultation:</p>
    <table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse;">
      <tr>
        <th>User Name</th>
        <td>{{$appointment->name}}</td>
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
    <p>Please be prepared to connect with the user at the scheduled time via the {{ config("app.name") }} platform.</p>
    @if($appointment->package == 1)
      <p>You will be able to initiate the video call through your {{ config("app.name") }} professional dashboard at the scheduled time.</p>
    @elseif($appointment->package == 2)
    <p>Please log in to your {{ config("app.name") }} professional dashboard at {{route('astrologer.consultations')}} to be ready for the chat consultation.</p>
    @elseif($appointment->package == 3)
      <p>You will be able to initiate the call through your {{ config("app.name") }} professional dashboard at the scheduled time.</p>
    @endif
  </div>
@endsection
