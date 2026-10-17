@extends(themeView().'emails.layout')
@section('content')
  <div class="">
    <p>Hi {{$user->name}}</p>
    <p>Thank you for indicating your interest in hiring {{substr($lawyer->firstname, 0, 1)}}. {{$lawyer->lastname}}{{$lawyer->unique_id?'-'.$lawyer->unique_id:''}} or discussing your case further.</p>
    <p>We have notified {{substr($lawyer->firstname, 0, 1)}}. {{$lawyer->lastname}}{{$lawyer->unique_id?'-'.$lawyer->unique_id:''}} of your interest. If the lawyer decides to proceed, they will connect with you directly.</p>
  </div>
@endsection
