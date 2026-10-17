@extends(themeView().'emails.layout')
@section('content')
  <div class="">
    <p>Dear Admin,</p>
    <p>Lawyer {{$lawyer->display_name}} has successfully purchased the contact information for user {{$user->name}}, who expressed interest after their consultation.</p>
    <p>Please ensure the lead status is updated in the system and that the transaction for the lead purchase is recorded.</p>
  </div>
@endsection
