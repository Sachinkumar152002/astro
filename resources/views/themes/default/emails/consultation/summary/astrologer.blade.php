@extends(themeView().'emails.layout')
@section('content')
  <div class="">
    <p>Dear {{ $astrologer->display_name }},</p>
    <p>Today's legal consultation booked Details:</p>
    <table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse;">
      <thead>
        <tr>
          <th>User</th>
          <th>Mode</th>
          <th>Date & Time</th>
        </tr>
      </thead>
      <tbody>
        @foreach($appointmentsArray as $key => $appointment)
          <tr>
            <td>{{$appointment->name}}</td>
            @if($appointment->package == 1)
              <td>Video</td>
            @elseif($appointment->package == 2)
              <td>Call</td>
            @else
              <td>Chat</td>
            @endif
            <td>{{ date('d-m-Y h:i a', strtotime($appointment->scheduled_date.' '.$appointment->scheduled_time)) }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
    <p>Please Be on time.</p>
  </div>
@endsection
