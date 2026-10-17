@extends(themeView().'emails.layout')
@section('content')
  <div class="">
    <p>Hi {{$user->name}}</p>
    <p>Thank you for indicating your interest in hiring {{substr($astrologer->firstname, 0, 1)}}. {{$astrologer->lastname}}{{$astrologer->unique_id?'-'.$astrologer->unique_id:''}} or discussing your case further.</p>
    <p>We have notified {{substr($astrologer->firstname, 0, 1)}}. {{$astrologer->lastname}}{{$astrologer->unique_id?'-'.$astrologer->unique_id:''}} of your interest. If the lawyer decides to proceed, they will connect with you directly.</p>
  </div>
@endsection
