@extends(themeView().'emails.layout')
@section('content')
  <div class="">
    <p>Hi {{$user->name}},</p>
    @if($data->package == 1)
    <p>Your video consultation with {{substr($astrologer->firstname, 0, 1)}}. {{$astrologer->lastname}}{{$astrologer->unique_id?'-'.$astrologer->unique_id:''}} on {{date('d-m-Y', strtotime($data->scheduled_date))}} at {{date('h:i a', strtotime($data->scheduled_time))}} could not proceed as you didn’t join the session. As a result, it has been cancelled.</p>
    @elseif($data->package == 2)
    <p>Your chat consultation with {{substr($astrologer->firstname, 0, 1)}}. {{$astrologer->lastname}}{{$astrologer->unique_id?'-'.$astrologer->unique_id:''}} scheduled for {{date('d-m-Y', strtotime($data->scheduled_date))}} at {{date('h:i a', strtotime($data->scheduled_time))}} was cancelled because you didn’t join the session.</p>
    @elseif($data->package == 3)
    <p>We tried to connect you with {{substr($astrologer->firstname, 0, 1)}}. {{$astrologer->lastname}}{{$astrologer->unique_id?'-'.$astrologer->unique_id:''}} for your scheduled phone consultation on {{date('d-m-Y', strtotime($data->scheduled_date))}} at {{date('h:i a', strtotime($data->scheduled_time))}}. Unfortunately, you did not respond to two call attempts, and the session has been cancelled.</p>
    @endif
    <p>Details:</p>
    <table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse;">
      <tr>
        <th>Lawyer</th>
        <td>{{substr($astrologer->firstname, 0, 1)}}. {{$astrologer->lastname}}{{$astrologer->unique_id?'-'.$astrologer->unique_id:''}}</td>
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
    </table>
    <p>You can book another session at your convenience:</p>
    <a href="{{route('astrologers.publicview', [$astrologer->type, $astrologer->slug])}}">Book Again</a>
  </div>
@endsection
