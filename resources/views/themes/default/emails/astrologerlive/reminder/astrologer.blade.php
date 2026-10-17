@extends(themeView().'emails.layout')
@section('content')
  <div class="">
    <p>Hi {{$astrologer->display_name}}</p>
    <p>This is a reminder about your live stream scheduled today:</p>
    <p>Date {{ date('d/m/Y', strttotime($astrologerLive->start_at)) }}</p>
    <p>From {{ date('h:i a', strttotime($astrologerLive->start_at)) }} To {{ date('h:i a', strttotime($astrologerLive->end_at)) }}</p>
    <p>Please ensure you are available to connect through the {{ config("app.name") }} platform at the scheduled time.</p>
  </div>
@endsection
