@extends(themeView().'emails.layout')
@section('content')
  <div class="">
    <p>Hi {{$user->name}}</p>
    <p>We hope your recent legal consultation with {{substr($lawyer->firstname, 0, 1)}}. {{$lawyer->lastname}}{{$lawyer->unique_id?'-'.$lawyer->unique_id:''}} was helpful.</p>
    <p>If you feel you need further assistance with your matter, you have the option to hire {{substr($lawyer->firstname, 0, 1)}}. {{$lawyer->lastname}}{{$lawyer->unique_id?'-'.$lawyer->unique_id:''}} for detailed case handling or to schedule a more in-depth discussion.</p>
    <p>To express your interest, please click on the button below:</p>
    <a href="{{route('user.createlead', [$enquiry->id, $user->id])}}">Click to show Interest</a>
    <p>Once you express your interest, we will notify the lawyer and the Advolive team.</p>
  </div>
@endsection
