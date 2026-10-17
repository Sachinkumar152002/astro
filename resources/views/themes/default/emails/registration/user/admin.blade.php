@extends(themeView().'emails.layout')
@section('content')
  <div class="">
    <p>Dear Admin,</p>
    <p>A new user, {{$user->name}} ({{$user->email}} & {{$user->phone}}), has registered on the {{ config("app.name") }} platform.</p>
    <p>Please review the new user's profile and ensure all information is accurate.</p>
  </div>
@endsection
