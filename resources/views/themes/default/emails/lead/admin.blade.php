@extends(themeView().'emails.layout')
@section('content')
  <div class="">
    <p>Dear Admin,</p>
    <p>User {{$user->name}} has expressed interest in hiring or having a more detailed discussion with lawyer {{$astrologer->display_name}} following their consultation.</p>
    <p>Lawyer {{$astrologer->display_name}} has been notified and will have the option to purchase the lead's contact information.</p>
    <p>No immediate action is required from your end, but you can monitor the lead status in the admin panel.</p>
  </div>
@endsection
